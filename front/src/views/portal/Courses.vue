<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">All Courses</h1>

    <div class="mb-6 flex gap-4">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search courses..."
        class="flex-1 px-4 py-2 border rounded"
        @keyup.enter="searchCourses"
      />
      <select v-model="selectedLevel" @change="filterCourses" class="px-4 py-2 border rounded">
        <option value="">All Levels</option>
        <option value="beginner">Beginner</option>
        <option value="intermediate">Intermediate</option>
        <option value="advanced">Advanced</option>
      </select>
    </div>

    <div v-if="loading" class="text-center py-8">Loading...</div>

    <div v-else-if="courses.length === 0" class="text-center py-8 text-gray-600">
      No courses found.
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <div
        v-for="course in courses"
        :key="course.id"
        class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
        @click="router.push(`/courses/${course.id}`)"
      >
        <img
          :src="course.thumbnail || 'https://via.placeholder.com/300x200'"
          :alt="course.title"
          class="w-full h-48 object-cover rounded-t-lg"
        />
        <div class="p-4">
          <h3 class="font-bold text-lg mb-2">{{ course.title }}</h3>
          <p class="text-gray-600 text-sm mb-2 line-clamp-2">
            {{ course.short_description }}
          </p>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-500">⭐ {{ course.rating_average || 'N/A' }}</span>
            <span class="font-bold text-blue-600">
              {{ course.is_free ? 'Free' : `$${course.price}` }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { portalService } from '@/services/portalService'

const router = useRouter()
const route = useRoute()

const courses = ref<any[]>([])
const loading = ref(true)
const searchQuery = ref((route.query.search as string) || '')
const selectedLevel = ref('')

onMounted(async () => {
  await fetchCourses()
})

async function fetchCourses() {
  try {
    const params: any = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (selectedLevel.value) params.level = selectedLevel.value

    const response = await portalService.getCourses(params)
    courses.value = response.data.data.courses
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  } finally {
    loading.value = false
  }
}

function searchCourses() {
  loading.value = true
  fetchCourses()
}

function filterCourses() {
  loading.value = true
  fetchCourses()
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
