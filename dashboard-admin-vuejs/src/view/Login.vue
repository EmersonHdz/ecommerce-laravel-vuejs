<template>
  <!-- Guest layout with title -->
  <GuestLayout title="Admin Login - Dashboard">
    <!-- Sign-in form -->
    <form class="mt-8 space-y-6" method="POST" @submit.prevent="login">
      <!-- Error alert component -->
      <error-alert :error-msg="errorMsg" @dismiss="errorMsg = ''" />

      <!-- Hidden input for remember -->
      <input type="hidden" name="remember" value="true"/>

      <!-- Email and password fields -->
      <div class="rounded-md shadow-sm -space-y-px">
        <div class="mb-3">
          <label for="email-address" class="sr-only">Email address</label>
          <input id="email-address" v-model="user.email" name="email" type="email" autocomplete="email" required placeholder="Email address"
          class="appearance-none relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"/>
        </div>
        <div>
          <label for="password" class="sr-only">Password</label>
          <input id="password" v-model="user.password" name="password" type="password" autocomplete="current-password" placeholder="Password" required
                 class="appearance-none relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                 />
        </div>
      </div>

      <!-- Remember me checkbox and forgot password link -->
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember-me" name="remember-me" v-model="user.remember" type="checkbox"
                 class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-900 rounded"/>
          <label for="remember-me" class="ml-2 block text-sm text-gray-900"> Remember me </label>
        </div>

        <div class="text-sm">
          <router-link :to="{name: 'requestPassword'}"  class="font-medium text-indigo-600 hover:text-indigo-500"> Forgot
            your password?
          </router-link>
        </div>
      </div>

      <!-- Sign in button -->
      <div>
        <button type="submit"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :class="{
                  'cursor-not-allowed': loading,
                  'hover:bg-indigo-500': loading,
                }">
           <!-- Loading spinner -->
           <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <!-- Lock icon -->
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <!-- Lock icon component -->
              <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true"/>  
            </span>
          Sign in
        </button>
      </div>
    </form>
  </GuestLayout>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { LockClosedIcon } from '@heroicons/vue/solid'
import GuestLayout from '../components/GuestLayout.vue'
import store from '../store'
import ErrorAlert from '../components/core/ErrorAlert.vue'

export default {
  components: {
    GuestLayout,
    ErrorAlert
  },
  setup() {
    const router = useRouter()

    // Error message and loading indicator
    const errorMsg = ref('')
    const loading = ref(false)

    // User object with email, password, and remember properties
    const user = {
      email: '',
      password: '',
      remember: false
    }

    // Function to handle login
    const login = () => {
      loading.value = true
      store.dispatch('login', user)
        .then(() => {
          loading.value = false
          router.push({ name: 'app.dashboard' })
        })
        .catch(({ response }) => {
          loading.value = false
          errorMsg.value = response.data.message
        })
    }

    // Function to dismiss error message
    const dismissError = () => {
      errorMsg.value = ''
    }

    return {
      errorMsg,
      loading,
      user,
      login,
      dismissError
    }
  }
}
</script>
