<template>
  <div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-3xl">
          <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
            Learn Without Limits
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-indigo-100">
            Start, switch, or advance your career with thousands of courses from expert instructors.
          </p>

          <!-- Search Box -->
          <div class="relative max-w-2xl">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="What do you want to learn?"
                @keyup.enter="searchCourses"
                class="w-full px-6 py-4 pr-32 rounded-lg text-gray-900 text-lg focus:outline-none focus:ring-2 focus:ring-white shadow-xl"
              />
              <button
                @click="searchCourses"
                class="absolute right-2 top-2 px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors font-medium"
              >
                Search
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Wave decoration -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="rgb(249, 250, 251)"/>
        </svg>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div>
            <div class="text-3xl md:text-4xl font-bold text-indigo-600">10K+</div>
            <div class="text-gray-600 mt-2">Students</div>
          </div>
          <div>
            <div class="text-3xl md:text-4xl font-bold text-indigo-600">500+</div>
            <div class="text-gray-600 mt-2">Courses</div>
          </div>
          <div>
            <div class="text-3xl md:text-4xl font-bold text-indigo-600">50+</div>
            <div class="text-gray-600 mt-2">Instructors</div>
          </div>
          <div>
            <div class="text-3xl md:text-4xl font-bold text-indigo-600">20+</div>
            <div class="text-gray-600 mt-2">Categories</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold text-gray-900">Featured Courses</h2>
          <router-link to="/courses" class="text-indigo-600 hover:text-indigo-700 font-medium">
            View all →
          </router-link>
        </div>

        <div v-if="loadingFeatured" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
          <p class="mt-4 text-gray-600">Loading courses...</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="course in featuredCourses"
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
            </div>

            <div class="p-4">
              <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                {{ course.title }}
              </h3>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ course.short_description }}
              </p>

              <div class="flex items-center justify-between">
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
    </section>

    <!-- Categories -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Top Categories</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
          <div
            v-for="category in categories"
            :key="category.id"
            @click="router.push(`/categories/${category.id}`)"
            class="bg-white border border-gray-200 rounded-lg p-6 text-center hover:border-indigo-500 hover:shadow-md transition-all cursor-pointer group"
          >
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-600 transition-colors">
              <svg class="w-6 h-6 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
              </svg>
            </div>
            <h3 class="font-medium text-gray-900 text-sm">{{ category.name }}</h3>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-indigo-600 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to start learning?</h2>
        <p class="text-xl text-indigo-100 mb-8">Join thousands of students already learning on LearnHub</p>
        <router-link
          to="/register"
          class="inline-block px-8 py-4 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors"
        >
          Get Started Free
        </router-link>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'

const router = useRouter()

const searchQuery = ref('')
const featuredCourses = ref<any[]>([])
const categories = ref<any[]>([])
const loadingFeatured = ref(true)

onMounted(async () => {
  await Promise.all([fetchFeaturedCourses(), fetchCategories()])
})

async function fetchFeaturedCourses() {
  try {
    const response = await portalService.getFeaturedCourses()
    featuredCourses.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch featured courses:', error)
  } finally {
    loadingFeatured.value = false
  }
}

async function fetchCategories() {
  try {
    const response = await portalService.getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch categories:', error)
  }
}

function searchCourses() {
  if (searchQuery.value.trim()) {
    router.push({ path: '/courses', query: { search: searchQuery.value } })
  }
}
</script>
