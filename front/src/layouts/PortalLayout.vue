<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center space-x-8">
            <router-link to="/" class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">LearnHub</span>
            </router-link>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
              <router-link to="/" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                Home
              </router-link>
              <router-link to="/courses" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                Courses
              </router-link>
              <template v-if="authStore.isAuthenticated">
                <router-link to="/my-courses" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                  My Learning
                </router-link>
              </template>
            </div>
          </div>

          <!-- Right Side Actions -->
          <div class="flex items-center space-x-4">
            <template v-if="authStore.isAuthenticated">
              <!-- User Menu -->
              <div class="relative">
                <button @click="showUserMenu = !showUserMenu" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                  <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-indigo-600">
                      {{ authStore.user?.first_name?.[0]  || 'U' }}
                    </span>
                  </div>
                </button>
                <!-- Dropdown Menu -->
                <div v-if="showUserMenu" @click="showUserMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200">
                  <router-link to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Profile
                  </router-link>
                  <router-link to="/my-courses" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    My Courses
                  </router-link>
                  <hr class="my-1 border-gray-200">
                  <button @click="handleLogout" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                    Logout
                  </button>
                </div>
              </div>
            </template>

            <template v-else>
              <router-link to="/login" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                Log in
              </router-link>
              <router-link to="/register" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Sign up
              </router-link>
            </template>

            <!-- Mobile Menu Button -->
            <button @click="showMobileMenu = !showMobileMenu" class="md:hidden p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="showMobileMenu" class="md:hidden border-t border-gray-200 py-2">
          <router-link to="/" @click="showMobileMenu = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
            Home
          </router-link>
          <router-link to="/courses" @click="showMobileMenu = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
            Courses
          </router-link>
          <template v-if="authStore.isAuthenticated">
            <router-link to="/my-courses" @click="showMobileMenu = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
              My Learning
            </router-link>
          </template>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Brand -->
          <div class="col-span-1">
            <div class="flex items-center space-x-2 mb-4">
              <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
              </div>
              <span class="text-xl font-bold text-white">LearnHub</span>
            </div>
            <p class="text-sm text-gray-400">Empowering learners worldwide with quality education.</p>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-white font-semibold mb-4">Learn</h3>
            <ul class="space-y-2 text-sm">
              <li><router-link to="/courses" class="hover:text-white transition-colors">All Courses</router-link></li>
              <li><router-link to="/" class="hover:text-white transition-colors">Categories</router-link></li>
            </ul>
          </div>

          <!-- Company -->
          <div>
            <h3 class="text-white font-semibold mb-4">Company</h3>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
            </ul>
          </div>

          <!-- Support -->
          <div>
            <h3 class="text-white font-semibold mb-4">Support</h3>
            <ul class="space-y-2 text-sm">
              <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
              <li><a href="/admin" class="hover:text-white transition-colors">Admin</a></li>
            </ul>
          </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-sm text-gray-400 text-center">
          <p>&copy; 2024 LearnHub. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePortalAuthStore } from '@/stores/portalAuth'

const router = useRouter()
const authStore = usePortalAuthStore()
const showUserMenu = ref(false)
const showMobileMenu = ref(false)

async function handleLogout() {
  await authStore.logout()
  showUserMenu.value = false
  router.push('/')
}
</script>
