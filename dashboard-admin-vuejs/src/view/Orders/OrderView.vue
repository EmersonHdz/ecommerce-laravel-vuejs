<template>
  <div v-if="order" class="container mx-auto px-4 py-6">
    <!-- Header con título y acciones -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Order #{{ order.id }}</h1>
      <div class="flex space-x-3">
        <button @click="printOrder" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-gray-700 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Print
        </button>
        <button @click="sendEmail" class="px-4 py-2 bg-blue-100 hover:bg-blue-200 rounded-md text-blue-700 flex items-center">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          Send Email
        </button>
      </div>
    </div>

    <!-- Resumen rápido del pedido -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-blue-50 p-4 rounded-lg">
          <p class="text-sm text-blue-600 font-medium">Order Date</p>
          <p class="text-lg font-semibold">{{ formatDate(order.created_at) }}</p> 
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
          <p class="text-sm text-green-600 font-medium">Total Amount</p>
          <p class="text-lg font-semibold">{{ $filters.currencyUSD(order.total_price) }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
          <p class="text-sm text-purple-600 font-medium">Payment Method</p>
          <p class="text-lg font-semibold">{{ order.payment_method || 'Credit Card' }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
          <p class="text-sm text-yellow-600 font-medium">Status</p>
          <select v-model="order.status" @change="onStatusChange" class="w-full mt-1 bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option v-for="status of orderStatuses" :value="status">{{ status }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- main section 2 col -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- customer informacion left -->
      <div class="lg:col-span-1 space-y-6">
        <!-- card information customer -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Customer Information</h2>
            <button @click="editCustomer" class="text-blue-600 hover:text-blue-800">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
          </div>
          <div class="space-y-3">
            <div class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <div>
                <p class="text-sm text-gray-500">Customer</p>
                <p class="font-medium">{{ order.customer.first_name }} {{ order.customer.last_name }}</p>
              </div>
            </div>
            <div class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ order.customer.email }}</p>
              </div>
            </div>
            <div class="flex items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <div>
                <p class="text-sm text-gray-500">Phone</p>
                <p class="font-medium">{{ order.customer.phone }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- address card -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Addresses</h2>
          <div class="space-y-4">
            <div>
              <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Billing Address
              </h3>
              <div class="text-sm text-gray-600 space-y-1">
                <p>{{ order.customer.billingAddress.address1 }}</p>
                <p v-if="order.customer.billingAddress.address2">{{ order.customer.billingAddress.address2 }}</p>
                <p>{{ order.customer.billingAddress.city }}, {{ order.customer.billingAddress.state }} {{ order.customer.billingAddress.zipcode }}</p>
                <p>{{ order.customer.billingAddress.country }}</p>
              </div>
            </div>
            <div>
              <h3 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Shipping Address
              </h3>
              <div class="text-sm text-gray-600 space-y-1">
                <p>{{ order.customer.shippingAddress.address1 }}</p>
                <p v-if="order.customer.shippingAddress.address2">{{ order.customer.shippingAddress.address2 }}</p>
                <p>{{ order.customer.shippingAddress.city }}, {{ order.customer.shippingAddress.state }} {{ order.customer.shippingAddress.zipcode }}</p>
                <p>{{ order.customer.shippingAddress.country }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--col right product status -->
      <div class="lg:col-span-2 space-y-6">
        <!-- product card -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Items ({{ order.items.length }})</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in order.items" :key="item.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md overflow-hidden">
                        <img v-if="item.product.image" :src="item.product.image" class="h-full w-full object-cover">
                        <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                        </div>
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ item.product.title }}</div>
                        <div class="text-sm text-gray-500">SKU: {{ item.product.sku || 'N/A' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $filters.currencyUSD(item.unit_price) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ item.quantity }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $filters.currencyUSD(item.unit_price * item.quantity) }}
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-gray-50">
                <tr>
                  <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal</td>
                  <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $filters.currencyUSD(order.total_price) }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Shipping</td>
                  <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $filters.currencyUSD(order.shipping_cost || 0) }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tax</td>
                  <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $filters.currencyUSD(order.tax_amount || 0) }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Total</td>
                  <td class="px-6 py-3 text-sm font-bold text-gray-900">{{ $filters.currencyUSD(order.total_price + (order.shipping_cost || 0) + (order.tax_amount || 0)) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- historial status -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Status Timeline</h2>
          <OrderStatus :order="order" />
        </div>

        <!-- note card and accions -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Notes</h2>
          <div class="space-y-4">
            <div v-for="note in order.notes" :key="note.id" class="border-l-4 border-blue-500 pl-4 py-2">
              <div class="flex justify-between items-start">
                <p class="text-sm text-gray-600">{{ note.content }}</p>
                  <span class="text-xs text-gray-400">{{ formatDate(note.created_at) }}</span> 
              </div>
              <p class="text-xs text-gray-500 mt-1">Added by {{ note.author }}</p>
            </div>
            <div class="mt-4">
              <textarea v-model="newNote" placeholder="Add a note about this order..." class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
              <button @click="addNote" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Add Note
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import {useRoute} from "vue-router";
import axiosClient from "../../lib/axios";
import OrderStatus from "./OrderStatus.vue";

const route = useRoute()

const order = ref();
const orderStatuses = ref([]);

onMounted(() => {
  store.dispatch('getOrder', route.params.id)
    .then(({data}) => {
      order.value = data
    })

  axiosClient.get(`/orders/statuses`)
    .then(({data}) => orderStatuses.value = data)
})

function onStatusChange() {
  axiosClient.post(`/orders/change-status/${order.value.id}/${order.value.status}`)
    .then(({data}) => {
      store.commit('showToast', `Order status was successfully changed into "${order.value.status}"`)
    })

}

function formatDate(dateString) {
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit', 
    minute: '2-digit' 
  };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

</script>

<style scoped>

</style>