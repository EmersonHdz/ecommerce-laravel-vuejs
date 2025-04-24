<x-app-layout>
  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />
  <div class="min-h-screen flex items-center justify-center bg-slate-900 p-4">
    <div class="w-full max-w-md bg-slate-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white">Welcome back</h2>
                <p class="text-slate-400 mt-2">Sign in to your account</p>
            </div>

            <!-- div for alpine,js desallow button double click -->
        <div x-data="{
                 isSubmitting: false,
                 showPassword: false,
                    togglePassword() {
          this.showPassword = !this.showPassword; }
          }">

          <form method="POST" action="{{ route('login') }}" class="space-y-6"  @submit="isSubmitting = true">
                    @csrf
    
                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autofocus
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                            placeholder="your@email.com"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
    
                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-emerald-400 hover:text-emerald-300">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        

               
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                class="w-full px-4 py-3 pr-12 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                                placeholder="••••••••"
                            >
                    
                            <!-- Toggle Button -->
                            <button type="button"
                                    @click="togglePassword"
                                    class="absolute top-3 right-3 text-sm text-slate-400 hover:text-white focus:outline-none"
                            >
                                <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                            </button>
                        </div>
                 
                       </div>
    
                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded bg-slate-700 border-slate-600 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-slate-800"
                        >
                        <label for="remember_me" class="ml-2 block text-sm text-slate-300">
                            Remember me
                        </label>
                    </div>
    
                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="isSubmitting"
                                        class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span x-show="!isSubmitting">Sign in</span>
                        <span x-show="isSubmitting">Signing in...</span>
                    </button>
            </form>
        </div>

            <!-- Registration Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-slate-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">
                        Register now
                    </a>
                </p>
            </div>
            
        </div>
    </div>
</div>
    
</x-app-layout>
