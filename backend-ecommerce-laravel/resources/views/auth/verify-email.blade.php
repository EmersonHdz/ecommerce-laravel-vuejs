<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-8 rounded-2xl text-center">

        <!-- Main Title -->
        <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-4">
            Thank You for Signing Up!
        </h1>
    
        <!-- Verification Message -->
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            {{ __('Before getting started, please verify your email address by clicking the link we just emailed to you. If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
    
        <!-- Success Message -->
        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 text-sm font-semibold text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
    
        <!-- Resend Verification Form -->
        <div x-data="{isSubmitting: false}">
            <form method="POST" action="{{ route('verification.send') }}" @submit="isSubmitting = true">
                @csrf
    
                <div class="mb-6">
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                        class="w-full py-3 px-6 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!isSubmitting">Resend Verification Email</span>
                        <span x-show="isSubmitting">Sending...</span>
                    </button>
                </div>
            </form>
    
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <button 
                    type="submit"
                    class="text-sm text-gray-500 dark:text-gray-400 underline hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-gray-800 transition"
                >
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    
    </div>
    
    
</x-guest-layout>
