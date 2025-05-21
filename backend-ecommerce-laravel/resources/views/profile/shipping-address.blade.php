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
                shippingAddress: {{ json_encode([
                    'address1' => old('shipping.address1', $shippingAddress->address1),
                    'address2' => old('shipping.address2', $shippingAddress->address2),
                    'city' => old('shipping.city', $shippingAddress->city),
                    'state' => old('shipping.state', $shippingAddress->state),
                    'country_code' => old('shipping.country_code', $shippingAddress->country_code),
                    'zipcode' => old('shipping.zipcode', $shippingAddress->zipcode),
                ]) }},
                isLoading: false,
                get shippingCountryStates() {
                    const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
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
            action="{{ route('shippingAddress.update') }}" 
            method="post"
            @submit.prevent="submitForm">
        @csrf
        @method('patch')

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Shipping Address</h2>
            <p class="text-gray-500 dark:text-gray-300 mt-2">Update your delivery information</p>
        </div>

        <!-- Address Line 1 -->
        <div class="space-y-2">
            <label for="shipping_address1" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Address 1 *</label>
            <input id="shipping_address1" 
                   type="text" 
                   name="shipping[address1]" 
                   x-model="shippingAddress.address1" 
                   placeholder="123 Main St" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('shipping_address1')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Address Line 2 -->
        <div class="space-y-2">
            <label for="shipping_address2" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Shipping Address 2</label>
            <input id="shipping_address2" 
                   type="text" 
                   name="shipping[address2]" 
                   x-model="shippingAddress.address2" 
                   placeholder="Apt, suite, etc." 
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('shipping_address2')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- City -->
        <div class="space-y-2">
            <label for="shipping_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City *</label>
            <input type="text" 
                   id="shipping_city" 
                   name="shipping[city]" 
                   x-model="shippingAddress.city" 
                   placeholder="New York" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('shipping_city')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Postal Code -->
        <div class="space-y-2">
            <label for="shipping_zipcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Postal Code *</label>
            <input type="text" 
                   id="shipping_zipcode"  
                   name="shipping[zipcode]" 
                   x-model="shippingAddress.zipcode"  
                   placeholder="10001" 
                   required
                   class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition duration-200">
            <x-input-error :messages="$errors->get('shipping_zipcode')" class="mt-1 text-sm text-red-600" />
        </div>

        <!-- Country and State -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Country Select -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country *</label>
                <div class="relative">
                    <select name="shipping[country_code]" 
                            x-model="shippingAddress.country_code" 
                            required
                            class="w-full px-4 py-3 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none transition duration-200">
                        <option value="">Select Country</option>
                        <template x-for="country of countries" :key="country.code">
                            <option :selected="country.code === shippingAddress.country_code" 
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
                <template x-if="shippingCountryStates">
                    <div class="relative">
                        <select name="shipping[state]" 
                                x-model="shippingAddress.state" 
                                required
                                class="w-full px-4 py-3 pr-8 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none transition duration-200">
                            <option value="">Select State</option>
                            <template x-for="[code, state] of Object.entries(shippingCountryStates)" :key="code">
                                <option :selected="code === shippingAddress.state" 
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

                <template x-if="!shippingCountryStates"> 
                    <input type="text" 
                           name="shipping[state]" 
                           x-model="shippingAddress.state" 
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
                <span x-show="!isLoading">Update Shipping Address</span>
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