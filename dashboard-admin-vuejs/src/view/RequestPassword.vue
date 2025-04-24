<script setup>
import { computed, ref,  } from "vue";
import GuestLayout from "../components/GuestLayout.vue";
import ErrorAlert from '../components/core/ErrorAlert.vue';
import store from "../store";
import axios from "../lib/axios";
import SuccessAlert from "../components/core/SuccessAlert.vue";


const email = ref("");

const loading = computed(() => store.state.resetPassword.loading);
const successMessage = computed(() => store.state.resetPassword.successMessage);
const errorMessage = computed(() => store.state.resetPassword.errorMessage);

const submitRequest = async () => {
  if (!email.value) return;
  await store.dispatch("requestPasswordReset", email.value);
  
};


</script>

<template>
  <div>
    <GuestLayout title="Request new password">
      <p class="text-gray-900 m-4">
        We will send the necessary information to create a new password.
      </p>

      <div class="bg-white shadow-md rounded-lg">
        <form class="mt-5 space-y-6" @submit.prevent="submitRequest">
        <!-- Componente de alerta -->
          <ErrorAlert v-if="errorMessage" :errorMsg="errorMessage" @dismiss="store.dispatch('clearError')" />
          
          <!-- Componente de alerta de éxito -->

        <SuccessAlert v-if="successMessage" :successMsg="successMessage" @dismiss="store.dispatch('clearSuccess')" />
   
          
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="email"
              type="email"
              required
              class="appearance-none block w-full px-3 py-2 border border-gray-600 placeholder-gray-800 text-gray-900 rounded focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
              placeholder="Email address"
            />
          </div>

          <div class="flex items-center justify-between">
            <router-link :to="{ name: 'login' }" class="text-indigo-600 hover:text-indigo-500">
              Remember your password?
            </router-link>
          </div>

          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :disabled="loading"
            >
              {{ loading ? "Sending..." : "Submit" }}
            </button>
          </div>
        </form>
      </div>
    </GuestLayout>
  </div>
</template>



