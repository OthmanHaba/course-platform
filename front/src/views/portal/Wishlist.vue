<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Wishlist</h1>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="animate-spin rounded-full h-10 w-10 border-4 border-indigo-200 border-t-indigo-600"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="wishlist.length === 0" class="text-center py-16">
      <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
      </svg>
      <h2 class="mt-4 text-xl font-semibold text-gray-900">Your wishlist is empty</h2>
      <p class="mt-2 text-gray-600">Browse courses and add them to your wishlist</p>
      <router-link
        to="/courses"
        class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors"
      >
        Browse Courses
      </router-link>
    </div>

    <!-- Wishlist Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="item in wishlist"
        :key="item.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
      >
        <!-- Course Image -->
        <router-link :to="`/courses/${item.course_id || item.id}`">
          <img
            :src="item.thumbnail || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=400&h=250&fit=crop'"
            :alt="item.title"
            class="w-full h-40 object-cover"
          />
        </router-link>

        <!-- Course Info -->
        <div class="p-4">
          <router-link :to="`/courses/${item.course_id || item.id}`">
            <h3 class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors line-clamp-2">
              {{ item.title }}
            </h3>
          </router-link>

          <p v-if="item.short_description" class="mt-2 text-sm text-gray-600 line-clamp-2">
            {{ item.short_description }}
          </p>

          <div class="mt-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span v-if="item.rating_average" class="flex items-center text-sm">
                <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ item.rating_average }}
              </span>
              <span v-if="item.level" class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                {{ item.level }}
              </span>
            </div>
            <span class="font-bold text-gray-900">
              {{ item.is_free ? 'Free' : `$${item.price}` }}
            </span>
          </div>

          <!-- Actions -->
          <div class="mt-4 flex gap-2">
            <router-link
              :to="`/courses/${item.course_id || item.id}`"
              class="flex-1 text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors"
            >
              View Course
            </router-link>
            <button
              @click="removeFromWishlist(item.course_id || item.id)"
              :disabled="removing === (item.course_id || item.id)"
              class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
              title="Remove from wishlist"
            >
              <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { portalService } from '@/services/portalService'

const wishlist = ref<any[]>([])
const loading = ref(true)
const removing = ref<number | null>(null)

onMounted(async () => {
  await fetchWishlist()
})

async function fetchWishlist() {
  try {
    const response = await portalService.getWishlist()
    wishlist.value = response.data.data || []
  } catch (error) {
    console.error('Failed to fetch wishlist:', error)
  } finally {
    loading.value = false
  }
}

async function removeFromWishlist(courseId: number) {
  removing.value = courseId
  try {
    await portalService.removeFromWishlist(courseId)
    wishlist.value = wishlist.value.filter(item => (item.course_id || item.id) !== courseId)
  } catch (error) {
    console.error('Failed to remove from wishlist:', error)
    alert('Failed to remove from wishlist')
  } finally {
    removing.value = null
  }
}
</script>
