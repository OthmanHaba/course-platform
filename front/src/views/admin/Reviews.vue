<template>
  <div class="max-w-7xl">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Reviews</h1>
    </header>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <p class="text-gray-500">Loading reviews...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="review in reviews" :key="review.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ review.first_name }} {{ review.last_name }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ review.course_title }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">
                  {{ review.rating }}/5
                </span>
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full"
                  :class="review.is_approved ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                >
                  {{ review.is_approved ? 'Approved' : 'Pending' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button
                    v-if="!review.is_approved"
                    @click="approveReview(review.id)"
                    class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md transition-colors"
                  >
                    Approve
                  </button>
                  <button @click="deleteReview(review.id)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="reviews.length === 0" class="p-8 text-center">
          <p class="text-gray-500">No reviews found</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const reviews = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchReviews()
})

async function fetchReviews() {
  try {
    const response = await adminService.getReviews()
    reviews.value = response.data.data.reviews
  } catch (error) {
    console.error('Failed to fetch reviews:', error)
  } finally {
    loading.value = false
  }
}

async function approveReview(id: number) {
  try {
    await adminService.approveReview(id)
    await fetchReviews()
  } catch (error) {
    console.error('Failed to approve review:', error)
    alert('Failed to approve review')
  }
}

async function deleteReview(id: number) {
  if (!confirm('Are you sure you want to delete this review?')) return

  try {
    await adminService.deleteReview(id)
    await fetchReviews()
  } catch (error) {
    console.error('Failed to delete review:', error)
    alert('Failed to delete review')
  }
}
</script>
