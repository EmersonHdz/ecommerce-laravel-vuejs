<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function apiStore(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
    
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $frontendUrl = rtrim(env('FRONTEND_URL', 'http://localhost:5040'), '/');
                $resetUrl = "{$frontendUrl}/reset-password/{$token}?email=".urlencode($user->email);
                
                // Aquí es importante usar una notificación personalizada
                $user->sendPasswordResetNotification($token, $resetUrl);
            }
        );
    
        return $status == Password::RESET_LINK_SENT
            ? response()->json(['message' => trans($status)], 200)
            : response()->json(['error' => trans($status)], 422);
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
