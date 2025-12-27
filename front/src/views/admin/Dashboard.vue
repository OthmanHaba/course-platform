<template>
  <div class="dashboard">
    <header class="page-header">
      <h1>Dashboard</h1>
    </header>

    <div v-if="loading" class="loading-state">
      <div class="spinner"></div>
      <p class="text-muted">Loading analytics...</p>
    </div>

    <div v-else class="dashboard-content">
      <!-- Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card stat-students">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-value">{{ analytics?.total_students || 0 }}</p>
            <p class="stat-label">Students</p>
          </div>
        </div>

        <div class="stat-card stat-instructors">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
              <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-value">{{ analytics?.total_instructors || 0 }}</p>
            <p class="stat-label">Instructors</p>
          </div>
        </div>

        <div class="stat-card stat-courses">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-value">{{ analytics?.published_courses || 0 }}</p>
            <p class="stat-label">Published Courses</p>
          </div>
        </div>

        <div class="stat-card stat-enrollments">
          <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
          </div>
          <div class="stat-info">
            <p class="stat-value">{{ analytics?.total_enrollments || 0 }}</p>
            <p class="stat-label">Total Enrollments</p>
          </div>
        </div>
      </div>

      <!-- Analytics Cards -->
      <div class="analytics-grid">
        <!-- User Breakdown -->
        <div class="section-card">
          <h2>User Breakdown</h2>
          <div class="breakdown-list">
            <div class="breakdown-item">
              <span class="breakdown-label">Students</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-students" :style="{ width: userPercentage('student') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ userAnalytics?.students || 0 }}</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Instructors</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-instructors" :style="{ width: userPercentage('instructor') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ userAnalytics?.instructors || 0 }}</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Admins</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-admins" :style="{ width: userPercentage('admin') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ userAnalytics?.admins || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Course Status -->
        <div class="section-card">
          <h2>Course Status</h2>
          <div class="breakdown-list">
            <div class="breakdown-item">
              <span class="breakdown-label">Published</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-published" :style="{ width: coursePercentage('published') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ courseAnalytics?.published || 0 }}</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Draft</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-draft" :style="{ width: coursePercentage('draft') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ courseAnalytics?.draft || 0 }}</span>
            </div>
            <div class="breakdown-item">
              <span class="breakdown-label">Archived</span>
              <div class="breakdown-bar">
                <div class="bar-fill bar-archived" :style="{ width: coursePercentage('archived') + '%' }"></div>
              </div>
              <span class="breakdown-value">{{ courseAnalytics?.archived || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Top Courses -->
        <div class="section-card">
          <h2>Top Courses by Enrollment</h2>
          <div v-if="courseAnalytics?.popular_courses?.length > 0" class="top-courses-list">
            <div v-for="(course, index) in courseAnalytics.popular_courses.slice(0, 5)" :key="course.id" class="top-course-item">
              <span class="course-rank">{{ index + 1 }}</span>
              <span class="course-title">{{ course.title }}</span>
              <span class="course-enrollments">{{ course.enrollment_count }} students</span>
            </div>
          </div>
          <p v-else class="text-muted">No courses yet</p>
        </div>

        <!-- Top Rated -->
        <div class="section-card">
          <h2>Top Rated Courses</h2>
          <div v-if="courseAnalytics?.top_rated?.length > 0" class="top-courses-list">
            <div v-for="(course, index) in courseAnalytics.top_rated.slice(0, 5)" :key="course.id" class="top-course-item">
              <span class="course-rank">{{ index + 1 }}</span>
              <span class="course-title">{{ course.title }}</span>
              <span class="course-rating">
                <svg class="star-icon" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ parseFloat(course.rating_average).toFixed(1) }}
              </span>
            </div>
          </div>
          <p v-else class="text-muted">No rated courses yet</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="section-card">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
          <router-link to="/admin/courses/new" class="btn btn-primary">
            + Create Course
          </router-link>
          <router-link to="/admin/courses" class="btn btn-outline">
            Manage Courses
          </router-link>
          <router-link to="/admin/users" class="btn btn-outline">
            Manage Users
          </router-link>
          <router-link to="/admin/enrollments" class="btn btn-outline">
            View Enrollments
          </router-link>
          <router-link to="/admin/reviews" class="btn btn-outline">
            Moderate Reviews
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { adminService } from '@/services/adminService'

const analytics = ref<any>(null)
const userAnalytics = ref<any>(null)
const courseAnalytics = ref<any>(null)
const loading = ref(true)

const totalUsers = computed(() => {
  if (!userAnalytics.value) return 1
  return (userAnalytics.value.students || 0) + (userAnalytics.value.instructors || 0) + (userAnalytics.value.admins || 0) || 1
})

const totalCourses = computed(() => {
  if (!courseAnalytics.value) return 1
  return (courseAnalytics.value.published || 0) + (courseAnalytics.value.draft || 0) + (courseAnalytics.value.archived || 0) || 1
})

function userPercentage(type: string) {
  if (!userAnalytics.value) return 0
  const value = type === 'student' ? userAnalytics.value.students :
                type === 'instructor' ? userAnalytics.value.instructors :
                userAnalytics.value.admins || 0
  return Math.round((value / totalUsers.value) * 100)
}

function coursePercentage(status: string) {
  if (!courseAnalytics.value) return 0
  const value = courseAnalytics.value[status] || 0
  return Math.round((value / totalCourses.value) * 100)
}

onMounted(async () => {
  try {
    const [overviewRes, usersRes, coursesRes] = await Promise.all([
      adminService.getOverview(),
      adminService.getUserAnalytics(),
      adminService.getCourseAnalytics()
    ])
    analytics.value = overviewRes.data.data
    userAnalytics.value = usersRes.data.data
    courseAnalytics.value = coursesRes.data.data
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
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.loading-state {
  padding: 3rem;
  text-align: center;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid #e5e7eb;
  border-top-color: #4f46e5;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.dashboard-content {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
}

.stat-card {
  background: var(--color-background, #fff);
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: 0.75rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon svg {
  width: 24px;
  height: 24px;
}

.stat-students .stat-icon { background: #dbeafe; color: #2563eb; }
.stat-instructors .stat-icon { background: #d1fae5; color: #059669; }
.stat-courses .stat-icon { background: #fef3c7; color: #d97706; }
.stat-enrollments .stat-icon { background: #ede9fe; color: #7c3aed; }

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--color-text, #111827);
  line-height: 1.2;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--color-text-muted, #6b7280);
  margin-top: 0.25rem;
}

.analytics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
}

.section-card {
  background: var(--color-background, #fff);
  border: 1px solid var(--color-border, #e5e7eb);
  border-radius: 0.75rem;
  padding: 1.5rem;
}

.section-card h2 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: var(--color-text, #111827);
}

.breakdown-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.breakdown-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.breakdown-label {
  width: 80px;
  font-size: 0.8125rem;
  color: var(--color-text-muted, #6b7280);
}

.breakdown-bar {
  flex: 1;
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  overflow: hidden;
}

.bar-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 0.5s ease-out;
}

.bar-students { background: #3b82f6; }
.bar-instructors { background: #10b981; }
.bar-admins { background: #8b5cf6; }
.bar-published { background: #10b981; }
.bar-draft { background: #f59e0b; }
.bar-archived { background: #6b7280; }

.breakdown-value {
  width: 40px;
  font-size: 0.875rem;
  font-weight: 600;
  text-align: right;
  color: var(--color-text, #111827);
}

.top-courses-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.top-course-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0;
  border-bottom: 1px solid #f3f4f6;
}

.top-course-item:last-child {
  border-bottom: none;
}

.course-rank {
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6;
  border-radius: 50%;
  font-size: 0.75rem;
  font-weight: 600;
  color: #6b7280;
}

.course-title {
  flex: 1;
  font-size: 0.875rem;
  color: var(--color-text, #111827);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.course-enrollments,
.course-rating {
  font-size: 0.75rem;
  color: var(--color-text-muted, #6b7280);
  white-space: nowrap;
}

.course-rating {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  color: #f59e0b;
  font-weight: 600;
}

.star-icon {
  width: 14px;
  height: 14px;
}

.action-buttons {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  border-radius: 0.5rem;
  transition: all 0.2s;
}

.btn-primary {
  background: #4f46e5;
  color: white;
}

.btn-primary:hover {
  background: #4338ca;
}

.btn-outline {
  background: transparent;
  border: 1px solid var(--color-border, #e5e7eb);
  color: var(--color-text, #111827);
}

.btn-outline:hover {
  background: var(--color-background-alt, #f9fafb);
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .stat-card {
    flex-direction: column;
    text-align: center;
  }

  .stat-value {
    font-size: 1.5rem;
  }

  .analytics-grid {
    grid-template-columns: 1fr;
  }

  .action-buttons {
    flex-direction: column;
  }

  .action-buttons .btn {
    width: 100%;
    text-align: center;
  }
}
</style>
