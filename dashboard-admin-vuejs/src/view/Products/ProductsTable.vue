<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
      
      <!-- Displaying options to control the number of items per page and search functionality -->
      <div class="flex flex-col md:flex-row md:justify-between m-2">

        <!-- Option to select the number of items per page and information about total products -->
        <div class="flex items-center mb-2 md:mb-0">
          <span class="text-sm mr-5">Per Page</span>
          <select @change="getProducts(null)" v-model="perPage"
                  class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
             <option v-for="option in [5, 10, 20, 50, 100]" :value="option">{{ option }}</option>
          </select>
          <span class="text-sm ml-3">Found {{ products.total }} products</span>
        </div>
        
         <!-- Input field for searching products -->
        <div class="flex items-center">
          <input v-model="search" @change="getProducts(null)"
                 class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                 placeholder="Type to Search products">
        </div>
      </div>
     
      <div class="overflow-auto rounded-lg shadow-lg">
        <table class="table-auto w-full">
         <!-- show product information header-->
        <thead class="bg-gray-200 border-b-2 border-gray-300 text-center">
        <tr>
          <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortProducts('id')">
            No Id
          </TableHeaderCell>
          <TableHeaderCell field="image" :sort-field="sortField" :sort-direction="sortDirection">
            Image
          </TableHeaderCell>
          <TableHeaderCell field="title" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortProducts('title')">
            Title
          </TableHeaderCell>
          <TableHeaderCell field="price" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortProducts('price')">
            Price
          </TableHeaderCell>
          <TableHeaderCell field="quantity" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortProducts('quantity')">
            Quantity
          </TableHeaderCell>
          <TableHeaderCell field="updated_at" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortProducts('updated_at')">
            Last Updated At
          </TableHeaderCell>
          <TableHeaderCell field="actions">
            Actions
          </TableHeaderCell>
        </tr>
        </thead>
        <!-- show espiner while loading the products -->
        <tbody v-if="products.loading || !products.data.length">
        <tr>
          <td colspan="6">
            <Spinner v-if="products.loading"/>
            <p v-else class="text-center py-8 text-gray-700">
              There are no products
            </p>
          </td>
        </tr>
        </tbody>

         <!-- show products in row -->
        <tbody v-else>
        <tr v-for="(product, index) of products.data">
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap text-center">{{ product.id }}</td>
          <td class="border-b p-2 ">
            <img v-if="product.image_url" class="w-16 h-16 object-cover" :src="product.image_url" :alt="product.title">
            <img v-else class="w-16 h-16 object-cover" src="../../assets/noimage.png">
          </td>
          <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
            {{ product.title }}
          </td>
         
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap text-center">
            {{ $filters.currencyGBP(product.price)}}
          </td>
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap text-center">
            {{ product.quantity }}
          </td>
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
            {{ formatDate(product.updated_at) }}
          </td>
    
              <!-- Menu to actions-->
             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center space-x-2">
                <RouterLink
                  :to="{name: 'app.products.edit', params: {id: product.id}}"
                  class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-md hover:bg-indigo-50"
                  title="Edit"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </RouterLink>

                <button
                  @click="deleteProduct(product)"
                  class="text-red-600 hover:text-red-900 p-1.5 rounded-md hover:bg-red-50"
                  title="Delete"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
        </tr>
        </tbody>

        </table>
      </div>
  
      <!-- Displaying pagination and data information -->
      <div v-if="!products.loading" class="flex justify-between items-center mt-5">
         <!-- Show the range of items being displayed -->
        <div v-if="products.data.length">
          Showing from {{ products.from }} to {{ products.to }}
        </div>

          <!-- Pagination navigation -->
        <nav
          v-if="products.total > products.limit"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination">

          <!-- Pagination buttons -->
         <button
          v-for="(link, i) of products.links"
          :key="i"
          :disabled="!link.url"
          @click="getForPage($event, link)"
          aria-current="page"
          :class="{
          'relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap rounded-lg': true,
          'z-10 bg-indigo-500 border-indigo-500 text-white': link.active,
          'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
          'bg-gray-200 text-gray-900 font-bold ': !link.url
          }"
          v-html="link.label">
            
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
  import Swal from 'sweetalert2'
 
  
  const perPage = ref(PRODUCTS_PER_PAGE);
  const search = ref('');
  const products = computed(() => store.state.products);
  const sortField = ref('updated_at');
  const sortDirection = ref('desc')
  
  const product = ref({})
  
  onMounted(() => {
    getProducts();
  })
  
  function getForPage(ev, link) {
    ev.preventDefault();
    if (!link.url || link.active) {
      return;
    }
  
    getProducts(link.url)
  }
  
  function getProducts(url = null) {
    store.dispatch("getProducts", {
      url,
      search: search.value,
      per_page: perPage.value,
      sort_field: sortField.value,
      sort_direction: sortDirection.value
    });
  }
  
  function sortProducts(field) {
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
  
    getProducts()
  }
  
  function deleteProduct(product) {
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
        store.dispatch('deleteProduct', product.id)
          .then(() => {
            store.commit('showToast', 'Product was successfully deleted');
            store.dispatch('getProducts')
          })
      }
    })
 
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
  </script>
  
  <style scoped>
  
  </style>
  