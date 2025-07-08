


<template>
    <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
      
      <!-- Displaying options to control the number of items per page and search functionality -->
      <div class="flex flex-col md:flex-row md:justify-between m-2">

        <!-- Option to select the number of items per page and information about total products -->
        <div class="flex items-center mb-2 md:mb-0">
          <span class="text-sm mr-5">Per Page</span>
          <select @change="getUsers(null)" v-model="perPage"
                  class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
             <option v-for="option in [5, 10, 20, 50, 100]" :value="option">{{ option }}</option>
          </select>
          <span class="text-sm ml-3">Found {{ users.total }} products</span>
        </div>
        
         <!-- Input field for searching users -->
        <div class="flex items-center">
          <input v-model="search" @change="getUsers(null)"
                 class="appearance-none relative block w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                 placeholder="Type to search user">
        </div>
      </div>
     
      <div class="overflow-auto rounded-lg shadow-lg">
        <table class="table-auto w-full">
         <!-- show product information header-->
        <thead class="bg-gray-200 border-b-2 border-gray-300 text-center">
        <tr>
          <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection"
                          @click="sortUsers('id')">
            No Id
          </TableHeaderCell>
          <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection">
            name
          </TableHeaderCell>
          <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortUsers('email')">
            Email
          </TableHeaderCell>
          <TableHeaderCell field="phone" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortUsers('phone')">
            phone
          </TableHeaderCell>

          <TableHeaderCell field="updated_at" :sort-field="sortField" :sort-direction="sortDirection"
                           @click="sortUsers('updated_at')">
            Last Updated At
          </TableHeaderCell>
          <TableHeaderCell field="actions">
            Actions
          </TableHeaderCell>
        </tr>
        </thead>
        <!-- show espiner while loading the products -->
        <tbody v-if="users.loading || !users.data.length">
        <tr>
          <td colspan="6">
            <Spinner v-if="users.loading"/>
            <p v-else class="text-center py-8 text-gray-700">
              There are no users
            </p>
          </td>
        </tr>
        </tbody>

         <!-- show products in row -->
        <tbody v-else>
        <tr v-for="user in users.data" :key="user.id">
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap text">{{ user.id }}</td>
          <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">
           {{ user.name }}
          </td>
         
          <td class="p-3 text-sm text-gray-700 whitespace-nowrap">
            {{ user.email}}
          </td>
          <td class="p-3 text-sm  text-gray-700 whitespace-nowrap">
            {{ user.phone }}
          </td>
          <td class="p-3 text-sm  text-gray-700 whitespace-nowrap">
            {{ formatDate(user.created_at) }}
          </td>
       <!-- Menu to actions-->
             <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center  space-x-2">
                <RouterLink
                  :to="{name: 'app.users.edit', params: {id: user.id}}"
                  class="text-indigo-600 hover:text-indigo-900 p-1.5 rounded-md hover:bg-indigo-50"
                  title="Edit"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </RouterLink>

                <button
                  @click="deleteUser(user)"
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
      <div v-if="!users.loading" class="flex justify-between items-center mt-5">
         <!-- Show the range of items being displayed -->
        <div v-if="users.data.length">
          Showing from {{ users.from }} to {{ users.to }}
        </div>

          <!-- Pagination navigation -->
        <nav
          v-if="users.total > users.limit"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination">

          <!-- Pagination buttons -->
         <button
          v-for="(link, i) of users.links"
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
import { computed, onMounted, ref } from 'vue';
import store from '../../store';
import Spinner from '../../components/core/Spinner.vue';
import { USERS_PER_PAGE } from '../../constants';
import SortIndicator from '../../components/core/SortIndicator.vue';
import Swal from 'sweetalert2';
  import TableHeaderCell from "../../components/core/TableHeaderCell.vue"


const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const users = computed(() => store.state.users);
const sortField = ref('updated_at');
const sortDirection = ref('desc');

onMounted(() => {
  getUsers();
});

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }
  getUsers(link.url);
}

function formatDate(dateString) {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function getUsers(url = null) {
  store.dispatch('getUsers', {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortUsers(field) {
  if (field === sortField.value) {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
  } else {
    sortField.value = field;
    sortDirection.value = 'desc';
  }
  getUsers();
}

function deleteUser(user) {
  Swal.fire({
    title: 'Delete User?',
    text: `Are you sure you want to delete ${user.name} ${user.last_name}? This action cannot be undone.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      store.dispatch('deleteUser', user.id)
        .then(() => {
          Swal.fire(
            'Deleted!',
            'The user has been deleted.',
            'success'
          );
          getUsers();
        })
        .catch(error => {
          Swal.fire(
            'Error!',
            'There was an error deleting the user.',
            'error'
          );
        });
    }
  });
}
</script>