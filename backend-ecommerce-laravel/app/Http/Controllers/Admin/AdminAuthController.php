<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AdminAuthController handles user authentication processes specifically for administrative access.
 * This includes logging in, logging out, and retrieving authenticated user data.
 * 
 * The controller ensures that only users with proper credentials and admin rights can access the system.
 * It also checks that the user's email is verified before allowing access to protected admin routes.
 */

class AdminAuthController extends Controller
{
    /**
     * Handle user login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate request data
        $credentials = $request->validate([
            'email' => ['required', 'email'],   
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        // Extract remember value from credentials
        $remember = $credentials['remember'] ?? false;

        // Remove remember key from credentials
        unset($credentials['remember']);
        
        // Attempt user authentication
        if (!Auth::attempt($credentials, $remember)) {
            // Return response with error message if authentication fails
            return response(['message' => 'Email or password is incorrect'], 422);
        }

        // Get authenticated user
        $user = Auth::user(); 

        // Check if user can authenticate as admin
        if (!$this->canAuthenticateAsAdmin($user)) {
            Auth::logout();
            return response(['message' => 'You don\'t have permission to authenticate as admin'], 403);
        }

        // Check if user's email is verified
        if (!$this->isEmailVerified($user)) {
            Auth::logout();
            return response(['message' => 'Your email address is not verified'], 403);
        }

        // Generate token for the user
        $token = $user->createToken('main')->plainTextToken;

        // Return response with user data and token
        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    /**
     * Logout the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // Get authenticated user
        $user = Auth::user();
        
        // Revoke current user token
        $user->currentAccessToken()->delete();

        // Return empty response with status code 204
        return response('', 204);
    }

    /**
     * Get the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUser(Request $request)
    {
        // Return authenticated user data
        return new UserResource($request->user());
    }

    /**
     * Check if the user can authenticate as an admin.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function canAuthenticateAsAdmin($user)
    {
        // Check if the user is an admin
        return $user->role;
    }

    /**
     * Check if the user's email is verified.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function isEmailVerified($user)
    {
        // Check if the user's email is verified
        return $user->email_verified_at !== null;
    }

    
}



