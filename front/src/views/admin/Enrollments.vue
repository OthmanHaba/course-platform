<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Enrollments</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">Loading...</div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled On</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="enrollment in enrollments" :key="enrollment.id">
              <td class="px-6 py-4">
                {{ enrollment.first_name }} {{ enrollment.last_name }}
              </td>
              <td class="px-6 py-4">{{ enrollment.course_title }}</td>
              <td class="px-6 py-4">{{ Math.round(enrollment.progress_percentage) }}%</td>
              <td class="px-6 py-4">{{ formatDate(enrollment.enrollment_date) }}</td>
              <td class="px-6 py-4">
                <button @click="deleteEnrollment(enrollment.id)" class="text-red-600 hover:underline">
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>
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
