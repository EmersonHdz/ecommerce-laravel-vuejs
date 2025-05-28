<x-app-layout>
    <div x-data="{
        flashMessage: '{{ \Illuminate\Support\Facades\Session::get('flash_message') }}',
        init() {
            if (this.flashMessage) {
                setTimeout(() => this.$dispatch('notify', { message: this.flashMessage }), 200)
            }
        }
    }" class="container mx-auto lg:w-2/3 p-5">

        @if (session('error'))
            <div class="py-2 px-3 bg-red-500 text-white mb-2 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
                <form 
                    action="{{ route('profile.billing.update') }}"
                    method="POST"
                    x-data="{
                        countries: {{ json_encode($countries) }},
                        billingAddress: {{ json_encode([
                            'address1' => old('billing.address1', $billingAddress->address1),
                            'address2' => old('billing.address2', $billingAddress->address2),
                            'city' => old('billing.city', $billingAddress->city),
                            'state' => old('billing.state', $billingAddress->state),
                            'country_code' => old('billing.country_code', $billingAddress->country_code),
                            'zipcode' => old('billing.zipcode', $billingAddress->zipcode),
                        ]) }},
                        shippingAddress: {{ json_encode([
                            'address1' => old('shipping.address1', $shippingAddress->address1),
                            'address2' => old('shipping.address2', $shippingAddress->address2),
                            'city' => old('shipping.city', $shippingAddress->city),
                            'state' => old('shipping.state', $shippingAddress->state),
                            'country_code' => old('shipping.country_code', $shippingAddress->country_code),
                            'zipcode' => old('shipping.zipcode', $shippingAddress->zipcode),
                        ]) }},
                        get billingCountryStates() {
                            const country = this.countries.find(c => c.code === this.billingAddress.country_code)
                            return country?.states ? JSON.parse(country.states) : null;
                        },
                        get shippingCountryStates() {
                            const country = this.countries.find(c => c.code === this.shippingAddress.country_code)
                            return country?.states ? JSON.parse(country.states) : null;
                        }
                    }"
                >
                    @csrf
                    @method('PATCH')
                    <h2 class="text-xl mt-6 font-semibold mb-2">Billing Address</h2>
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
                            <select name="billing[country_code]" x-model="billingAddress.country_code" class="w-full border-gray-300 rounded">
                                <option value="">Select Country</option>
                                <template x-for="country in countries" :key="country.code">
                                    <option :value="country.code" x-text="country.name"  :selected="country.code === billingAddress.country_code"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <template x-if="billingCountryStates">
                                <select name="billing[state]" x-model="billingAddress.state" class="w-full border-gray-300 rounded">
                                    <option value="">Select State</option>
                                    <template x-for="[code, state] in Object.entries(billingCountryStates)" :key="code">
                                        <option :value="code" x-text="state"  :selected="code === billingAddress.state"></option>
                                    </template>
                                </select>
                            </template>
                            <template x-if="!billingCountryStates">
                                <x-input name="billing[state]" x-model="billingAddress.state" placeholder="State" />
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold">Shipping Address</h2>
                        <label class="text-gray-700">
                            <input type="checkbox" @change="$event.target.checked ? shippingAddress = { ...billingAddress } : ''" class="mr-2">
                            Same as Billing
                        </label>
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
                            <select name="shipping[country_code]" x-model="shippingAddress.country_code" class="w-full border-gray-300 rounded">
                                <option value="">Select Country</option>
                                <template x-for="country in countries" :key="country.code">
                                    <option :value="country.code" x-text="country.name"  :selected="country.code === shippingAddress.country_code"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <template x-if="shippingCountryStates">
                                <select name="shipping[state]" x-model="shippingAddress.state" class="w-full border-gray-300 rounded">
                                    <option value="">Select State</option>
                                    <template x-for="[code, state] in Object.entries(shippingCountryStates)" :key="code">
                                        <option :value="code" x-text="state" :selected="code === shippingAddress.state "></option>
                                    </template>
                                </select>
                            </template>
                            <template x-if="!shippingCountryStates">
                                <x-input name="shipping[state]" x-model="shippingAddress.state" placeholder="State" />
                            </template>
                        </div>
                    </div>

                    <button class="w-full mt-4">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
