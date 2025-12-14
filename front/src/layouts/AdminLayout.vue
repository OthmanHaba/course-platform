<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white">
      <div class="p-4">
        <h2 class="text-2xl font-bold">Admin Panel</h2>
      </div>

      <nav class="mt-8">
        <router-link
          to="/admin/dashboard"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Dashboard
        </router-link>
        <router-link
          to="/admin/courses"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Courses
        </router-link>
        <router-link
          to="/admin/users"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Users
        </router-link>
        <router-link
          to="/admin/enrollments"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Enrollments
        </router-link>
        <router-link
          to="/admin/reviews"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Reviews
        </router-link>
        <router-link
          to="/admin/categories"
          class="block px-4 py-3 hover:bg-gray-700"
          active-class="bg-gray-700"
        >
          Categories
        </router-link>
      </nav>

      <div class="absolute bottom-0 w-64 p-4">
        <button
          @click="handleLogout"
          class="w-full px-4 py-2 bg-red-600 rounded hover:bg-red-700"
        >
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const router = useRouter()
const authStore = useAdminAuthStore()

function handleLogout() {
  authStore.logout()
  router.push('/admin/login')
}
</script>
