<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
        <!-- Header with filters and search -->
    <div class="flex flex-col md:flex-row md:justify-between m-2">
      <div class="flex items-center mb-2 md:mb-0">
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
        <span class="ml-3">Found {{ orders.total }} orders</span>
      </div>
      <!-- Input field for searching products -->
      <div>
        <input v-model="search" @change="getOrders(null)"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Type to Search orders">
      </div>
    </div>

      <!-- Orders Table -->
    <div class="overflow-auto rounded-lg shadow-lg" >  
    <table class="table-auto w-full">
      <thead class="bg-gray-200 border-b-2 border-gray-300 text-center">
      <tr>
        <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortOrders('id')">
          ID
        </TableHeaderCell>
        <TableHeaderCell :sort-field="sortField" :sort-direction="sortDirection">
          Customer
        </TableHeaderCell>
        <TableHeaderCell field="status" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortOrders('status')">
          Status
        </TableHeaderCell>
        <TableHeaderCell field="total_price" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortOrders('total_price')">
          Price
        </TableHeaderCell>
        <TableHeaderCell field="created_at" :sort-field="sortField" :sort-direction="sortDirection"
                         @click="sortOrders('created_at')">
          Date
        </TableHeaderCell>
        <TableHeaderCell field="actions">
          Actions
        </TableHeaderCell>
      </tr>
      </thead>


      
          <!-- show espiner while loading the products -->
        <tbody v-if="orders.loading || !orders.data.length">
        <tr>
          <td colspan="6">
            <Spinner v-if="orders.loading"/>
            <p v-else class="text-center py-8 text-gray-700">
              There are no users
            </p>
          </td>
        </tr>
        </tbody>
      
        <!-- show orders in row -->
      <tbody v-else>
      <tr v-for="(order, index) of orders.data">
        <td class="border-b p-2 ">{{ order.id }}</td>
        <td class="border-b p-2 ">
          {{ order.customer.first_name }} {{ order.customer.last_name }}
        </td>
        <td class="border-b p-2 ">
          <OrderStatus :order="order" />
        </td>
        <td class="border-b p-2">
          {{ $filters.currencyGBP(order.total_price) }}
        </td>
        <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
          {{ formatDate(order.created_at) }}
        </td>
      

          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center  space-x-2">
                  <RouterLink :to="{name: 'app.orders.view', params: {id: order.id}}"
                  class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-md hover:bg-indigo-50"
                  title="Edit"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </RouterLink>

                 <!--     <button
                  @click="deleteOrder(order)"
                  class="text-red-600 hover:text-red-900 p-1.5 rounded-md hover:bg-red-50"
                  title="Delete"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>-->
              </div>
            </td>
      </tr>
      </tbody>
    </table>
    </div> 
    <div v-if="!orders.loading" class="flex justify-between items-center mt-5">
      <div v-if="orders.data.length">
        Showing from {{ orders.from }} to {{ orders.to }}
      </div>
      <nav
        v-if="orders.total > orders.limit"
        class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
        aria-label="Pagination"
      >
        <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
        <button
          v-for="(link, i) of orders.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md' : '',
              i === orders.links.length - 1 ? 'rounded-r-md' : '',
              !link.url ? ' bg-gray-100 text-gray-700': ''
            ]"
          v-html="link.label"
        >
        </button>
      </nav>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
 import {PRODUCTS_PER_PAGE} from "../../constants";
  import TableHeaderCell from "../../components/core/TableHeaderCell.vue"
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import OrderStatus from "./OrderStatus.vue";
import Swal from "sweetalert2";

const perPage = ref(PRODUCTS_PER_PAGE);
const search = ref('');
const orders = computed(() => store.state.orders);
const sortField = ref('updated_at');
const sortDirection = ref('desc')


onMounted(() => {
  getOrders();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getOrders(link.url)
}



function formatDate(dateString) {
  const options = { 
    day: 'numeric',
    month: 'short', 
    year: 'numeric', 
    hour: '2-digit', 
    minute: '2-digit' 
  };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function getOrders(url = null) {
  store.dispatch("getOrders", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortOrders(field) {
  if (field === sortField.value) {
    if (sortDirection.value === 'desc') {
      sortDirection.value = 'asc'
    } else {
      sortDirection.value = 'desc'
    }
  } else {
    sortField.value = field;
    sortDirection.value = 'asc'
  }

  getOrders()
}


  function deleteOrder(order) {
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        store.dispatch('deleteOrder', order.id)
          .then(() => {
            store.commit('showToast', 'Product was successfully deleted');
            store.dispatch('getOrders')
          })
      }
    })
 
  }
  
  

</script>

<style scoped>

</style>