<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold text-center mb-6">Create Account</h2>

      <form @submit.prevent="handleRegister">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">
            First Name
          </label>
          <input
            v-model="formData.first_name"
            type="text"
            id="first_name"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">
            Last Name
          </label>
          <input
            v-model="formData.last_name"
            type="text"
            id="last_name"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Email
          </label>
          <input
            v-model="formData.email"
            type="email"
            id="email"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div class="mb-6">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Password
          </label>
          <input
            v-model="formData.password"
            type="password"
            id="password"
            required
            minlength="6"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <div v-if="error" class="mb-4 text-red-600 text-sm">
          {{ error }}
        </div>

        <div v-if="success" class="mb-4 text-green-600 text-sm">
          {{ success }}
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 disabled:opacity-50 mb-4"
        >
          {{ loading ? 'Creating Account...' : 'Register' }}
        </button>

        <div class="text-center">
          <router-link to="/login" class="text-blue-600 hover:underline text-sm">
            Already have an account? Login
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePortalAuthStore } from '@/stores/portalAuth'

const router = useRouter()
const authStore = usePortalAuthStore()

const formData = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: ''
})

const error = ref('')
const success = ref('')
const loading = ref(false)

async function handleRegister() {
  loading.value = true
  error.value = ''
  success.value = ''

  const result = await authStore.register(formData.value)

  if (result.success) {
    success.value = 'Registration successful! Redirecting to login...'
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } else {
    error.value = result.error || 'Registration failed'
  }

  loading.value = false
}
</script>
