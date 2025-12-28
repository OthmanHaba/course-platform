<template>
  <div class="max-w-7xl">
    <header class="mb-8">
      <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    </header>

    <div v-if="loading" class="text-center py-12">
      <div class="w-10 h-10 border-3 border-gray-200 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>
      <p class="text-gray-500">Loading analytics...</p>
    </div>

    <div v-else class="space-y-8">
      <!-- Stats Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4">
          <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
            </svg>
          </div>
          <div>
            <p class="text-3xl font-bold text-gray-900">{{ analytics?.total_students || 0 }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Students</p>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4">
          <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
              <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </svg>
          </div>
          <div>
            <p class="text-3xl font-bold text-gray-900">{{ analytics?.total_instructors || 0 }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Instructors</p>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4">
          <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
            </svg>
          </div>
          <div>
            <p class="text-3xl font-bold text-gray-900">{{ analytics?.published_courses || 0 }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Published Courses</p>
          </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6 flex items-center gap-4">
          <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
              <line x1="16" y1="2" x2="16" y2="6" />
              <line x1="8" y1="2" x2="8" y2="6" />
              <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
          </div>
          <div>
            <p class="text-3xl font-bold text-gray-900">{{ analytics?.total_enrollments || 0 }}</p>
            <p class="text-sm font-medium text-gray-500 mt-1">Total Enrollments</p>
          </div>
        </div>
      </div>

      <!-- Analytics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User Breakdown -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <h2 class="text-base font-semibold text-gray-900 mb-4">User Breakdown</h2>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Students</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full transition-all duration-500" :style="{ width: userPercentage('student') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ userAnalytics?.students || 0 }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Instructors</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full transition-all duration-500" :style="{ width: userPercentage('instructor') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ userAnalytics?.instructors || 0 }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Admins</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-purple-500 rounded-full transition-all duration-500" :style="{ width: userPercentage('admin') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ userAnalytics?.admins || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Course Status -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <h2 class="text-base font-semibold text-gray-900 mb-4">Course Status</h2>
          <div class="space-y-3">
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Published</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full transition-all duration-500" :style="{ width: coursePercentage('published') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ courseAnalytics?.published || 0 }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Draft</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 rounded-full transition-all duration-500" :style="{ width: coursePercentage('draft') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ courseAnalytics?.draft || 0 }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="w-20 text-sm text-gray-500">Archived</span>
              <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gray-500 rounded-full transition-all duration-500" :style="{ width: coursePercentage('archived') + '%' }"></div>
              </div>
              <span class="w-10 text-sm font-semibold text-gray-900 text-right">{{ courseAnalytics?.archived || 0 }}</span>
            </div>
          </div>
        </div>

        <!-- Top Courses -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <h2 class="text-base font-semibold text-gray-900 mb-4">Top Courses by Enrollment</h2>
          <div v-if="courseAnalytics?.popular_courses?.length > 0" class="space-y-2">
            <div v-for="(course, index) in courseAnalytics.popular_courses.slice(0, 5)" :key="course.id" class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
              <span class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-full text-xs font-semibold text-gray-500">{{ index + 1 }}</span>
              <span class="flex-1 text-sm text-gray-900 truncate">{{ course.title }}</span>
              <span class="text-xs text-gray-500 whitespace-nowrap">{{ course.enrollment_count }} students</span>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No courses yet</p>
        </div>

        <!-- Top Rated -->
        <div class="bg-white border border-gray-200 rounded-xl p-6">
          <h2 class="text-base font-semibold text-gray-900 mb-4">Top Rated Courses</h2>
          <div v-if="courseAnalytics?.top_rated?.length > 0" class="space-y-2">
            <div v-for="(course, index) in courseAnalytics.top_rated.slice(0, 5)" :key="course.id" class="flex items-center gap-3 py-2 border-b border-gray-100 last:border-0">
              <span class="w-6 h-6 flex items-center justify-center bg-gray-100 rounded-full text-xs font-semibold text-gray-500">{{ index + 1 }}</span>
              <span class="flex-1 text-sm text-gray-900 truncate">{{ course.title }}</span>
              <span class="flex items-center gap-1 text-xs font-semibold text-amber-500 whitespace-nowrap">
                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ parseFloat(course.rating_average).toFixed(1) }}
              </span>
            </div>
          </div>
          <p v-else class="text-gray-500 text-sm">No rated courses yet</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="text-base font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="flex flex-wrap gap-3">
          <router-link to="/admin/courses/new" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + Create Course
          </router-link>
          <router-link to="/admin/courses" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Manage Courses
          </router-link>
          <router-link to="/admin/users" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Manage Users
          </router-link>
          <router-link to="/admin/enrollments" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            View Enrollments
          </router-link>
          <router-link to="/admin/reviews" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
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
