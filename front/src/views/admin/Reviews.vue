<template>
  <div class="reviews-page">
    <header class="page-header">
      <h1>Reviews</h1>
    </header>

    <div class="reviews-table-container">
      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading reviews...</p>
      </div>

      <div v-else class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Course</th>
              <th>Rating</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="review in reviews" :key="review.id">
              <td class="student-name">{{ review.first_name }} {{ review.last_name }}</td>
              <td class="text-muted">{{ review.course_title }}</td>
              <td>
                <span class="badge badge-rating">{{ review.rating }}/5</span>
              </td>
              <td>
                <span class="badge" :class="review.is_approved ? 'badge-approved' : 'badge-pending'">
                  {{ review.is_approved ? 'Approved' : 'Pending' }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button
                    v-if="!review.is_approved"
                    @click="approveReview(review.id)"
                    class="action-btn"
                  >
                    Approve
                  </button>
                  <button @click="deleteReview(review.id)" class="action-btn delete">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="reviews.length === 0" class="empty-state">
          <p class="text-muted">No reviews found</p>
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

<style scoped>
.reviews-page {
  max-width: 1400px;
}

.page-header {
  margin-bottom: var(--spacing-2xl);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.reviews-table-container {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}

.loading-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background: var(--color-background-alt);
  border-bottom: 1px solid var(--color-border);
}

.data-table th {
  padding: 0.875rem 1.25rem;
  text-align: left;
  font-size: 0.8125rem;
  font-weight: 600;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.data-table tbody tr {
  border-bottom: 1px solid var(--color-border);
  transition: background-color 0.2s;
}

.data-table tbody tr:hover {
  background: var(--color-background-alt);
}

.data-table tbody tr:last-child {
  border-bottom: none;
}

.data-table td {
  padding: 1rem 1.25rem;
  font-size: 0.9375rem;
  color: var(--color-text);
}

.student-name {
  font-weight: 500;
}

.badge-rating {
  background: #fef3c7;
  color: #92400e;
}

.badge-approved {
  background: #d1fae5;
  color: #065f46;
}

.badge-pending {
  background: #fef3c7;
  color: #92400e;
}

.action-buttons {
  display: flex;
  gap: var(--spacing-sm);
}

.action-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--color-text-muted);
  background: transparent;
  border-radius: var(--radius-sm);
  transition: all 0.2s;
}

.action-btn:hover {
  color: var(--color-text);
  background: var(--color-background-alt);
}

.action-btn.delete:hover {
  color: var(--color-error);
  background: #fef2f2;
}

.empty-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

@media (max-width: 768px) {
  .data-table th,
  .data-table td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }

  .action-buttons {
    flex-direction: column;
    gap: 0.25rem;
  }
}
</style>
