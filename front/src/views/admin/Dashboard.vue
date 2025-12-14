<template>
  <div class="dashboard">
    <header class="page-header">
      <h1>Dashboard</h1>
    </header>

    <div v-if="loading" class="loading-state">
      <p class="text-muted">Loading analytics...</p>
    </div>

    <div v-else class="dashboard-content">
      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <p class="stat-label">Students</p>
          <p class="stat-value">{{ analytics?.total_students || 0 }}</p>
        </div>

        <div class="stat-card">
          <p class="stat-label">Instructors</p>
          <p class="stat-value">{{ analytics?.total_instructors || 0 }}</p>
        </div>

        <div class="stat-card">
          <p class="stat-label">Courses</p>
          <p class="stat-value">{{ analytics?.published_courses || 0 }}</p>
        </div>

        <div class="stat-card">
          <p class="stat-label">Enrollments</p>
          <p class="stat-value">{{ analytics?.total_enrollments || 0 }}</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="actions-section">
        <div class="section-card">
          <h2>Quick Actions</h2>
          <div class="action-buttons">
            <router-link to="/admin/courses" class="btn btn-primary">
              Manage Courses
            </router-link>
            <router-link to="/admin/users" class="btn btn-outline">
              Manage Users
            </router-link>
            <router-link to="/admin/enrollments" class="btn btn-outline">
              View Enrollments
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const analytics = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try {
    const response = await adminService.getOverview()
    analytics.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch analytics:', error)
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.dashboard {
  max-width: 1400px;
}

.page-header {
  margin-bottom: var(--spacing-2xl);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.loading-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

.dashboard-content {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-2xl);
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: var(--spacing-lg);
}

.stat-card {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: var(--spacing-lg);
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--color-text-muted);
  margin-bottom: var(--spacing-sm);
}

.stat-value {
  font-size: 2rem;
  font-weight: 600;
  color: var(--color-text);
  line-height: 1.2;
}

.actions-section {
  display: grid;
  gap: var(--spacing-lg);
}

.section-card {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: var(--spacing-xl);
}

.section-card h2 {
  font-size: 1.125rem;
  font-weight: 600;
  margin-bottom: var(--spacing-lg);
}

.action-buttons {
  display: flex;
  gap: var(--spacing-md);
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .stat-value {
    font-size: 1.5rem;
  }

  .action-buttons {
    flex-direction: column;
  }

  .action-buttons .btn {
    width: 100%;
  }
}
</style>
