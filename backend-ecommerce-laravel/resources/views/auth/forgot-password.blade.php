<x-app-layout>
    <div class="w-full max-w-md mx-auto p-8 my-16 bg-slate-800 rounded-2xl shadow-lg">
        <div class="mb-4 text-sm text-gray-400 text-center">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>
        
        @if (session('status'))
        <!-- Este div será usado por Alpine.js/SweetAlert -->
        <div id="status-message" data-status="{{ session('status') }}"></div>
    @endif

     <!-- Envoltura Alpine -->
<div x-data="{ isSubmitting: false }">
    <form method="POST" action="{{ route('password.email') }}"
          @submit="isSubmitting = true"
    >
        @csrf

        <!-- Email Input -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
            <input
                id="email"
                name="email"
                type="email"
                required
                value="{{ old('email') }}"
                class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                placeholder="your@email.com"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button
                type="submit"
                :disabled="isSubmitting"
                class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-800 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span x-show="!isSubmitting">Send email reset link</span>
                <span x-show="isSubmitting">Sending...</span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-4">
            <p class="text-sm text-slate-400">
                Back to login
                <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">
                    Login
                </a>
            </p>
        </div>

    </form>
</div>
    </div>
</x-app-layout>
