<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <div v-if="loading" class="text-center py-8">Loading...</div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-600 text-sm font-medium">Total Students</h3>
        <p class="text-3xl font-bold mt-2">{{ analytics?.total_students || 0 }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-600 text-sm font-medium">Total Instructors</h3>
        <p class="text-3xl font-bold mt-2">{{ analytics?.total_instructors || 0 }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-600 text-sm font-medium">Published Courses</h3>
        <p class="text-3xl font-bold mt-2">{{ analytics?.published_courses || 0 }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-600 text-sm font-medium">Total Enrollments</h3>
        <p class="text-3xl font-bold mt-2">{{ analytics?.total_enrollments || 0 }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
        <p class="text-gray-600">No recent activity</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
        <div class="space-y-2">
          <router-link
            to="/admin/courses/create"
            class="block w-full text-left px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Create New Course
          </router-link>
          <router-link
            to="/admin/users"
            class="block w-full text-left px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
          >
            Manage Users
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const analytics = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await adminService.getOverview()
    analytics.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch analytics:', error)
  } finally {
    loading.value = false
  }
})
</script>
