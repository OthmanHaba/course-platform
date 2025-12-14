<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6">Reviews Management</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">Loading...</div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="review in reviews" :key="review.id">
              <td class="px-6 py-4">{{ review.first_name }} {{ review.last_name }}</td>
              <td class="px-6 py-4">{{ review.course_title }}</td>
              <td class="px-6 py-4">⭐ {{ review.rating }}/5</td>
              <td class="px-6 py-4">
                <span
                  :class="{
                    'px-2 py-1 rounded text-sm': true,
                    'bg-green-100 text-green-800': review.is_approved,
                    'bg-yellow-100 text-yellow-800': !review.is_approved
                  }"
                >
                  {{ review.is_approved ? 'Approved' : 'Pending' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <button
                  v-if="!review.is_approved"
                  @click="approveReview(review.id)"
                  class="text-green-600 hover:underline mr-3"
                >
                  Approve
                </button>
                <button @click="deleteReview(review.id)" class="text-red-600 hover:underline">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
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
