<template>
  <div class="courses-page">
    <header class="page-header">
      <h1>Courses</h1>
      <button @click="router.push('/admin/courses/new')" class="create-btn">
        + Create Course
      </button>
    </header>

    <!-- Search and Filter -->
    <div class="filters-bar">
      <div class="search-box">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search courses..."
          @keyup.enter="fetchCourses"
        />
        <button @click="fetchCourses" class="search-btn">Search</button>
      </div>
      <div class="filter-group">
        <select v-model="statusFilter" @change="fetchCourses">
          <option value="">All Status</option>
          <option value="draft">Draft</option>
          <option value="published">Published</option>
          <option value="archived">Archived</option>
        </select>
      </div>
    </div>

    <div class="courses-table-container">
      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading courses...</p>
      </div>

      <div v-else class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Title</th>
              <th>Status</th>
              <th>Students</th>
              <th>Rating</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="course in courses" :key="course.id">
              <td class="course-title">{{ course.title }}</td>
              <td>
                <span class="badge" :class="`badge-${course.status}`">
                  {{ course.status }}
                </span>
              </td>
              <td>{{ course.enrollment_count || 0 }}</td>
              <td>{{ course.rating_average || '—' }}</td>
              <td>
                <div class="action-buttons">
                  <button @click="editCourse(course.id)" class="action-btn edit">
                    Edit
                  </button>
                  <button @click="viewCourse(course.id)" class="action-btn">
                    View
                  </button>
                  <button @click="deleteCourse(course.id)" class="action-btn delete">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="courses.length === 0" class="empty-state">
          <p class="text-muted">No courses found</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { adminService } from '@/services/adminService'

const router = useRouter()
const courses = ref<any[]>([])
const loading = ref(true)
const searchQuery = ref('')
const statusFilter = ref('')

onMounted(async () => {
  await fetchCourses()
})

async function fetchCourses() {
  loading.value = true
  try {
    const params: any = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (statusFilter.value) params.status = statusFilter.value

    const response = await adminService.getCourses(params)
    courses.value = response.data.data.courses || []
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  } finally {
    loading.value = false
  }
}

function editCourse(id: number) {
  router.push(`/admin/courses/${id}`)
}

function viewCourse(id: number) {
  router.push(`/courses/${id}`)
}

async function deleteCourse(id: number) {
  if (!confirm('Delete this course?')) return

  try {
    await adminService.deleteCourse(id)
    await fetchCourses()
  } catch (error) {
    console.error('Failed to delete course:', error)
    alert('Failed to delete course')
  }
}
</script>

<style scoped>
.courses-page {
  max-width: 1400px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.create-btn {
  padding: 0.5rem 1rem;
  background: var(--color-primary, #4f46e5);
  color: white;
  border-radius: var(--radius-md, 0.375rem);
  font-weight: 500;
  font-size: 0.875rem;
}

.create-btn:hover {
  opacity: 0.9;
}

.filters-bar {
  display: flex;
  gap: 1rem;
  margin-bottom: var(--spacing-lg, 1.5rem);
  flex-wrap: wrap;
}

.search-box {
  display: flex;
  gap: 0.5rem;
  flex: 1;
  min-width: 200px;
}

.search-box input {
  flex: 1;
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: var(--radius-md, 0.375rem);
  font-size: 0.875rem;
}

.search-btn {
  padding: 0.5rem 1rem;
  background: var(--color-background-alt, #f9fafb);
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: var(--radius-md, 0.375rem);
  font-size: 0.875rem;
}

.search-btn:hover {
  background: var(--color-border, #e5e7eb);
}

.filter-group select {
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: var(--radius-md, 0.375rem);
  font-size: 0.875rem;
  min-width: 150px;
}

.action-btn.edit {
  color: var(--color-primary, #4f46e5);
}

.action-btn.edit:hover {
  background: #eef2ff;
}

.courses-table-container {
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

.course-title {
  font-weight: 500;
}

.badge-published {
  background: #d1fae5;
  color: #065f46;
}

.badge-draft {
  background: #fef3c7;
  color: #92400e;
}

.badge-archived {
  background: var(--color-background-alt);
  color: var(--color-text-muted);
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
