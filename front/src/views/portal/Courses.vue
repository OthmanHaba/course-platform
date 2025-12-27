<template>
  <div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">All Courses</h1>
        <p class="mt-2 text-gray-600">Explore our comprehensive course catalog</p>
      </div>

      <!-- Filters -->
      <div class="mb-8 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search courses..."
            @keyup.enter="searchCourses"
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
          />
        </div>
        <select
          v-model="selectedLevel"
          @change="filterCourses"
          class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
        >
          <option value="">All Levels</option>
          <option value="beginner">Beginner</option>
          <option value="intermediate">Intermediate</option>
          <option value="advanced">Advanced</option>
        </select>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
        <p class="mt-4 text-gray-600">Loading courses...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="courses.length === 0" class="text-center py-20">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">No courses found</h3>
        <p class="mt-2 text-gray-500">Try adjusting your search or filters</p>
      </div>

      <!-- Courses Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="course in courses"
          :key="course.id"
          @click="router.push(`/courses/${course.id}`)"
          class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-xl transition-all cursor-pointer group"
        >
          <div class="relative overflow-hidden">
            <img
              :src="course.thumbnail || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=250&fit=crop'"
              :alt="course.title"
              class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-if="course.is_free" class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
              FREE
            </div>
            <div v-if="course.level" class="absolute top-2 left-2 bg-gray-900 bg-opacity-75 text-white text-xs font-medium px-2 py-1 rounded">
              {{ course.level }}
            </div>
          </div>

          <div class="p-4">
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">
              {{ course.title }}
            </h3>
            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
              {{ course.short_description }}
            </p>

            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
              <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span>{{ course.rating_average || 'New' }}</span>
              </div>
              <div class="text-lg font-bold text-gray-900">
                {{ course.is_free ? 'Free' : `$${course.price}` }}
              </div>
            </div>
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
