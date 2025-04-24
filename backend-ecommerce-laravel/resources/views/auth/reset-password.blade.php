<x-guest-layout>


    <div class="min-h-screen flex items-center justify-center"
    x-data="{
        isSubmitting: false,
        showPassword: false,
        showConfirmPassword: false,
        togglePassword(field) {
            if (field === 'password') {
                this.showPassword = !this.showPassword;
            } else {
                this.showConfirmPassword = !this.showConfirmPassword;
            }
        }
    }">
   <div class="w-full max-w-md bg-slate-800 rounded-xl overflow-hidden">
       <div class="p-4">
           <!-- Header with logo and title -->
           <div class="text-center mb-4">
               <div class="flex justify-center mb-4">
                   <a href="/" class="text-2xl font-bold text-white">Ecommerce LTD</a>
               </div>
               <h2 class="text-2xl font-bold text-white">Reset Your Password</h2>
               <p class="text-slate-400 mt-2">Enter your new password below</p>
           </div>

           <form method="POST" action="{{ route('password.store') }}" class="space-y-5"  @submit="isSubmitting = true">
               @csrf

               <!-- Password Reset Token -->
               <input type="hidden" name="token" value="{{ $request->route('token') }}">

               <!-- Email Input -->
               <div>
                   <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                   <input
                       id="email"
                       name="email"
                       type="email"
                       required
                       autofocus
                       value="{{ old('email', $request->email) }}"
                       class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                       placeholder="your@email.com"
                   >
                   @error('email')
                       <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Password Input -->
               <div>
                   <label for="password" class="block text-sm font-medium text-slate-300 mb-1">New Password</label>
                   <div class="relative">
                       <input
                           id="password"
                           name="password"
                           :type="showPassword ? 'text' : 'password'"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                           placeholder="••••••••"
                       >
                       <button 
                           type="button" 
                           class="absolute right-3 top-3 text-slate-400 hover:text-slate-300"
                           @click="togglePassword('password')"
                       >
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path x-show="!showPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                               <path x-show="!showPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                               <path x-show="showPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                           </svg>
                       </button>
                   </div>
                   <p class="mt-1 text-xs text-slate-400">Minimum 8 characters</p>
                   @error('password')
                       <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Confirm Password Input -->
               <div>
                   <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1">Confirm New Password</label>
                   <div class="relative">
                       <input
                           id="password_confirmation"
                           name="password_confirmation"
                           :type="showConfirmPassword ? 'text' : 'password'"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 rounded-lg bg-slate-700 border border-slate-600 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors"
                           placeholder="••••••••"
                       >
                       <button 
                           type="button" 
                           class="absolute right-3 top-3 text-slate-400 hover:text-slate-300"
                           @click="togglePassword('confirm')"
                       >
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path x-show="!showConfirmPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                               <path x-show="!showConfirmPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                               <path x-show="showConfirmPassword" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                           </svg>
                       </button>
                   </div>
                   @error('password_confirmation')
                       <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                   @enderror
               </div>

               <!-- Submit Button -->
               <button
                   type="submit"
                   :disabled="isSubmitting"
                   class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-800  disabled:opacity-50 disabled:cursor-not-allowed"
               >
               <span x-show="!isSubmitting">Reset Password</span>
               <span x-show="isSubmitting">Reseting...</span>
               </button>

               <!-- Back to Login Link -->
               <div class="text-center mt-4">
                   <p class="text-sm text-slate-400">
                       Remembered your password? 
                       <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">
                           Sign in
                       </a>
                   </p>
               </div>
           </form>
       </div>
   </div>
</div>
</x-guest-layout>
