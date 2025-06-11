<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Header with filters and search -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div class="flex items-center space-x-4">
        <div class="flex items-center">
          <label for="perPage" class="text-sm font-medium text-gray-600 mr-2">Show:</label>
          <select
            id="perPage"
            v-model="perPage"
            @change="getOrders()"
            class="block w-20 pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm"
          >
            <option v-for="option in [5, 10, 20, 50, 100]" :value="option">{{ option }}</option>
          </select>
        </div>

        <div class="flex items-center">
          <label for="sortField" class="text-sm font-medium text-gray-600 mr-2">Sort by:</label>
          <select
            id="sortField"
            v-model="sortField"
            @change="sortOrders(sortField)"
            class="block w-40 pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm"
          >
            <option value="created_at">Order Date</option>
            <option value="total_price">Order Total</option>
            <option value="updated_at">Last Updated</option>
          </select>
          <button
            @click="sortOrders(sortField)"
            class="ml-2 p-2 text-gray-500 hover:text-indigo-600 focus:outline-none"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              viewBox="0 0 20 20"
              fill="currentColor"
            >
              <path
                fill-rule="evenodd"
                d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                clip-rule="evenodd"
              />
            </svg>
          </button>
        </div>
      </div>

      <div class="relative w-full sm:w-64">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 text-gray-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            />
          </svg>
        </div>
        <input
          v-model="search"
          @input="getOrders()"
          type="text"
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="Search orders..."
          debounce="300"
        />
      </div>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              @click="sortOrders('id')"
            >
              <div class="flex items-center">
                Order ID
                <SortIndicator :field="sortField" current-field="id" :direction="sortDirection" />
              </div>
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Customer
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Status
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              @click="sortOrders('created_at')"
            >
              <div class="flex items-center">
                Date
                <SortIndicator :field="sortField" current-field="created_at" :direction="sortDirection" />
              </div>
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer"
              @click="sortOrders('total_price')"
            >
              <div class="flex items-center">
                Total
                <SortIndicator :field="sortField" current-field="total_price" :direction="sortDirection" />
              </div>
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="orders.loading">
            <td colspan="6" class="px-6 py-8 text-center">
              <Spinner class="mx-auto" />
            </td>
          </tr>

          <tr v-else-if="!orders.data || !orders.data.length">
            <td colspan="6" class="px-6 py-8 text-center">
              <div class="flex flex-col items-center justify-center text-gray-400">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-12 w-12"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="1"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                  />
                </svg>
                <p class="mt-2 text-sm font-medium">No orders found</p>
                <button
                  v-if="search"
                  @click="search = ''; getOrders()"
                  class="mt-2 text-sm text-indigo-600 hover:text-indigo-500"
                >
                  Clear search
                </button>
              </div>
            </td>
          </tr>

          <tr
            v-for="order in orders.data"
            :key="order.id"
            class="hover:bg-gray-50 transition-colors duration-150"
          >
            <td class="px-6 py-4 whitespace-nowrap">
              <router-link
                :to="{ name: 'app.orders.view', params: { id: order.id } }"
                class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline"
              >
                #{{ order.id }}
              </router-link>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div
                  class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-medium"
                >
                  {{ order.customer.first_name.charAt(0) }}{{ order.customer.last_name.charAt(0) }}
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ order.customer.first_name }} {{ order.customer.last_name }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ order.customer.email }}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <OrderStatus :order="order" />
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(order.created_at) }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ $filters.currencyGBP(order.total_price) }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center justify-end space-x-2">
                <router-link
                  :to="{ name: 'app.orders.view', params: { id: order.id } }"
                  class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-md hover:bg-indigo-50"
                  title="View Details"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                    />
                  </svg>
                </router-link>
               <!-- print order -->
                <button
                  @click="printOrder(order)"
                  class="text-gray-500 hover:text-gray-700 p-1.5 rounded-md hover:bg-gray-100"
                  title="Print Order"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.5"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div
      v-if="!orders.loading && orders.data && orders.data.length"
      class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200"
    >
      <div class="flex-1 flex justify-between items-center sm:hidden">
        <button
          @click="getForPage($event, orders.prev_page_url ? { url: orders.prev_page_url } : null)"
          :disabled="!orders.prev_page_url"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !orders.prev_page_url }"
        >
          Previous
        </button>
        <span class="text-sm text-gray-700">
          Page {{ orders.current_page }} of {{ orders.last_page }}
        </span>
        <button
          @click="getForPage($event, orders.next_page_url ? { url: orders.next_page_url } : null)"
          :disabled="!orders.next_page_url"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !orders.next_page_url }"
        >
          Next
        </button>
      </div>

      <div v-if="!orders.loading"  class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div v-if="orders.data.length" >
          <p class="text-sm text-gray-700">
            Showing
            <span class="font-medium">{{ orders.from }}</span>
            to
            <span class="font-medium">{{ orders.to }}</span>
            of
            <span class="font-medium">{{ orders.total }}</span>
            results
          </p>
        </div>

        <div >
          <nav v-if="orders.total > orders.limit" class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a
              v-for="(link, index) in orders.links"
              :key="index"
              href="#"
               @click="getForPage($event, link)"
              :aria-current="link.active ? 'page' : undefined"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              :class="{
                'bg-indigo-50 border-indigo-500 text-indigo-600 z-10': link.active,
                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                'rounded-l-md': index === 0,
                'rounded-r-md': index === orders.links.length - 1,
                'pointer-events-none opacity-50': !link.url
              }"
              v-html="link.label"
            ></a>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import store from '../../store';
import Spinner from '../../components/core/Spinner.vue';
import { PRODUCTS_PER_PAGE } from '../../constants';
import OrderStatus from './OrderStatus.vue';
import SortIndicator from '../../components/core/SortIndicator.vue';

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const orders = computed(() => store.state.orders);
const sortField = ref('updated_at');
const sortDirection = ref('desc');



onMounted(() => {
  getOrders();
});

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }
  getOrders(link.url);
}

function sortOrders(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
  } else {
    sortField.value = field;
    sortDirection.value = 'desc';
  }
  getOrders();
}


function formatDate(dateString) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function printOrder(order) {
  // Implement print functionality
  console.log('Printing order:', order.id);
  // window.open(`/orders/${order.id}/print`, '_blank');
}

function getOrders(url = null) {
  store.dispatch('getOrders', {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}




</script>

<style scoped>
/* Custom styles if needed */
</style>