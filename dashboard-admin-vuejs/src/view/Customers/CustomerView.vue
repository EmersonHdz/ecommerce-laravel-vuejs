<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
      <h1 v-if="!loading" class="text-3xl font-bold text-gray-900">
        {{ customer.id ? `Update Customer: "${customer.first_name}"` : 'Create New Customer' }}
      </h1>
    </div>
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-down">
      <Spinner v-if="loading"
              class="absolute left-0 top-0 bg-white bg-opacity-70 right-0 bottom-0 flex items-center justify-center z-50"/>
      
      <form v-if="!loading" @submit.prevent="onSubmit" class="divide-y divide-gray-200">
        <!-- Main Customer Info Section -->
        <div class="px-6 py-5 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <CustomInput 
              v-model="customer.first_name" 
              label="First Name" 
              :errors="errors.first_name"
              input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
            />
            <CustomInput 
              v-model="customer.last_name" 
              label="Last Name" 
              :errors="errors.last_name"
              input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
            />
      
          </div>

          
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <CustomInput 
              v-model="customer.email" 
              label="Email" 
              type="email"
              :errors="errors.email"
              input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
            />
                <CustomInput
              v-model="customer.password"
              label="Password"
              type="password"
              input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
              :errors="errors.password"/>

            <CustomInput 
              v-model="customer.phone" 
              label="Phone" 
              type="tel"
              :errors="errors.phone"
              input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
            />
          </div>
           <!-- Customer status -->
          <div class="flex items-center">
            <CustomInput 
              type="checkbox" 
              v-model="customer.status" 
              label="Active Customer" 
              :errors="errors.status"
              wrapper-class="flex items-center"
              input-class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              label-class="ml-2 block text-sm text-gray-700"
            />
          </div>
        </div>
        
        <!-- Address Sections -->
        <div class="px-6 py-5 bg-gray-50">
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Billing Address -->
            <div class="space-y-4">
              <div class="flex items-center">
                <h2 class="text-lg font-semibold text-gray-900">Billing Address</h2>
                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                  Primary
                </span>
              </div>
              
              <div class="grid grid-cols-1 gap-4">
                <CustomInput 
                  v-model="customer.billingAddress.address1" 
                  label="Address Line 1" 
                  :errors="errors['billingAddress.address1']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                <CustomInput 
                  v-model="customer.billingAddress.address2" 
                  label="Address Line 2" 
                  :errors="errors['billingAddress.address2']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <CustomInput 
                    v-model="customer.billingAddress.city" 
                    label="City" 
                    :errors="errors['billingAddress.city']"
                    input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                  />
                  <CustomInput 
                    v-model="customer.billingAddress.zipcode" 
                    label="Postal Code" 
                    :errors="errors['billingAddress.zipcode']"
                    input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                  />
                </div>
                
                <CustomInput 
                  type="select" 
                  :select-options="countries" 
                  v-model="customer.billingAddress.country_code"
                  label="Country" 
                  :errors="errors['billingAddress.country_code']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                
                <CustomInput 
                  v-if="billingCountry && !billingCountry.states" 
                  v-model="customer.billingAddress.state" 
                  label="State/Province" 
                  :errors="errors['billingAddress.state']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                <CustomInput 
                  v-else 
                  type="select" 
                  :select-options="billingStateOptions"
                  v-model="customer.billingAddress.state" 
                  label="State/Province" 
                  :errors="errors['billingAddress.state']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
              </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="space-y-4 ">
              <div class="flex items-center justify-between ">
                <h2 class="text-lg font-semibold text-gray-900">Shipping Address</h2>
                <button 
                  type="button" 
                  @click="copyBillingToShipping"
                  class="inline-flex items-center px-5 py-2 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  Same as Billing
                </button>
              </div>
              
              <div class="grid grid-cols-1 gap-4">
                <CustomInput 
                  v-model="customer.shippingAddress.address1" 
                  label="Address Line 1"
                  :errors="errors['shippingAddress.address1']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                <CustomInput 
                  v-model="customer.shippingAddress.address2" 
                  label="Address Line 2"
                  :errors="errors['shippingAddress.address2']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <CustomInput 
                    v-model="customer.shippingAddress.city" 
                    label="City"
                    :errors="errors['shippingAddress.city']"
                    input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                  />
                  <CustomInput 
                    v-model="customer.shippingAddress.zipcode" 
                    label="Postal Code"
                    :errors="errors['shippingAddress.zipcode']"
                    input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                  />
                </div>
                
                <CustomInput 
                  type="select" 
                  :select-options="countries" 
                  v-model="customer.shippingAddress.country_code"
                  label="Country"
                  :errors="errors['shippingAddress.country_code']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                
                <CustomInput 
                  v-if="shippingCountry && !shippingCountry.states" 
                  v-model="customer.shippingAddress.state" 
                  label="State/Province"
                  :errors="errors['shippingAddress.state']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
                <CustomInput 
                  v-else 
                  type="select" 
                  :select-options="shippingStateOptions"
                  v-model="customer.shippingAddress.state" 
                  label="State/Province"
                  :errors="errors['shippingAddress.state']"
                  input-class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-lg"
                />
              </div>
            </div>
          </div>
        </div>
        
        <!-- Form Footer -->
        <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
          <router-link 
            :to="{name: 'app.customers'}" 
            type="button"
            class="mt-3 w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm"
          >
            Cancel
          </router-link>
          <button 
            type="submit"
            @click="onSubmit(true)"
            class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm"
          >
            {{ customer.id ? 'Update Customer' : 'Create Customer' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {useRoute, useRouter} from "vue-router";
import CustomInput from "../../components/core/CustomInput.vue";
import Spinner from "../../components/core/Spinner.vue";

const router = useRouter();
const route = useRoute()
const loading = ref(false)
const title = ref('');

const errors = ref({
  first_name: [],
  last_name: [],
  email: [],
  phone: [],
  status: [],
  'billingAddress.address1': [],
  'billingAddress.address2': [],
  'billingAddress.city': [],
  'billingAddress.zipcode': [],
  'billingAddress.country_code': [],
  'billingAddress.state': [],
  'shippingAddress.address1': [],
  'shippingAddress.address2': [],
  'shippingAddress.city': [],
  'shippingAddress.zipcode': [],
  'shippingAddress.country_code': [],
  'shippingAddress.state': [],
});
const customer = ref({
  billingAddress: {},
  shippingAddress: {}
})

const countries = computed(() => store.state.countries.map(c => ({key: c.code, text: c.name})))
const billingCountry = computed(() => store.state.countries.find(c => c.code === customer.value.billingAddress.country_code))
const billingStateOptions = computed(() => {
  if (!billingCountry.value || !billingCountry.value.states) return [];

  return Object.entries(billingCountry.value.states).map(c => ({key: c[0], text: c[1]}))
})
const shippingCountry = computed(() => store.state.countries.find(c => c.code === customer.value.shippingAddress.country_code))
const shippingStateOptions = computed(() => {
  if (!shippingCountry.value || !shippingCountry.value.states) return [];

  return Object.entries(shippingCountry.value.states).map(c => ({key: c[0], text: c[1]}))
})

onMounted(() => {
  // call to store get countries
  if (!store.state.countries.length) {
    store.dispatch('getCountries');
  }

  if (route.params.id) {
    loading.value = true;
    store.dispatch('getCustomer', route.params.id).then(({ data }) => {
      loading.value = false;
      title.value = `Update customer: "${data.first_name} ${data.last_name}"`;
      customer.value = data;
    });
  }
});


function onSubmit(close = false) {
  loading.value = true
  errors.value = {};
  if (customer.value.id) {
    customer.value.status = !!customer.value.status
    store.dispatch('updateCustomer', customer.value)
      .then(response => {
        loading.value = false;
        if (response.status === 200) {
          store.commit('showToast', 'Customer has been successfully updated');
          store.dispatch('getCustomers')
          router.push({name: 'app.customers'})
        }
      })
      .catch(err => {
        errors.value = err.response.data.errors
      })
  } else {
    store.dispatch('createCustomer', customer.value)
      .then(response => {
        loading.value = false;
        if (response.status === 201) {
          // TODO show notification
           store.commit('showToast', 'Customer has been successfully created');
          store.dispatch('getCustomers')
          router.push({name: 'app.customers'})
        }
      })
      .catch(err => {
        loading.value = false;
        if (err.response?.data?.errors) {
          errors.value = err.response.data.errors
        } else {
          store.commit('showToast', 'An error occurred while creating the customer', 'error');
        }
      })
  }
}

 function copyBillingToShipping() {
  customer.value.shippingAddress = { ...customer.value.billingAddress };
  // Clear any errors related to shipping address
  errors.value['shippingAddress.address1'] = [];
  errors.value['shippingAddress.address2'] = [];
  errors.value['shippingAddress.city'] = [];
  errors.value['shippingAddress.zipcode'] = [];
  errors.value['shippingAddress.country_code'] = [];
  errors.value['shippingAddress.state'] = [];
 }
</script>