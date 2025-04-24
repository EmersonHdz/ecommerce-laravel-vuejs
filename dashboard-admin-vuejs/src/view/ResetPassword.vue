

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'
import GuestLayout from '../components/GuestLayout.vue'
const route = useRoute()
const router = useRouter()

const token = ref(route.params.token)
const email = ref(route.query.email)
const newPassword = ref('')
const confirmPassword = ref('')
const loading = ref(false)

const resetPassword = async () => {
  if (newPassword.value !== confirmPassword.value) {
    alert('Passwords do not match!')
    return
  }

  loading.value = true
  try {
    await axios.post('http://127.0.0.1:8000/api/reset-password', {
      token: token.value, // Laravel espera el token en el body
      email: email.value,
      password: newPassword.value,
      password_confirmation: confirmPassword.value
    })

    alert('Password reset successful! Redirecting to login.')
    router.push({ name: 'login' }) 
  } catch (error) {
    console.error('Error:', error.response?.data || error.message)
    alert(error.response?.data?.message || 'Password reset failed.')
  } finally {
    loading.value = false
  }
}

</script>

<template>
  <GuestLayout title="Set New Password">
    <form class="space-y-6" @submit.prevent="resetPassword">
      <div class="round-md shadow-sm">
        <div>
        <label for="new_password" class="block text-medium font-medium text-gray-900">New Password</label>
        <input id="new_password" v-model="newPassword" type="password" required placeholder="Password"
          class="block w-full px-3 py-2 border rounded" />
      </div>

      <div>
        <label for="password_repeat" class="block text-medium font-medium text-gray-900">Repeat Password</label>
        <input id="password_repeat" v-model="confirmPassword" type="password" required placeholder="Repeat Password"
          class="block w-full px-3 py-2 border rounded" />
      </div>

      <button type="submit" :disabled="loading"
        class="w-full py-2 px-4 bg-indigo-600 text-white rounded disabled:bg-gray-400">
        {{ loading ? 'Processing...' : 'Submit' }}
      </button>

      </div>
    </form>
  </GuestLayout>
</template>

