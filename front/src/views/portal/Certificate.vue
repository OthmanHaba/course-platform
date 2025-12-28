<template>
  <div class="certificate-page bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
      <!-- Back Button -->
      <button @click="router.push('/my-courses')" class="mb-6 text-gray-600 hover:text-gray-900 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to My Courses
      </button>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600 mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading certificate...</p>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="text-center py-20 bg-white rounded-lg shadow-sm">
        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h2 class="mt-4 text-xl font-semibold text-gray-900">{{ error }}</h2>
        <router-link to="/my-courses" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
          View My Courses
        </router-link>
      </div>

      <!-- Certificate -->
      <div v-else class="space-y-6">
        <!-- Actions -->
        <div class="flex justify-end gap-4">
          <button @click="printCertificate" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print
          </button>
          <button @click="downloadCertificate" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Download
          </button>
        </div>

        <!-- Certificate Display -->
        <div id="certificate" class="certificate-container bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="certificate-inner p-8 md:p-12">
            <!-- Border Design -->
            <div class="certificate-border">
              <!-- Header -->
              <div class="text-center mb-8">
                <div class="text-indigo-600 text-sm font-semibold tracking-widest uppercase mb-2">
                  Certificate of Completion
                </div>
                <h1 class="text-3xl md:text-4xl font-serif text-gray-900">
                  Course Completion Certificate
                </h1>
              </div>

              <!-- Award Text -->
              <div class="text-center my-10">
                <p class="text-gray-600 text-lg mb-4">This is to certify that</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 border-b-2 border-indigo-200 pb-2 inline-block px-8">
                  {{ userName }}
                </h2>
                <p class="text-gray-600 text-lg mt-4">has successfully completed the course</p>
              </div>

              <!-- Course Name -->
              <div class="text-center my-8">
                <h3 class="text-xl md:text-2xl font-semibold text-indigo-600 mb-2">
                  {{ courseName }}
                </h3>
                <p class="text-gray-500">
                  Instructed by {{ instructorName }}
                </p>
              </div>

              <!-- Completion Details -->
              <div class="text-center my-8">
                <div class="inline-flex items-center gap-8 text-sm text-gray-500">
                  <div>
                    <span class="block text-gray-400">Completed On</span>
                    <span class="font-medium text-gray-700">{{ completionDate }}</span>
                  </div>
                  <div class="h-8 w-px bg-gray-300"></div>
                  <div>
                    <span class="block text-gray-400">Certificate ID</span>
                    <span class="font-medium text-gray-700">{{ certificateId }}</span>
                  </div>
                </div>
              </div>

              <!-- Signature Area -->
              <div class="mt-12 flex justify-center gap-16">
                <div class="text-center">
                  <div class="w-40 border-t-2 border-gray-400 pt-2">
                    <p class="text-sm font-medium text-gray-700">Platform Director</p>
                    <p class="text-xs text-gray-500">Course Platform</p>
                  </div>
                </div>
                <div class="text-center">
                  <div class="w-40 border-t-2 border-gray-400 pt-2">
                    <p class="text-sm font-medium text-gray-700">{{ instructorName }}</p>
                    <p class="text-xs text-gray-500">Course Instructor</p>
                  </div>
                </div>
              </div>

              <!-- Badge -->
              <div class="absolute top-8 right-8 w-20 h-20 md:w-24 md:h-24">
                <svg viewBox="0 0 100 100" class="text-indigo-600 opacity-20">
                  <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="3"/>
                  <path d="M50 15 L60 40 L87 40 L65 55 L75 80 L50 65 L25 80 L35 55 L13 40 L40 40 Z" fill="currentColor"/>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Share Section -->
        <div class="bg-white rounded-lg shadow-sm p-6">
          <h3 class="font-semibold text-gray-900 mb-4">Share Your Achievement</h3>
          <div class="flex gap-3">
            <button @click="shareLinkedIn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
              </svg>
              Share on LinkedIn
            </button>
            <button @click="copyLink" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
              </svg>
              Copy Link
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'
import { usePortalAuthStore } from '@/stores/portalAuth'

const route = useRoute()
const router = useRouter()
const authStore = usePortalAuthStore()

const courseId = Number(route.params.courseId)
const enrollment = ref<any>(null)
const course = ref<any>(null)
const loading = ref(true)
const error = ref('')

const userName = computed(() => {
  if (authStore.user) {
    return `${authStore.user.first_name || ''} ${authStore.user.last_name || ''}`.trim() || 'Student'
  }
  return 'Student'
})

const courseName = computed(() => course.value?.title || 'Course')
const instructorName = computed(() => {
  if (course.value?.instructor) {
    return `${course.value.instructor.first_name || ''} ${course.value.instructor.last_name || ''}`.trim()
  }
  return 'Instructor'
})

const completionDate = computed(() => {
  if (enrollment.value?.completion_date) {
    return new Date(enrollment.value.completion_date).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }
  return new Date().toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const certificateId = computed(() => {
  return `CERT-${courseId}-${authStore.user?.id || 0}-${Date.now().toString(36).toUpperCase()}`
})

onMounted(async () => {
  try {
    // Get course details
    const courseRes = await portalService.getCourse(courseId)
    course.value = courseRes.data.data

    // Check if user is enrolled and has completed
    const myCoursesRes = await portalService.getMyCourses()
    const myCourses = myCoursesRes.data.data || []
    enrollment.value = myCourses.find((c: any) => Number(c.course_id) === courseId || Number(c.id) === courseId)

    if (!enrollment.value) {
      error.value = 'You are not enrolled in this course'
    } else if (!enrollment.value.is_completed && enrollment.value.progress_percentage < 100) {
      error.value = 'Complete the course to get your certificate'
    }
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Failed to load certificate'
  } finally {
    loading.value = false
  }
})

function printCertificate() {
  window.print()
}

function downloadCertificate() {
  // Simple download by printing to PDF
  window.print()
}

function shareLinkedIn() {
  const text = `I just completed "${courseName.value}" on Course Platform! 🎓`
  const url = window.location.href
  window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}&title=${encodeURIComponent(text)}`, '_blank')
}

function copyLink() {
  navigator.clipboard.writeText(window.location.href)
  alert('Link copied to clipboard!')
}
</script>

<style scoped>
.certificate-container {
  aspect-ratio: 1.414;
  position: relative;
}

.certificate-inner {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.certificate-border {
  width: 100%;
  max-width: 700px;
  padding: 2rem;
  border: 3px solid #c7d2fe;
  border-radius: 0.5rem;
  position: relative;
  background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
}

@media print {
  .certificate-page > *:not(#certificate) {
    display: none !important;
  }

  .certificate-page {
    background: white !important;
    padding: 0 !important;
    min-height: auto !important;
  }

  #certificate {
    box-shadow: none !important;
    border-radius: 0 !important;
  }
}
</style>
