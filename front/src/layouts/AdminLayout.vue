<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-60 bg-white border-r border-gray-200 flex flex-col">
      <div class="px-6 py-5 border-b border-gray-200">
        <h2 class="text-xl font-semibold tracking-tight text-gray-900">Admin</h2>
      </div>

      <nav class="flex-1 py-4 overflow-y-auto">
        <router-link
          to="/admin/dashboard"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
          </svg>
          Dashboard
        </router-link>
        <router-link
          to="/admin/courses"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
          Courses
        </router-link>
        <router-link
          to="/admin/users"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          Users
        </router-link>
        <router-link
          to="/admin/enrollments"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
          Enrollments
        </router-link>
        <router-link
          to="/admin/reviews"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
          </svg>
          Reviews
        </router-link>
        <router-link
          to="/admin/categories"
          class="flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 border-l-2 border-transparent hover:text-gray-900 hover:bg-gray-50 transition-colors"
          active-class="!text-indigo-600 !bg-indigo-50 !border-indigo-600"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
          </svg>
          Categories
        </router-link>
      </nav>

      <div class="p-4 border-t border-gray-200">
        <button
          @click="handleLogout"
          class="w-full px-4 py-2.5 text-sm font-medium text-gray-600 bg-transparent border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 hover:border-gray-400 transition-colors"
        >
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8">
      <router-view />
    </main>
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
