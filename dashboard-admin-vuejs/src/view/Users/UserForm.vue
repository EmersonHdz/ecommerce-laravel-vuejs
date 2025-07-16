<script setup>
import store from "../../store/index.js";
import { onMounted, ref } from 'vue'
import Spinner from "../../components/core/Spinner.vue";
import { useRoute, useRouter } from "vue-router";
import axiosClient from "../../lib/axios";
import CustomInput from "../../components/core/CustomInput.vue";

const route = useRoute()
const router = useRouter()

const user = ref({
  id: null,
  name: '',
  last_name: '',
  email: '',
  phone: '',
  avatar: null,
  password: ''
})

const errors = ref({});

const loading = ref(false)
const avatarPreview = ref(null)

onMounted(() => {
  if (route.params.id) {
    loading.value = true
    store.dispatch('getUser', route.params.id)
      .then((response) => {
        loading.value = false;
        user.value = response.data
        if (user.value.avatar) {
          avatarPreview.value = user.value.avatar
        }
      })
  }
})

function handleAvatarChange(e) {
  const file = e.target.files[0];
  if (file) {
    user.value.avatar = file;
    avatarPreview.value = URL.createObjectURL(file);
  }
}

async function onSubmit(close = false) {
  loading.value = true
  errors.value = {};
  
  try {
    let response;
    if(user.value.id) {
      response = await store.dispatch('updateUser', user.value)
      store.commit('showToast', 'User was successfully updated');
    } else {
      response = await store.dispatch('createUser', user.value)
      store.commit('showToast', 'User was successfully created');
    }
    
    store.dispatch('getUsers')
    if(close) {
      router.push({name: 'app.users'})
    } else if (response.status === 201) {
      router.push({name: 'app.users.edit', params: {id: response.data.id}})
    }
  } catch (err) {
    if (err.response?.data?.errors) {
      errors.value = err.response.data.errors
    } else {
      store.commit('showToast', { 
        message: 'An error occurred: ' + (err.message || 'Unknown error'), 
        type: 'error' 
      });
    }
  } finally {
    loading.value = false;
  }
}
</script>

<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 v-if="!loading" class="text-2xl md:text-3xl font-bold text-gray-900">
          {{ user.id ? `Edit User: ${user.name}` : 'Create New User' }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
          {{ user.id ? 'Update user information' : 'Fill in the details to create a new user' }}
        </p>
      </div>
    </div>


    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden animate-fade-in-down">
      <Spinner v-if="loading"
        class="absolute left-0 top-0 bg-white bg-opacity-70 right-0 bottom-0 flex items-center justify-center z-50"
      />

      <form v-if="!loading" @submit.prevent="onSubmit(false)" class="divide-y divide-gray-200">
        <!-- Form Sections -->
        <div class="px-6 py-5 space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
              <CustomInput
                v-model="user.name"
                label="First Name"
                placeholder="Enter first name"
                :errors="errors.name || []" 
                required
              />
  

              <CustomInput
                v-model="user.email"
                type="email"
                label="Email Address"
                placeholder="Enter email address"
                :errors="errors.email"
                required
              />

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                <div class="flex items-center space-x-4">
                  <div class="relative">
                    <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-300">
                      <img v-if="avatarPreview" :src="avatarPreview" class="h-full w-full object-cover">
                      <div v-else class="h-full w-full flex items-center justify-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                    </div>
                    <input
                      type="file"
                      @change="handleAvatarChange"
                      accept="image/*"
                      class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                    >
                  </div>
                  <div>
                    <button
                      type="button"
                      @click="avatarPreview = null; user.avatar = null"
                      class="text-sm text-red-600 hover:text-red-800"
                      v-if="avatarPreview"
                    >
                      Remove photo
                    </button>
                    <p class="text-xs text-gray-500 mt-1">JPG, GIF or PNG. Max size 2MB</p>
                  </div>
                </div>
                <p v-if="errors['avatar']" class="mt-1 text-sm text-red-600">{{ errors['avatar'][0] }}</p>
              </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
              <CustomInput
                v-model="user.last_name"
                label="Last Name"
                placeholder="Enter last name"
                :errors="errors.last_name || []"
              />

              <CustomInput
                v-model="user.phone"
                type="tel"
                label="Phone Number"
                placeholder="Enter phone number"
                :error="errors.phone?.[0]"
              />

              <CustomInput
                v-model="user.password"
                type="password"
                :label="user.id ? 'New Password (leave blank to keep current)' : 'Password'"
                placeholder="Enter password"
                :errors="errors.password"
                :required="!user.id"
              />
              
            </div>
          </div>
        </div>

        <!-- Form Footer -->
        <div class="px-6 py-4 bg-gray-50 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
          <router-link
            :to="{name: 'app.users'}"
            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
          >
            Cancel
          </router-link>
          
          <button
            type="button"
            @click="onSubmit(true)"
            class="mb-3 sm:mb-0 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
          >
            Save & Close
          </button>
          
          <button
            type="submit"
            class="mb-3 sm:mb-0 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
          >
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</template>