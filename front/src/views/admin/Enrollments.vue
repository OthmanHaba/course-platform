<template>
  <div class="enrollments-page">
    <header class="page-header">
      <h1>Enrollments</h1>
    </header>

    <div class="enrollments-table-container">
      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading enrollments...</p>
      </div>

      <div v-else class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Student</th>
              <th>Course</th>
              <th>Progress</th>
              <th>Enrolled On</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="enrollment in enrollments" :key="enrollment.id">
              <td class="student-name">
                {{ enrollment.first_name }} {{ enrollment.last_name }}
              </td>
              <td class="text-muted">{{ enrollment.course_title }}</td>
              <td>
                <span class="badge badge-progress">{{ Math.round(enrollment.progress_percentage) }}%</span>
              </td>
              <td class="text-muted">{{ formatDate(enrollment.enrollment_date) }}</td>
              <td>
                <button @click="deleteEnrollment(enrollment.id)" class="action-btn delete">
                  Remove
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="enrollments.length === 0" class="empty-state">
          <p class="text-muted">No enrollments found</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const enrollments = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchEnrollments()
})

async function fetchEnrollments() {
  try {
    const response = await adminService.getEnrollments()
    enrollments.value = response.data.data.enrollments
  } catch (error) {
    console.error('Failed to fetch enrollments:', error)
  } finally {
    loading.value = false
  }
}

async function deleteEnrollment(id: number) {
  if (!confirm('Are you sure you want to remove this enrollment?')) return

  try {
    await adminService.deleteEnrollment(id)
    await fetchEnrollments()
  } catch (error) {
    console.error('Failed to delete enrollment:', error)
    alert('Failed to remove enrollment')
  }
}

function formatDate(dateString: string) {
  return new Date(dateString).toLocaleDateString()
}
</script>

<style scoped>
.enrollments-page {
  max-width: 1400px;
}

.page-header {
  margin-bottom: var(--spacing-2xl);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.enrollments-table-container {
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

.badge-progress {
  background: #e0e7ff;
  color: #4338ca;
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
}
</style>
