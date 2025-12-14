<template>
  <div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Curriculum Sidebar -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-4 max-h-screen overflow-y-auto">
          <h2 class="font-bold text-lg mb-4">Course Content</h2>

          <div v-for="section in curriculum" :key="section.id" class="mb-4">
            <h3 class="font-semibold mb-2">{{ section.title }}</h3>
            <div class="space-y-1">
              <div
                v-for="lesson in section.lessons"
                :key="lesson.id"
                @click="loadLesson(lesson.id)"
                :class="{
                  'p-2 cursor-pointer rounded text-sm': true,
                  'bg-blue-100 text-blue-700': currentLesson?.id === lesson.id,
                  'hover:bg-gray-100': currentLesson?.id !== lesson.id
                }"
              >
                <span v-if="lesson.is_completed" class="text-green-600">✓ </span>
                {{ lesson.title }}
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Lesson Content -->
      <div class="lg:col-span-3">
        <div v-if="loadingLesson" class="bg-white rounded-lg shadow p-6">Loading...</div>

        <div v-else-if="currentLesson" class="bg-white rounded-lg shadow p-6">
          <h1 class="text-2xl font-bold mb-4">{{ currentLesson.title }}</h1>

          <!-- Video Content -->
          <div v-if="currentLesson.content_type === 'video' && currentLesson.video_url">
            <video
              :src="currentLesson.video_url"
              controls
              class="w-full rounded mb-4"
            ></video>
          </div>

          <!-- Article Content -->
          <div
            v-if="currentLesson.content"
            v-html="currentLesson.content"
            class="prose max-w-none mb-6"
          ></div>

          <div class="flex justify-between items-center">
            <button
              @click="completeLesson"
              :disabled="currentLesson.progress?.is_completed"
              class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
            >
              {{ currentLesson.progress?.is_completed ? 'Completed ✓' : 'Mark as Complete' }}
            </button>

            <button
              @click="loadNextLesson"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              Next Lesson →
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { portalService } from '@/services/portalService'

const route = useRoute()
const courseId = Number(route.params.courseId)

const curriculum = ref<any[]>([])
const currentLesson = ref<any>(null)
const loadingLesson = ref(false)

onMounted(async () => {
  await fetchCurriculum()
})

async function fetchCurriculum() {
  try {
    const response = await portalService.getCourseCurriculum(courseId)
    curriculum.value = response.data.data

    // Load first lesson
    if (curriculum.value.length > 0 && curriculum.value[0].lessons.length > 0) {
      await loadLesson(curriculum.value[0].lessons[0].id)
    }
  } catch (error) {
    console.error('Failed to fetch curriculum:', error)
  }
}

async function loadLesson(lessonId: number) {
  loadingLesson.value = true
  try {
    const response = await portalService.getLesson(lessonId)
    currentLesson.value = response.data.data
  } catch (error) {
    console.error('Failed to load lesson:', error)
  } finally {
    loadingLesson.value = false
  }
}

async function completeLesson() {
  if (!currentLesson.value) return

  try {
    await portalService.completeLesson(currentLesson.value.id)
    currentLesson.value.progress = { is_completed: 1 }
    await fetchCurriculum()
  } catch (error) {
    console.error('Failed to complete lesson:', error)
    alert('Failed to mark lesson as complete')
  }
}

async function loadNextLesson() {
  if (!currentLesson.value) return

  try {
    const response = await portalService.getNextLesson(currentLesson.value.id)
    if (response.data.data) {
      await loadLesson(response.data.data.id)
    } else {
      alert('Congratulations! You have completed all lessons.')
    }
  } catch (error) {
    console.error('Failed to load next lesson:', error)
  }
}
</script>
