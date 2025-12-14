<template>
  <div class="home-page">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Learn Anything, Anytime</h1>
        <p class="hero-subtitle">Discover courses from expert instructors</p>
        <div class="search-box">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for courses..."
            @keyup.enter="searchCourses"
            class="input search-input"
          />
        </div>
      </div>
    </section>

    <!-- Featured Courses -->
    <section class="section">
      <div class="container">
        <h2 class="section-title">Featured Courses</h2>
        <div v-if="loadingFeatured" class="loading-state">
          <p class="text-muted">Loading courses...</p>
        </div>
        <div v-else class="courses-grid">
          <div
            v-for="course in featuredCourses"
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
    </section>

    <!-- Categories -->
    <section class="section section-alt">
      <div class="container">
        <h2 class="section-title">Browse by Category</h2>
        <div class="categories-grid">
          <div
            v-for="category in categories"
            :key="category.id"
            class="category-card"
            @click="router.push(`/categories/${category.id}`)"
          >
            <h3>{{ category.name }}</h3>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'

const router = useRouter()

const searchQuery = ref('')
const featuredCourses = ref<any[]>([])
const categories = ref<any[]>([])
const loadingFeatured = ref(true)

onMounted(async () => {
  await Promise.all([fetchFeaturedCourses(), fetchCategories()])
})

async function fetchFeaturedCourses() {
  try {
    const response = await portalService.getFeaturedCourses()
    featuredCourses.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch featured courses:', error)
  } finally {
    loadingFeatured.value = false
  }
}

async function fetchCategories() {
  try {
    const response = await portalService.getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch categories:', error)
  }
}

function searchCourses() {
  if (searchQuery.value.trim()) {
    router.push({ path: '/courses', query: { search: searchQuery.value } })
  }
}
</script>

<style scoped>
.home-page {
  min-height: 100vh;
}

.hero {
  background: linear-gradient(135deg, #000000 0%, #374151 100%);
  color: white;
  padding: 5rem 1.5rem;
  text-align: center;
}

.hero-content {
  max-width: 800px;
  margin: 0 auto;
}

.hero h1 {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: var(--spacing-md);
  letter-spacing: -0.025em;
}

.hero-subtitle {
  font-size: 1.125rem;
  opacity: 0.9;
  margin-bottom: var(--spacing-2xl);
}

.search-box {
  max-width: 600px;
  margin: 0 auto;
}

.search-input {
  background: white;
  color: var(--color-text);
  font-size: 1rem;
  padding: 0.875rem 1.25rem;
}

.section {
  padding: 4rem 0;
}

.section-alt {
  background: var(--color-background-alt);
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
}

.section-title {
  font-size: 1.875rem;
  font-weight: 600;
  margin-bottom: var(--spacing-2xl);
}

.loading-state {
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

.categories-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: var(--spacing-md);
}

.category-card {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  padding: var(--spacing-xl);
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
}

.category-card:hover {
  background: var(--color-background-alt);
  border-color: var(--color-text-muted);
}

.category-card h3 {
  font-size: 0.9375rem;
  font-weight: 600;
}

@media (max-width: 768px) {
  .hero h1 {
    font-size: 2rem;
  }

  .hero-subtitle {
    font-size: 1rem;
  }

  .section {
    padding: 2rem 0;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .courses-grid {
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: var(--spacing-lg);
  }

  .categories-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
