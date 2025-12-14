<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Courses</h1>

    <div v-if="loading" class="text-center py-8">Loading...</div>

    <div v-else-if="courses.length === 0" class="text-center py-8 text-gray-600">
      <p>You haven't enrolled in any courses yet.</p>
      <router-link to="/" class="text-blue-600 hover:underline mt-4 inline-block">
        Browse Courses
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="enrollment in courses"
        :key="enrollment.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
        @click="router.push(`/learn/${enrollment.course_id}`)"
      >
        <img
          :src="enrollment.thumbnail || 'https://via.placeholder.com/300x200'"
          :alt="enrollment.title"
          class="w-full h-48 object-cover rounded-t-lg"
        />
        <div class="p-4">
          <h3 class="font-bold text-lg mb-2">{{ enrollment.title }}</h3>

          <!-- Progress Bar -->
          <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
              <span class="text-gray-600">Progress</span>
              <span class="text-blue-600 font-semibold">
                {{ Math.round(enrollment.progress_percentage) }}%
              </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                class="bg-blue-600 h-2 rounded-full transition-all"
                :style="{ width: `${enrollment.progress_percentage}%` }"
              ></div>
            </div>
          </div>

          <div class="flex items-center justify-between text-sm text-gray-600">
            <span v-if="enrollment.is_completed" class="text-green-600 font-semibold">
              ✓ Completed
            </span>
            <span v-else>In Progress</span>
            <button class="text-blue-600 hover:underline">Continue</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'

const router = useRouter()

const courses = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchMyCourses()
})

async function fetchMyCourses() {
  try {
    const response = await portalService.getMyCourses()
    courses.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  } finally {
    loading.value = false
  }
}
</script>
