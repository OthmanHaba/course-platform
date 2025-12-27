<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="text-center">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
        <p class="mt-4 text-gray-600">Loading course details...</p>
      </div>
    </div>

    <!-- Course Content -->
    <div v-else-if="course">
      <!-- Course Header -->
      <div class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="max-w-4xl">
            <div class="flex items-center space-x-2 mb-3">
              <span v-if="course.level" class="px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-bold rounded uppercase">
                {{ course.level }}
              </span>
              <span v-if="course.is_free" class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded uppercase">
                Free
              </span>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight">{{ course.title }}</h1>
            <p class="text-xl text-gray-300 mb-6">{{ course.short_description }}</p>

            <div class="flex flex-wrap items-center gap-4 text-sm">
              <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="font-semibold">{{ course.rating_average || 'New' }}</span>
              </div>
              <div class="flex items-center text-gray-300">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>{{ course.enrollment_count || 0 }} students enrolled</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column - Course Details -->
          <div class="lg:col-span-2 space-y-6">
            <!-- What You'll Learn -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">What you'll learn</h2>
              <div v-html="course.objectives" class="prose prose-indigo max-w-none text-gray-700"></div>
            </div>

            <!-- Course Description -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Course Description</h2>
              <div v-html="course.description" class="prose prose-indigo max-w-none text-gray-700"></div>
            </div>

            <!-- Requirements -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
              <div v-html="course.requirements" class="prose prose-indigo max-w-none text-gray-700"></div>
            </div>

            <!-- Reviews Section -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
              <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Student Reviews</h2>
                <div v-if="course.rating_average" class="flex items-center">
                  <svg class="w-6 h-6 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="text-2xl font-bold text-gray-900">{{ course.rating_average }}</span>
                </div>
              </div>

              <div v-if="loadingReviews" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-200 border-t-indigo-600"></div>
                <p class="mt-3 text-gray-600 text-sm">Loading reviews...</p>
              </div>

              <div v-else-if="reviews.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No reviews yet</h3>
                <p class="mt-2 text-gray-500">Be the first to share your experience!</p>
              </div>

              <div v-else class="space-y-6">
                <div v-for="review in reviews" :key="review.id" class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                  <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                      <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-sm font-semibold text-indigo-600">
                          {{ review.first_name?.[0] || 'U' }}{{ review.last_name?.[0] || '' }}
                        </span>
                      </div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-gray-900">
                          {{ review.first_name }} {{ review.last_name }}
                        </p>
                        <div class="flex items-center">
                          <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                          <span class="text-sm font-semibold text-gray-900">{{ review.rating }}</span>
                        </div>
                      </div>
                      <p class="text-sm text-gray-700 leading-relaxed">{{ review.review_text }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column - Sidebar -->
          <div class="lg:col-span-1">
            <div class="sticky top-4">
              <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <!-- Course Thumbnail -->
                <div class="relative">
                  <img
                    :src="course.thumbnail || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=250&fit=crop'"
                    :alt="course.title"
                    class="w-full h-48 object-cover"
                  />
                  <div v-if="enrolled" class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                    Enrolled
                  </div>
                </div>

                <div class="p-6">
                  <!-- Price -->
                  <div class="mb-6">
                    <div class="text-4xl font-bold text-gray-900">
                      {{ course.is_free ? 'Free' : `$${course.price}` }}
                    </div>
                  </div>

                  <!-- Action Buttons -->
                  <div class="space-y-3">
                    <button
                      v-if="!enrolled"
                      @click="enrollInCourse"
                      :disabled="enrolling"
                      class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                    >
                      <svg v-if="enrolling" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
                    </button>

                    <button
                      v-else
                      @click="router.push(`/my-courses`)"
                      class="w-full bg-green-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors shadow-sm"
                    >
                      Go to My Courses
                    </button>

                    <button
                      @click="toggleWishlist"
                      class="w-full border-2 border-gray-300 text-gray-700 font-medium py-2.5 px-4 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors"
                    >
                      <svg class="w-5 h-5 inline mr-2" :class="inWishlist ? 'fill-red-500' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                      {{ inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                    </button>
                  </div>

                  <!-- Course Details -->
                  <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Self-paced learning</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Certificate of completion</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                      <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                      </svg>
                      <span>Mobile & desktop access</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="text-center py-20">
      <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-4 text-lg font-medium text-gray-900">Course not found</h3>
      <p class="mt-2 text-gray-500">The course you're looking for doesn't exist.</p>
      <router-link to="/courses" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
        Browse All Courses
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'
import { usePortalAuthStore } from '@/stores/portalAuth'

const route = useRoute()
const router = useRouter()
const authStore = usePortalAuthStore()

const course = ref<any>(null)
const reviews = ref<any[]>([])
const loading = ref(true)
const loadingReviews = ref(true)
const enrolled = ref(false)
const enrolling = ref(false)
const inWishlist = ref(false)

const courseId = Number(route.params.id)

onMounted(async () => {
  await fetchCourse()
  await fetchReviews()
})

async function fetchCourse() {
  try {
    const response = await portalService.getCourse(courseId)
    course.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch course:', error)
  } finally {
    loading.value = false
  }
}

async function fetchReviews() {
  try {
    const response = await portalService.getCourseReviews(courseId)
    reviews.value = response.data.data.reviews || []
  } catch (error) {
    console.error('Failed to fetch reviews:', error)
  } finally {
    loadingReviews.value = false
  }
}

async function enrollInCourse() {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  enrolling.value = true
  try {
    await portalService.enrollCourse(courseId)
    enrolled.value = true
    alert('Successfully enrolled in the course!')
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to enroll')
  } finally {
    enrolling.value = false
  }
}

async function toggleWishlist() {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    if (inWishlist.value) {
      await portalService.removeFromWishlist(courseId)
      inWishlist.value = false
    } else {
      await portalService.addToWishlist(courseId)
      inWishlist.value = true
    }
  } catch (error) {
    console.error('Failed to update wishlist:', error)
  }
}
</script>
