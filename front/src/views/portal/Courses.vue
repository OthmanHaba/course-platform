<template>
  <div class="courses-page">
    <div class="container">
      <header class="page-header">
        <h1>All Courses</h1>
      </header>

      <div class="filters">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search courses..."
          class="input search-input"
          @keyup.enter="searchCourses"
        />
        <select v-model="selectedLevel" @change="filterCourses" class="input">
          <option value="">All Levels</option>
          <option value="beginner">Beginner</option>
          <option value="intermediate">Intermediate</option>
          <option value="advanced">Advanced</option>
        </select>
      </div>

      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading courses...</p>
      </div>

      <div v-else-if="courses.length === 0" class="empty-state">
        <p class="text-muted">No courses found</p>
      </div>

      <div v-else class="courses-grid">
        <div
          v-for="course in courses"
          :key="course.id"
          class="course-card"
          @click="router.push(`/courses/${course.id}`)"
        >
          <img
            :src="course.thumbnail || 'https://via.placeholder.com/400x250'"
            :alt="course.title"
            class="course-image"
          />
          <div class="course-content">
            <h3 class="course-title">{{ course.title }}</h3>
            <p class="course-description">{{ course.short_description }}</p>
            <div class="course-footer">
              <span class="text-sm text-muted">
                {{ course.rating_average ? `★ ${course.rating_average}` : '—' }}
              </span>
              <span class="course-price">
                {{ course.is_free ? 'Free' : `$${course.price}` }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { portalService } from '@/services/portalService'

const router = useRouter()
const route = useRoute()

const courses = ref<any[]>([])
const loading = ref(true)
const searchQuery = ref((route.query.search as string) || '')
const selectedLevel = ref('')

onMounted(async () => {
  await fetchCourses()
})

async function fetchCourses() {
  try {
    const params: any = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (selectedLevel.value) params.level = selectedLevel.value

    const response = await portalService.getCourses(params)
    courses.value = response.data.data.courses
  } catch (error) {
    console.error('Failed to fetch courses:', error)
  } finally {
    loading.value = false
  }
}

function searchCourses() {
  loading.value = true
  fetchCourses()
}

function filterCourses() {
  loading.value = true
  fetchCourses()
}
</script>

<style scoped>
.courses-page {
  min-height: calc(100vh - 64px);
  padding: var(--spacing-2xl) 0;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
}

.page-header {
  margin-bottom: var(--spacing-2xl);
}

.page-header h1 {
  font-size: 1.875rem;
  font-weight: 600;
}

.filters {
  display: flex;
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-2xl);
}

.search-input {
  flex: 1;
}

.loading-state,
.empty-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: var(--spacing-xl);
}

.course-card {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  overflow: hidden;
  cursor: pointer;
  transition: all 0.2s;
}

.course-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.course-image {
  width: 100%;
  height: 180px;
  object-fit: cover;
  background: var(--color-background-alt);
}

.course-content {
  padding: var(--spacing-lg);
}

.course-title {
  font-size: 1.0625rem;
  font-weight: 600;
  margin-bottom: var(--spacing-sm);
  line-height: 1.4;
}

.course-description {
  font-size: 0.875rem;
  color: var(--color-text-muted);
  margin-bottom: var(--spacing-md);
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.5;
}

.course-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: var(--spacing-sm);
  border-top: 1px solid var(--color-border);
}

.course-price {
  font-weight: 600;
  font-size: 0.9375rem;
  color: var(--color-text);
}

@media (max-width: 768px) {
  .filters {
    flex-direction: column;
  }

  .courses-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: var(--spacing-lg);
  }
}
</style>
