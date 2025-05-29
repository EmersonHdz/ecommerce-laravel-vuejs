<div 
    x-data="{
        flashMessage: '{{ \Illuminate\Support\Facades\Session::get('flash_message') }}',
        shippingAddress: {{ json_encode([
            'address1' => old('shipping.address1', $shippingAddress->address1),
            'address2' => old('shipping.address2', $shippingAddress->address2),
            'city' => old('shipping.city', $shippingAddress->city),
            'state' => old('shipping.state', $shippingAddress->state),
            'country_code' => old('shipping.country_code', $shippingAddress->country_code),
            'zipcode' => old('shipping.zipcode', $shippingAddress->zipcode),
        ]) }},
        billingAddress: {{ json_encode([
            'address1' => old('billing.address1', $billingAddress->address1),
            'address2' => old('billing.address2', $billingAddress->address2),
            'city' => old('billing.city', $billingAddress->city),
            'state' => old('billing.state', $billingAddress->state),
            'country_code' => old('billing.country_code', $billingAddress->country_code),
            'zipcode' => old('billing.zipcode', $billingAddress->zipcode),
        ]) }},
        countries: {{ json_encode($countries) }},
        isSubmitting: false,

        get billingCountryStates() {
            const country = this.countries.find(c => c.code === this.billingAddress.country_code)
            return country?.states ? JSON.parse(country.states) : null;
        },
        get shippingCountryStates() {
            const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
            return country?.states ? JSON.parse(country.states) : null;
        },
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', { message: this.flashMessage }), 200)
            }
        },
        async submitForm() {
            this.isSubmitting = true;
            try {
                await $nextTick();
                this.$refs.form.submit();
            } catch (e) {
                this.isSubmitting = false;
                console.error('Form submission failed:', e);
            }
        }
    }"
    class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg transition-all duration-300 hover:shadow-2xl"
>
 
    @if (session('error'))
        <div class="py-2 px-3 bg-red-500 text-white mb-2 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form 
        x-ref="form"
        class="space-y-6"
        action="{{ route('profile.billing.update') }}"
        method="POST"
        @submit.prevent="submitForm"
    >
        @csrf
        @method('PATCH')

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Shipping Address</h2>
            <p class="text-gray-500 dark:text-gray-300 mt-2">Update your shipping information</p>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-input name="shipping[address1]" x-model="shippingAddress.address1" placeholder="Address 1" />
            <x-input name="shipping[address2]" x-model="shippingAddress.address2" placeholder="Address 2" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-input name="shipping[city]" x-model="shippingAddress.city" placeholder="City" />
            <x-input name="shipping[zipcode]" x-model="shippingAddress.zipcode" placeholder="Zip Code" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <select name="shipping[country_code]" x-model="shippingAddress.country_code" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                    <option value="">Select Country</option>
                   <template x-for="country of countries" :key="country.code">
                            <option :selected="country.code === shippingAddress.country_code" 
                                    :value="country.code" 
                                    x-text="country.name"></option>
                        </template>
                </select>
            </div>
            <div>
                <template x-if="shippingCountryStates">
                    <select name="shipping[state]" x-model="shippingAddress.state" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                        <option value="">Select State</option>
                        <template x-for="[code, state] in Object.entries(shippingCountryStates)" :key="code">
                            <option :value="code" x-text="state"></option>
                        </template>
                    </select>
                </template>
                <template x-if="!shippingCountryStates">
                    <x-input name="shipping[state]" x-model="shippingAddress.state" placeholder="State" />
                </template>
            </div>
        </div>

        <div class="flex justify-between mt-6 mb-2">
            <h2 class="text-xl font-semibold text-white">Billing Address</h2>
            <label class="text-gray-200">
                <input type="checkbox" @change="$event.target.checked ? billingAddress = { ...shippingAddress } : ''" class="mr-2">
                Same as Billing
            </label>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-input name="billing[address1]" x-model="billingAddress.address1" placeholder="Address 1" />
            <x-input name="billing[address2]" x-model="billingAddress.address2" placeholder="Address 2" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <x-input name="billing[city]" x-model="billingAddress.city" placeholder="City" />
            <x-input name="billing[zipcode]" x-model="billingAddress.zipcode" placeholder="Zip Code" />
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
                <select name="billing[country_code]" x-model="billingAddress.country_code" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
               <template x-for="country of countries" :key="country.code">
                            <option :selected="country.code === billingAddress.country_code" 
                                    :value="country.code" 
                                    x-text="country.name"></option>
                        </template>
                </select>
            </div>
            <div>
                <template x-if="billingCountryStates">
                    <select name="billing[state]" x-model="billingAddress.state" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white transition duration-200">
                        <option value="">Select State</option>
                        <template x-for="[code, state] in Object.entries(billingCountryStates)" :key="code">
                            <option :value="code" x-text="state"></option>
                        </template>
                    </select>
                </template>
                <template x-if="!billingCountryStates">
                    <x-input name="billing[state]" x-model="billingAddress.state" placeholder="State" />
                </template>
            </div>
        </div>

        <button 
            type="submit"
            :disabled="isSubmitting"
            x-bind:class="{ 'opacity-50 cursor-not-allowed': isSubmitting }"
            class="w-full flex justify-center items-center px-6 py-3.5 text-base font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition duration-300 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-800"
        >
            <span x-show="!isSubmitting" x-cloak>
                Update Shipping and Billing Address
            </span>
            <span x-show="isSubmitting" x-cloak class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                Processing...
            </span>
        </button>
    </form>
</div>

