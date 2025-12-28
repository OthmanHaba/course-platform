<template>
  <div class="max-w-7xl">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Enrollments</h1>
    </header>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <p class="text-gray-500">Loading enrollments...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Enrolled On</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="enrollment in enrollments" :key="enrollment.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-medium text-gray-900">
                {{ enrollment.first_name }} {{ enrollment.last_name }}
              </td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ enrollment.course_title }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">
                  {{ Math.round(enrollment.progress_percentage) }}%
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ formatDate(enrollment.enrollment_date) }}</td>
              <td class="px-5 py-4">
                <button @click="deleteEnrollment(enrollment.id)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="enrollments.length === 0" class="p-8 text-center">
          <p class="text-gray-500">No enrollments found</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const enrollments = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchEnrollments()
})

async function fetchEnrollments() {
  try {
    const response = await adminService.getEnrollments()
    enrollments.value = response.data.data.enrollments
  } catch (error) {
    console.error('Failed to fetch enrollments:', error)
  } finally {
    loading.value = false
  }
}

async function deleteEnrollment(id: number) {
  if (!confirm('Are you sure you want to remove this enrollment?')) return

  try {
    await adminService.deleteEnrollment(id)
    await fetchEnrollments()
  } catch (error) {
    console.error('Failed to delete enrollment:', error)
    alert('Failed to remove enrollment')
  }
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString()
}
</script>
