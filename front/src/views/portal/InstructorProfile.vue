<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="!instructor" class="text-center py-20">
      <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">Instructor not found</h3>
      <router-link to="/courses" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
        Browse Courses
      </router-link>
    </div>

    <!-- Instructor Profile -->
    <div v-else>
      <!-- Header -->
      <div class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
              <div v-if="instructor.avatar" class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/20">
                <img :src="instructor.avatar" :alt="fullName" class="w-full h-full object-cover" />
              </div>
              <div v-else class="w-32 h-32 rounded-full bg-indigo-600 flex items-center justify-center text-4xl font-bold border-4 border-white/20">
                {{ instructor.first_name?.[0] || 'I' }}{{ instructor.last_name?.[0] || '' }}
              </div>
            </div>

            <!-- Info -->
            <div class="text-center md:text-left flex-1">
              <h1 class="text-3xl font-bold">{{ fullName }}</h1>
              <p v-if="instructor.bio" class="mt-3 text-gray-300 max-w-2xl">{{ instructor.bio }}</p>

              <!-- Stats -->
              <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-6">
                <div class="text-center">
                  <div class="text-2xl font-bold">{{ courses.length }}</div>
                  <div class="text-sm text-gray-400">Courses</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold">{{ totalStudents }}</div>
                  <div class="text-sm text-gray-400">Students</div>
                </div>
                <div v-if="averageRating > 0" class="text-center">
                  <div class="text-2xl font-bold flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ averageRating.toFixed(1) }}
                  </div>
                  <div class="text-sm text-gray-400">Avg. Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Courses Section -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Courses by {{ fullName }}</h2>

        <div v-if="courses.length === 0" class="text-center py-12 bg-white rounded-lg border border-gray-200">
          <p class="text-gray-600">This instructor has no published courses yet.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="course in courses"
            :key="course.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
          >
            <router-link :to="`/courses/${course.id}`">
              <img
                :src="course.thumbnail || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=250&fit=crop'"
                :alt="course.title"
                class="w-full h-40 object-cover"
              />
            </router-link>

            <div class="p-4">
              <div class="flex items-center gap-2 mb-2">
                <span v-if="course.level" class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                  {{ course.level }}
                </span>
                <span v-if="course.is_free" class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded">
                  Free
                </span>
              </div>

              <router-link :to="`/courses/${course.id}`">
                <h3 class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors line-clamp-2">
                  {{ course.title }}
                </h3>
              </router-link>

              <p v-if="course.short_description" class="mt-2 text-sm text-gray-600 line-clamp-2">
                {{ course.short_description }}
              </p>

              <div class="mt-3 flex items-center justify-between">
                <div class="flex items-center gap-3 text-sm text-gray-500">
                  <span v-if="course.rating_average" class="flex items-center">
                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    {{ course.rating_average }}
                  </span>
                  <span>{{ course.enrollment_count || 0 }} students</span>
                </div>
                <span class="font-bold text-gray-900">
                  {{ course.is_free ? 'Free' : `$${course.price}` }}
                </span>
              </div>

              <router-link
                :to="`/courses/${course.id}`"
                class="mt-4 block text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
              >
                View Course
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { portalService } from '@/services/portalService'

const route = useRoute()
const instructorId = Number(route.params.id)

const instructor = ref<any>(null)
const courses = ref<any[]>([])
const loading = ref(true)

const fullName = computed(() => {
  if (!instructor.value) return ''
  return `${instructor.value.first_name || ''} ${instructor.value.last_name || ''}`.trim() || 'Instructor'
})

const totalStudents = computed(() => {
  return courses.value.reduce((sum, course) => sum + (course.enrollment_count || 0), 0)
})

const averageRating = computed(() => {
  const rated = courses.value.filter(c => c.rating_average)
  if (rated.length === 0) return 0
  return rated.reduce((sum, c) => sum + parseFloat(c.rating_average), 0) / rated.length
})

onMounted(async () => {
  await fetchInstructor()
})

async function fetchInstructor() {
  try {
    const response = await portalService.getInstructor(instructorId)
    const data = response.data.data
    instructor.value = data.instructor || data
    courses.value = data.courses || []
  } catch (error) {
    console.error('Failed to fetch instructor:', error)
  } finally {
    loading.value = false
  }
}
</script>
