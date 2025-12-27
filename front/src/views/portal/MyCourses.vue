<template>
  <div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Learning</h1>
        <p class="mt-2 text-gray-600">Track your progress and continue where you left off</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center py-20">
        <div class="text-center">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
          <p class="mt-4 text-gray-600">Loading your courses...</p>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="courses.length === 0" class="text-center py-20">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
          <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No courses yet</h3>
        <p class="text-gray-600 mb-6">Start learning today by enrolling in a course</p>
        <router-link
          to="/courses"
          class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          Browse Courses
        </router-link>
      </div>

      <!-- Courses Grid -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="enrollment in courses"
          :key="enrollment.id"
          @click="router.push(`/learn/${enrollment.course_id}`)"
          class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-xl transition-all cursor-pointer group"
        >
          <!-- Course Thumbnail -->
          <div class="relative overflow-hidden">
            <img
              :src="enrollment.thumbnail || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=250&fit=crop'"
              :alt="enrollment.title"
              class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div v-if="enrollment.is_completed" class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
              <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
              Completed
            </div>
          </div>

          <!-- Course Info -->
          <div class="p-5">
            <h3 class="font-semibold text-lg text-gray-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
              {{ enrollment.title }}
            </h3>

            <!-- Progress Section -->
            <div class="mb-4">
              <div class="flex items-center justify-between text-sm mb-2">
                <span class="text-gray-600 font-medium">Your Progress</span>
                <span class="text-indigo-600 font-bold">
                  {{ Math.round(enrollment.progress_percentage || 0) }}%
                </span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                <div
                  class="h-2.5 rounded-full transition-all duration-500 ease-out"
                  :class="enrollment.is_completed ? 'bg-green-500' : 'bg-indigo-600'"
                  :style="{ width: `${enrollment.progress_percentage || 0}%` }"
                ></div>
              </div>
            </div>

            <!-- Status and Action -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
              <div class="flex items-center text-sm">
                <span v-if="enrollment.is_completed" class="flex items-center text-green-600 font-semibold">
                  <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  Completed
                </span>
                <span v-else class="flex items-center text-gray-600">
                  <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  In Progress
                </span>
              </div>
              <span class="text-sm font-medium text-indigo-600 group-hover:text-indigo-700">
                {{ enrollment.is_completed ? 'Review' : 'Continue' }} →
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Section (if courses exist) -->
      <div v-if="!loading && courses.length > 0" class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
          <div class="text-3xl font-bold text-indigo-600 mb-2">{{ courses.length }}</div>
          <div class="text-sm text-gray-600">Enrolled Courses</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
          <div class="text-3xl font-bold text-green-600 mb-2">
            {{ courses.filter(c => c.is_completed).length }}
          </div>
          <div class="text-sm text-gray-600">Completed Courses</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
          <div class="text-3xl font-bold text-gray-900 mb-2">
            {{ Math.round(courses.reduce((sum, c) => sum + (c.progress_percentage || 0), 0) / courses.length) }}%
          </div>
          <div class="text-sm text-gray-600">Average Progress</div>
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
