<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow">
      <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
          <router-link to="/" class="text-2xl font-bold text-blue-600">
            LMS Portal
          </router-link>

          <div class="flex items-center gap-6">
            <router-link to="/" class="hover:text-blue-600">Home</router-link>
            <router-link to="/courses" class="hover:text-blue-600">Courses</router-link>

            <template v-if="authStore.isAuthenticated">
              <router-link to="/my-courses" class="hover:text-blue-600">
                My Courses
              </router-link>
              <router-link to="/profile" class="hover:text-blue-600">
                Profile
              </router-link>
              <button
                @click="handleLogout"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
              >
                Logout
              </button>
            </template>

            <template v-else>
              <router-link
                to="/login"
                class="px-4 py-2 border border-blue-600 text-blue-600 rounded hover:bg-blue-50"
              >
                Login
              </router-link>
              <router-link
                to="/register"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
              >
                Sign Up
              </router-link>
            </template>
          </div>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
      <div class="container mx-auto px-4 text-center">
        <p>&copy; 2024 Learning Management System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { usePortalAuthStore } from '@/stores/portalAuth'

const router = useRouter()
const authStore = usePortalAuthStore()

async function handleLogout() {
  await authStore.logout()
  router.push('/')
}
</script>
