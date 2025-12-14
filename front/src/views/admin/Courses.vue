<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Courses</h1>
      <router-link
        to="/admin/courses/create"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Create Course
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">Loading...</div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrollments</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="course in courses" :key="course.id">
              <td class="px-6 py-4">{{ course.title }}</td>
              <td class="px-6 py-4">
                <span
                  :class="{
                    'px-2 py-1 rounded text-sm': true,
                    'bg-green-100 text-green-800': course.status === 'published',
                    'bg-yellow-100 text-yellow-800': course.status === 'draft',
                    'bg-gray-100 text-gray-800': course.status === 'archived'
                  }"
                >
                  {{ course.status }}
                </span>
              </td>
              <td class="px-6 py-4">{{ course.enrollment_count }}</td>
              <td class="px-6 py-4">{{ course.rating_average || 'N/A' }}</td>
              <td class="px-6 py-4">
                <router-link
                  :to="`/admin/courses/${course.id}/edit`"
                  class="text-blue-600 hover:underline mr-3"
                >
                  Edit
                </router-link>
                <button
                  @click="deleteCourse(course.id)"
                  class="text-red-600 hover:underline"
                >
                  Delete
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

const courses = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchCourses()
})

async function fetchCourses() {
  try {
    const response = await adminService.getCourses()
    courses.value = response.data.data.courses
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  } finally {
    loading.value = false
  }
}

async function deleteCourse(id: number) {
  if (!confirm('Are you sure you want to delete this course?')) return

  try {
    await adminService.deleteCourse(id)
    await fetchCourses()
  } catch (error) {
    console.error('Failed to delete course:', error)
    alert('Failed to delete course')
  }
}
</script>
