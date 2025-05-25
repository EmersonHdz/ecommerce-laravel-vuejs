<div x-data="{
        flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
            }
        }
    }">
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl">
        <form x-data="{ isLoading: false }" 
              action="{{ route('profile.store') }}" 
              method="post"
              @submit.prevent="isLoading = true; $el.submit();">
            @csrf

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Profile Details</h2>
                <p class="text-gray-500 dark:text-gray-300 mt-2">Update your personal information</p>
            </div>

            <!-- First Name -->
            <div class="space-y-2 mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name *</label>
                <input id="first_name" 
                       type="text" 
                       name="first_name" 
                       value="{{ old('first_name', $customer->first_name) }}" 
                       placeholder="Enter your first name" 
                       required
                       autocomplete="given-name"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                <x-input-error :messages="$errors->get('first_name')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Last Name -->
            <div class="space-y-2 mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name *</label>
                <input id="last_name" 
                       type="text" 
                       name="last_name" 
                       value="{{ old('last_name', $customer->last_name) }}" 
                       placeholder="Enter your last name" 
                       required
                       autocomplete="family-name"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                <x-input-error :messages="$errors->get('last_name')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Email -->
            <div class="space-y-2 mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       placeholder="Enter your email address" 
                       required
                       autocomplete="email"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Phone -->
            <div class="space-y-2 mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone *</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <input id="phone" 
                           type="tel" 
                           name="phone" 
                           value="{{ old('phone', $customer->phone) }}" 
                           placeholder="Enter your phone number" 
                           required
                           autocomplete="tel"
                           class="w-full pl-10 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                </div>
                <x-input-error :messages="$errors->get('phone')" class="mt-1 text-sm text-red-600" />
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    :disabled="isLoading"
                    class="w-full flex justify-center items-center px-6 py-3.5 text-base font-medium text-white focus:ring-emerald-500 focus:border-emerald-500 focus:ring-4  rounded-lg transition duration-200 dark:bg-emerald-700 dark:hover:bg-emerald-800 disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!isLoading">Update Profile</span>
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
</div>