<div x-data="{
        flashMessage: '{{\Illuminate\Support\Facades\Session::get('flash_message')}}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', {message: this.flashMessage}), 200)
            }
        }
    }" 
    class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl">
    
    <form class="space-y-6" x-data="{
                countries: {{ json_encode($countries) }},
                billingAddress: {{ json_encode([
                    'address1' => old('billing.address1', $billingAddress->address1),
                    'address2' => old('billing.address2', $billingAddress->address2),
                    'city' => old('billing.city', $billingAddress->city),
                    'state' => old('billing.state', $billingAddress->state),
                    'country_code' => old('billing.country_code', $billingAddress->country_code),
                    'zipcode' => old('billing.zipcode', $billingAddress->zipcode),
                ]) }},
                isLoading: false,
                get billingCountryStates() {
                    const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                    if (country && country.states) {
                        return JSON.parse(country.states);
                    }
                    return null;
                },
                async submitForm() {
                    this.isLoading = true;
                    try {
                        await $nextTick();
                        this.$el.submit();
                    } finally {
                        this.isLoading = false;
                    }
                }
            }" 
            action="{{ route('profile.billing.update') }}" 
            method="post"
            @submit.prevent="submitForm">
        @csrf
        @method('patch')

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Billing Address</h2>
            <p class="text-gray-500 dark:text-gray-300 mt-2">Update your billing information</p>
        </div>

        <!-- Address Line 1 -->
        <div class="space-y-2">
            <label for="billing_address1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Address 1 *</label>
            <input id="billing_address1" 
                   type="text" 
                   name="billing[address1]" 
                   x-model="billingAddress.address1" 
                   placeholder="123 Main St" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('billing_address1')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Address Line 2 -->
        <div class="space-y-2">
            <label for="billing_address2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Address 2</label>
            <input id="billing_address2" 
                   type="text" 
                   name="billing[address2]" 
                   x-model="billingAddress.address2" 
                   placeholder="Apt, suite, etc." 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('billing_address2')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- City -->
        <div class="space-y-2">
            <label for="billing_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City *</label>
            <input type="text" 
                   id="billing_city" 
                   name="billing[city]" 
                   x-model="billingAddress.city" 
                   placeholder="New York" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('billing_city')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Postal Code -->
        <div class="space-y-2">
            <label for="billing_zipcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code *</label>
            <input type="text" 
                   id="billing_zipcode"  
                   name="billing[zipcode]" 
                   x-model="billingAddress.zipcode"  
                   placeholder="10001" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('billing_zipcode')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Country and State -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Country Select -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country *</label>
                <div class="relative">
                    <select name="billing[country_code]" 
                            x-model="billingAddress.country_code" 
                            required
                            class="w-full px-4 py-3 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none transition duration-200">
                        <option value="">Select Country</option>
                        <template x-for="country of countries" :key="country.code">
                            <option :selected="country.code === billingAddress.country_code" 
                                    :value="country.code" 
                                    x-text="country.name"></option>
                        </template>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- State Select/Input -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State/Province *</label>
                <template x-if="billingCountryStates">
                    <div class="relative">
                        <select name="billing[state]" 
                                x-model="billingAddress.state" 
                                required
                                class="w-full px-4 py-3 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none transition duration-200">
                            <option value="">Select State</option>
                            <template x-for="[code, state] of Object.entries(billingCountryStates)" :key="code">
                                <option :selected="code === billingAddress.state" 
                                        :value="code" 
                                        x-text="state"></option>
                            </template>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                </template>

                <template x-if="!billingCountryStates"> 
                    <input type="text" 
                           name="billing[state]" 
                           x-model="billingAddress.state" 
                           placeholder="State/Province" 
                           required
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
                </template>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit" 
                    :disabled="isLoading"
                    class="w-full flex justify-center items-center px-6 py-3.5 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 rounded-lg transition duration-200 dark:bg-blue-700 dark:hover:bg-blue-800">
                <span x-show="!isLoading">Update Billing Address</span>
                <span x-show="isLoading" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </span>
            </button>
        </div>
    </form>
</div>