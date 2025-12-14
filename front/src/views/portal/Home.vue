<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-blue-600 text-white py-20">
      <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Learn Anything, Anytime</h1>
        <p class="text-xl mb-8">Discover thousands of courses from expert instructors</p>
        <div class="max-w-2xl mx-auto">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search for courses..."
            @keyup.enter="searchCourses"
            class="w-full px-6 py-3 rounded-full text-gray-900"
          />
        </div>
      </div>
    </section>

    <!-- Featured Courses -->
    <section class="py-16 container mx-auto px-4">
      <h2 class="text-3xl font-bold mb-8">Featured Courses</h2>
      <div v-if="loadingFeatured" class="text-center">Loading...</div>
      <div v-else class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div
          v-for="course in featuredCourses"
          :key="course.id"
          class="bg-white rounded-lg shadow hover:shadow-lg transition cursor-pointer"
          @click="router.push(`/courses/${course.id}`)"
        >
          <img
            :src="course.thumbnail || 'https://via.placeholder.com/300x200'"
            :alt="course.title"
            class="w-full h-48 object-cover rounded-t-lg"
          />
          <div class="p-4">
            <h3 class="font-bold text-lg mb-2">{{ course.title }}</h3>
            <p class="text-gray-600 text-sm mb-2 line-clamp-2">
              {{ course.short_description }}
            </p>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-500">⭐ {{ course.rating_average || 'N/A' }}</span>
              <span class="font-bold text-blue-600">
                {{ course.is_free ? 'Free' : `$${course.price}` }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section class="bg-gray-100 py-16">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          <div
            v-for="category in categories"
            :key="category.id"
            class="bg-white p-6 rounded-lg shadow text-center cursor-pointer hover:shadow-lg transition"
            @click="router.push(`/categories/${category.id}`)"
          >
            <h3 class="font-semibold">{{ category.name }}</h3>
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
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
