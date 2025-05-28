<div x-data="{
        flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
            }
        }
    }
    "   class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl">
    
    <form x-data="{ isLoading: false }" 
          action="{{ route('profile.billing.update') }}" 
          method="POST"
          @submit.prevent="isLoading = true; $el.submit();">
        @csrf
        @method('patch')

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Update Password</h2>
            <p class="text-gray-500 dark:text-gray-300 mt-2">Secure your account with a new password</p>
        </div>

        <!-- Current Password -->
        <div class="space-y-2 mb-4">
            <label for="old_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password *</label>
             <div x-data="passwordField" class="relative">
             <input id="old_password" name="old_password"  :type="inputType()" 
                    placeholder="Enter your current password" 
                    required
                    autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200 pr-10">

            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer hover:text-gray-600 dark:hover:text-gray-300"
                 @click="toggle">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
        </div>
            <x-input-error :messages="$errors->get('old_password')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- New Password -->
        <div class="space-y-2 mb-4">
            <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password *</label>
            <div class="relative" x-data="passwordField">
                <input id="new_password" 
                       name="new_password" 
                       :type="inputType()"
                       placeholder="Enter your new password" 
                       required
                       autocomplete="new-password"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200 pr-10">
                <div @click="toggle"
                 class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer hover:text-gray-600 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="const input = $el.previousElementSibling; input.type = input.type === 'password' ? 'text' : 'password'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('new_password')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Confirm New Password -->
        <div class="space-y-2 mb-6">
            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password *</label>
            <div  x-data="passwordField" class="relative">
                <input id="new_password_confirmation" 
                       name="new_password_confirmation" 
                       :type="inputType()" 
                       placeholder="Repeat your new password" 
                       required
                       autocomplete="new-password"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200 pr-10">
                <div @click="toggle" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 cursor-pointer hover:text-gray-600 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" @click="const input = $el.previousElementSibling; input.type = input.type === 'password' ? 'text' : 'password'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
            <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-1 text-sm text-red-600" />
        </div>



        <!-- Submit Button -->
        <button type="submit" 
                :disabled="isLoading"
                class="w-full flex justify-center items-center px-6 py-3.5 text-base font-medium text-white focus:ring-emerald-500 focus:border-emerald-500 focus:ring-4 rounded-lg transition duration-200 dark:bg-emerald-700 dark:hover:bg-emerald-800 disabled:opacity-50 disabled:cursor-not-allowed ">
            <span x-show="!isLoading">Update Password</span>
            <span x-show="isLoading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            </span>
            
        </button>
    </form>
</div>