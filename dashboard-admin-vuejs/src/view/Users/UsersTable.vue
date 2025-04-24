<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">

      <!-- Displaying options to control the number of items per page and search functionality -->
    <div class="flex flex-col md:flex-row md:justify-between m-2 p-5">

       <!-- Option to select the number of items per page and information about total products -->
      <div class="flex items-center mb-2 md:mb-0">
        <span class="text-sm mr-3">Per Page</span>
        <select @change="getUsers(null)" v-model="perPage"
                class="appearance-none border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
        <span class="text-sm ml-3">Found {{users.total}} users</span>
      </div>

      <div class="flex items-center">
        <input v-model="search" @change="getUsers(null)" class="appearance-none block w-full md:w-48 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Type to Search users">
      </div>

    </div>

    <div class="overflow-auto rounded-lg shadow">
    <table class="w-full">
      <thead class="bg-gray-300">
      <tr>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Name</th>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Last Name</th>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Email</th>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Phone</th>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Create date</th>
        <th class="w-20 p-3 text-sm font-bold tracking-wide text-left" >Actions</th>      
      </tr>
      </thead>
      
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

      <tbody v-else>
      <tr v-for="(user, index) of users.data" :key="index">

        <td class="p-3 text-base text-gray-700 whitespace-nowrap">{{ user.name }}</td>
        <td class="p-3 text-base text-gray-700 whitespace-nowrap">{{ user.last_name }}</td>
        <td class="p-3 text-base text-gray-700 whitespace-nowrap">{{ user.email }}</td>
        <td class="p-3 text-base text-gray-700 whitespace-nowrap ">{{ user.phone }}</td>
        <td class="p-3 text-base text-gray-700 whitespace-nowrap">{{ user.created_at }}</td>
        <td class="p-3 text-base text-gray-700 whitespace-nowrap  text-center">
      
           <div class="px-1 py-1">
             <RouterLink  :to="{name: 'app.users.edit', params: {id: user.id}}" class="py-2 px-3 font-medium text-indigo-600 hover:text-indigo-500 duration-150 hover:bg-gray-50 rounded-lg">
                Edit
             </RouterLink>

              <button   @click="deleteUser(user)" class="py-2 leading-none px-3 font-medium text-red-600 hover:text-red-500 duration-150 hover:bg-gray-50 rounded-lg">
                Delete
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
import {computed, onMounted, ref} from "vue";
import store from "../../store";
import Spinner from "../../components/core/Spinner.vue";
import {USERS_PER_PAGE} from "../../constants";
import TableHeaderCell from "../../components/core/TableHeaderCell.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'
import { RouterLink } from "vue-router";
import Swal from 'sweetalert2'


const perPage = ref(USERS_PER_PAGE);
const search = ref('');
const users = computed(() => store.state.users);
const sortField = ref('updated_at');
const sortDirection = ref('desc')

const user = ref({})


onMounted(() => {
  getUsers();
})

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  getUsers(link.url)
}

function getUsers(url = null) {
  store.dispatch("getUsers", {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value
  });
}

function sortUsers(field) {
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

  getUsers()
}



function deleteUser(user) {
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      store.dispatch('deleteUser', user.id)
        .then(() => {
          Swal.fire(
            "Deleted!",
            "The user has been deleted.",
            "success"
          );
          store.dispatch('getUsers');
        });
    }
  });
}


</script>

<style scoped>

</style>