<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Helpers\Cart;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    DB::beginTransaction();

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            
        ]);

        event(new Registered($user));

        $customer = new Customer();
        $names = explode(" ", $user->name, 2); // Split the name into first and last name
        $customer->user_id = $user->id;
        $customer->first_name = $names[0];
        $customer->last_name = $names[1] ?? '';
        $customer->status = 'active'; 
        $customer->save();

        Auth::login($user);

        // move items from session to DB
        try {
            Cart::MoveCartItemsIntoDB();
        } catch (\Exception $e) {
            // Loggear error but continue
            \Log::error('Error moving cart items: '.$e->getMessage());
        }

        DB::commit();

        return redirect(RouteServiceProvider::HOME);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('User registration failed: '.$e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Unable to register right now.');
    }
}
}
