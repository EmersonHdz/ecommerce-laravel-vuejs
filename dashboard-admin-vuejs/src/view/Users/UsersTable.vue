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
            @change="getUsers()"
            class="block w-20 pl-3 pr-8 py-2 text-sm border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm"
          >
            <option v-for="option in [5, 10, 20, 50, 100]" :value="option">{{ option }}</option>
          </select>
        </div>
        <span class="text-sm text-gray-500">{{ users.total }} users found</span>
      </div>
           <!-- Input field for searching products -->
      <div class="relative w-full sm:w-64">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input
          v-model="search"
          @input="getUsers()"
          type="text"
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          placeholder="Search users..."
          debounce="300"
        />
      </div>
    </div>

    <!-- Users Table -->
    <div class="overflow-auto rounded-lg shadow-lg">
      <table class="table-auto w-full">

        <thead class="bg-gray-200 border-b-2 border-gray-300 text-center">
          <tr>
            <TableHeaderCell field="id" :sort-field="sortField" :sort-direction="sortDirection" @click="sortUsers('id')">
            Name
          </TableHeaderCell>

            <TableHeaderCell field="name" :sort-field="sortField" :sort-direction="sortDirection" @click="sortUsers('name')">
                Last Name
            </TableHeaderCell>
          

            <TableHeaderCell field="email" :sort-field="sortField" :sort-direction="sortDirection" @click="sortUsers('email')">
            Email
          </TableHeaderCell>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Phone
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" @click="sortUsers('created_at')">
              <div class="flex items-center">
                Created
                <SortIndicator :field="sortField" current-field="created_at" :direction="sortDirection" />
              </div>
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="users.loading">
            <td colspan="6" class="px-6 py-8 text-center">
              <Spinner class="mx-auto" />
            </td>
          </tr>

          <tr v-else-if="!users.data || !users.data.length">
            <td colspan="6" class="px-6 py-8 text-center">
              <div class="flex flex-col items-center justify-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <p class="mt-2 text-sm font-medium">No users found</p>
                <button v-if="search" @click="search = ''; getUsers()" class="mt-2 text-sm text-indigo-600 hover:text-indigo-500">
                  Clear search
                </button>
              </div>
            </td>
          </tr>

          <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors duration-150">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-medium">
                  {{ user.name.charAt(0) }}
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ user.name }}
                  </div>
                </div>
              </div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.last_name }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ user.email }}</div>
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.phone || '-' }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>

            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex items-center justify-end space-x-2">
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

    <!-- Pagination -->
    <div v-if="!users.loading && users.data && users.data.length" class="bg-white px-6 py-3 flex items-center justify-between border-t border-gray-200">
      <div class="flex-1 flex justify-between items-center sm:hidden">
        <button
          @click="getForPage($event, users.prev_page_url ? { url: users.prev_page_url } : null)"
          :disabled="!users.prev_page_url"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !users.prev_page_url }"
        >
          Previous
        </button>
        <span class="text-sm text-gray-700">
          Page {{ users.current_page }} of {{ users.last_page }}
        </span>
        <button
          @click="getForPage($event, users.next_page_url ? { url: users.next_page_url } : null)"
          :disabled="!users.next_page_url"
          class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          :class="{ 'opacity-50 cursor-not-allowed': !users.next_page_url }"
        >
          Next
        </button>
      </div>

      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
          <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ users.from }}</span> to <span class="font-medium">{{ users.to }}</span> of <span class="font-medium">{{ users.total }}</span> users
          </p>
        </div>

        <div v-if="users.last_page > 1">
          <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <a
              v-for="(link, index) in users.links"
              :key="index"
              href="#"
              @click.prevent="getForPage($event, link)"
              :aria-current="link.active ? 'page' : undefined"
              class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
              :class="{
                'bg-indigo-50 border-indigo-500 text-indigo-600 z-10': link.active,
                'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active,
                'rounded-l-md': index === 0,
                'rounded-r-md': index === users.links.length - 1,
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