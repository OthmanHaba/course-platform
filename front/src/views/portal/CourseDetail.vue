<template>
  <div v-if="loading" class="container mx-auto px-4 py-8">Loading...</div>

  <div v-else-if="course" class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-4">{{ course.title }}</h1>
        <p class="text-gray-600 mb-6">{{ course.short_description }}</p>

        <div class="flex items-center gap-4 mb-6">
          <span class="text-sm">⭐ {{ course.rating_average || 'N/A' }}</span>
          <span class="text-sm text-gray-600">
            {{ course.enrollment_count }} students enrolled
          </span>
          <span class="text-sm text-gray-600">{{ course.level }}</span>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-2xl font-bold mb-4">What you'll learn</h2>
          <div v-html="course.objectives" class="prose max-w-none"></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
          <h2 class="text-2xl font-bold mb-4">Course Description</h2>
          <div v-html="course.description" class="prose max-w-none"></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-2xl font-bold mb-4">Requirements</h2>
          <div v-html="course.requirements" class="prose max-w-none"></div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-4">
          <img
            :src="course.thumbnail || 'https://via.placeholder.com/400x300'"
            :alt="course.title"
            class="w-full rounded-lg mb-4"
          />

          <div class="text-3xl font-bold mb-4">
            {{ course.is_free ? 'Free' : `$${course.price}` }}
          </div>

          <button
            v-if="!enrolled"
            @click="enrollInCourse"
            :disabled="enrolling"
            class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50 mb-4"
          >
            {{ enrolling ? 'Enrolling...' : 'Enroll Now' }}
          </button>

          <button
            v-else
            @click="router.push(`/my-courses`)"
            class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 mb-4"
          >
            Go to Course
          </button>

          <button
            @click="toggleWishlist"
            class="w-full border border-gray-300 py-2 rounded-lg hover:bg-gray-50"
          >
            {{ inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-12 bg-white rounded-lg shadow p-6">
      <h2 class="text-2xl font-bold mb-6">Student Reviews</h2>
      <div v-if="loadingReviews" class="text-center">Loading reviews...</div>
      <div v-else-if="reviews.length === 0" class="text-gray-600">
        No reviews yet. Be the first to review!
      </div>
      <div v-else class="space-y-4">
        <div v-for="review in reviews" :key="review.id" class="border-b pb-4">
          <div class="flex items-center gap-2 mb-2">
            <span class="font-semibold">
              {{ review.first_name }} {{ review.last_name }}
            </span>
            <span class="text-sm text-gray-500">⭐ {{ review.rating }}/5</span>
          </div>
          <p class="text-gray-700">{{ review.review_text }}</p>
        </div>
      </div>
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
