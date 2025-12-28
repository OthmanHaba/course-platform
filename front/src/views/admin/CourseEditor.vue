<template>
  <div class="max-w-6xl">
    <header class="flex justify-between items-center mb-8 flex-wrap gap-4">
      <div class="flex flex-col gap-2">
        <button @click="router.push('/admin/courses')" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
          ← Back to Courses
        </button>
        <h1 class="text-2xl font-semibold text-gray-900">{{ isEditing ? 'Edit Course' : 'Create Course' }}</h1>
      </div>
      <div class="flex gap-4">
        <button
          v-if="isEditing"
          @click="updateStatus"
          class="px-4 py-2 text-sm font-medium rounded-lg"
          :class="{
            'bg-amber-100 text-amber-700': course.status === 'draft',
            'bg-green-100 text-green-700': course.status === 'published',
            'bg-gray-100 text-gray-600': course.status === 'archived'
          }"
        >
          Status: {{ course.status }}
        </button>
        <button @click="saveCourse" :disabled="saving" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          {{ saving ? 'Saving...' : 'Save Course' }}
        </button>
      </div>
    </header>

    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-500">Loading...</p>
    </div>

    <div v-else class="space-y-6">
      <!-- Tabs -->
      <div class="flex gap-0 border-b border-gray-200">
        <button
          :class="[
            'px-6 py-3 font-medium text-sm border-b-2 transition-colors',
            activeTab === 'details' ? 'text-indigo-600 border-indigo-600' : 'text-gray-500 border-transparent hover:text-gray-700'
          ]"
          @click="activeTab = 'details'"
        >
          Course Details
        </button>
        <button
          :class="[
            'px-6 py-3 font-medium text-sm border-b-2 transition-colors',
            activeTab === 'curriculum' ? 'text-indigo-600 border-indigo-600' : 'text-gray-500 border-transparent hover:text-gray-700',
            !isEditing && 'opacity-50 cursor-not-allowed'
          ]"
          @click="activeTab = 'curriculum'"
          :disabled="!isEditing"
        >
          Curriculum
        </button>
      </div>

      <!-- Details Tab -->
      <div v-if="activeTab === 'details'" class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Course Title *</label>
            <input
              v-model="course.title"
              type="text"
              placeholder="Enter course title"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Slug *</label>
            <input
              v-model="course.slug"
              type="text"
              placeholder="course-url-slug"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Short Description</label>
            <textarea
              v-model="course.short_description"
              rows="2"
              placeholder="Brief description for course cards"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Full Description</label>
            <textarea
              v-model="course.description"
              rows="5"
              placeholder="Detailed course description (HTML supported)"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select v-model="course.category_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Instructor</label>
            <select v-model="course.instructor_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">Select Instructor</option>
              <option v-for="inst in instructors" :key="inst.id" :value="inst.id">
                {{ inst.first_name }} {{ inst.last_name }}
              </option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Level</label>
            <select v-model="course.level" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
              <option value="">Select Level</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Price ($)</label>
            <input
              v-model.number="course.price"
              type="number"
              min="0"
              step="0.01"
              placeholder="0.00"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="course.is_free" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Free Course</span>
            </label>
          </div>

          <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="course.is_featured" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Featured Course</span>
            </label>
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Thumbnail URL</label>
            <input
              v-model="course.thumbnail"
              type="text"
              placeholder="https://example.com/image.jpg"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Learning Objectives</label>
            <textarea
              v-model="course.objectives"
              rows="4"
              placeholder="What students will learn (HTML supported)"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>

          <div class="md:col-span-2 space-y-2">
            <label class="block text-sm font-medium text-gray-700">Requirements</label>
            <textarea
              v-model="course.requirements"
              rows="4"
              placeholder="Prerequisites for the course (HTML supported)"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Curriculum Tab -->
      <div v-if="activeTab === 'curriculum' && isEditing" class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg font-semibold text-gray-900">Course Sections</h2>
          <button @click="showAddSection = true" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            + Add Section
          </button>
        </div>

        <!-- Add Section Form -->
        <div v-if="showAddSection" class="bg-gray-50 p-4 rounded-lg mb-4 flex gap-4 items-center flex-wrap">
          <input
            v-model="newSection.title"
            type="text"
            placeholder="Section Title"
            class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          />
          <div class="flex gap-2">
            <button @click="addSection" :disabled="!newSection.title" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
              Add Section
            </button>
            <button @click="showAddSection = false; newSection.title = ''" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
              Cancel
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="sections.length === 0" class="text-center py-8">
          <p class="text-gray-500">No sections yet. Add your first section to get started.</p>
        </div>

        <!-- Sections List -->
        <div v-else class="space-y-4">
          <div v-for="(section, sIndex) in sections" :key="section.id" class="border border-gray-200 rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-gray-50">
              <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500 uppercase">Section {{ sIndex + 1 }}</span>
                <input
                  v-if="editingSection === section.id"
                  v-model="section.title"
                  class="px-2 py-1 border border-gray-300 rounded text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  @blur="updateSection(section)"
                  @keyup.enter="updateSection(section)"
                />
                <h3 v-else @click="editingSection = section.id" class="text-sm font-semibold text-gray-900 cursor-pointer hover:text-indigo-600">
                  {{ section.title }}
                </h3>
              </div>
              <div class="flex gap-2">
                <button @click="toggleLessons(section.id)" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded transition-colors">
                  {{ expandedSections.includes(section.id) ? '▼' : '►' }} {{ section.lessons?.length || 0 }} lessons
                </button>
                <button @click="deleteSection(section.id)" class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded transition-colors">
                  Delete
                </button>
              </div>
            </div>

            <!-- Lessons -->
            <div v-if="expandedSections.includes(section.id)" class="p-4 bg-white">
              <div class="mb-4">
                <button @click="startAddLesson(section.id)" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition-colors">
                  + Add Lesson
                </button>
              </div>

              <!-- Add Lesson Form -->
              <div v-if="addingLessonTo === section.id" class="bg-gray-50 p-4 rounded-lg mb-4 flex gap-4 items-center flex-wrap">
                <input
                  v-model="newLesson.title"
                  type="text"
                  placeholder="Lesson Title"
                  class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <select v-model="newLesson.content_type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                  <option value="video">Video</option>
                  <option value="article">Article</option>
                  <option value="quiz">Quiz</option>
                  <option value="file">File</option>
                </select>
                <div class="flex gap-2">
                  <button @click="addLesson(section.id)" :disabled="!newLesson.title" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition-colors disabled:opacity-50">
                    Add Lesson
                  </button>
                  <button @click="addingLessonTo = null" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition-colors">
                    Cancel
                  </button>
                </div>
              </div>

              <!-- Lessons List -->
              <div class="space-y-2">
                <div v-for="(lesson, lIndex) in section.lessons" :key="lesson.id" class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                  <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 font-mono">{{ sIndex + 1 }}.{{ lIndex + 1 }}</span>
                    <span
                      class="text-[10px] px-2 py-0.5 rounded-full font-semibold uppercase"
                      :class="{
                        'bg-blue-100 text-blue-700': lesson.content_type === 'video',
                        'bg-green-100 text-green-700': lesson.content_type === 'article',
                        'bg-amber-100 text-amber-700': lesson.content_type === 'quiz',
                        'bg-gray-100 text-gray-600': lesson.content_type === 'file'
                      }"
                    >
                      {{ lesson.content_type }}
                    </span>
                    <span class="text-sm text-gray-900">{{ lesson.title }}</span>
                  </div>
                  <div class="flex gap-2">
                    <button @click="editLesson(lesson)" class="px-2 py-1 text-xs text-indigo-600 hover:bg-indigo-50 rounded transition-colors">
                      Edit
                    </button>
                    <button @click="deleteLesson(section.id, lesson.id)" class="px-2 py-1 text-xs text-red-600 hover:bg-red-50 rounded transition-colors">
                      Delete
                    </button>
                  </div>
                </div>
              </div>

              <div v-if="!section.lessons?.length" class="text-center py-4">
                <p class="text-sm text-gray-500">No lessons in this section</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lesson Editor Modal -->
    <div v-if="editingLesson" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-6" @click.self="editingLesson = null">
      <div class="bg-white rounded-xl w-full max-w-xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Edit Lesson</h2>
          <button @click="editingLesson = null" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="p-6 space-y-4">
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Title</label>
            <input v-model="editingLesson.title" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Content Type</label>
            <select v-model="editingLesson.content_type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <option value="video">Video</option>
              <option value="article">Article</option>
              <option value="quiz">Quiz</option>
              <option value="file">File</option>
            </select>
          </div>
          <div v-if="editingLesson.content_type === 'video'" class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Video URL</label>
            <input v-model="editingLesson.video_url" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="https://..." />
          </div>
          <div v-if="editingLesson.content_type === 'article'" class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Content (HTML)</label>
            <textarea v-model="editingLesson.content" rows="8" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
          </div>
          <div v-if="editingLesson.content_type === 'file'" class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">File URL</label>
            <input v-model="editingLesson.file_url" type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="https://..." />
          </div>
          <div v-if="editingLesson.content_type === 'quiz'" class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Quiz ID</label>
            <input v-model.number="editingLesson.quiz_id" type="number" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
            <p class="text-xs text-gray-500">Create quizzes in the Quiz Builder and link them here</p>
          </div>
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
            <input v-model.number="editingLesson.duration" type="number" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
          </div>
          <div>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="editingLesson.is_preview" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Available as Preview</span>
            </label>
          </div>
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
          <button @click="saveLesson" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Save Changes
          </button>
          <button @click="editingLesson = null" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- Status Modal -->
    <div v-if="showStatusModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-6" @click.self="showStatusModal = false">
      <div class="bg-white rounded-xl w-full max-w-sm">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Update Status</h2>
          <button @click="showStatusModal = false" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            <label v-for="status in ['draft', 'published', 'archived']" :key="status" class="flex items-center gap-3 cursor-pointer">
              <input
                type="radio"
                :value="status"
                v-model="newStatus"
                name="status"
                class="w-4 h-4 text-indigo-600 focus:ring-indigo-500"
              />
              <span
                class="px-3 py-1.5 text-sm font-medium rounded-md capitalize"
                :class="{
                  'bg-amber-100 text-amber-700': status === 'draft',
                  'bg-green-100 text-green-700': status === 'published',
                  'bg-gray-100 text-gray-600': status === 'archived'
                }"
              >
                {{ status }}
              </span>
            </label>
          </div>
        </div>
        <div class="flex justify-end gap-3 p-6 border-t border-gray-200">
          <button @click="confirmStatusUpdate" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
            Update Status
          </button>
          <button @click="showStatusModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminService } from '@/services/adminService'

const route = useRoute()
const router = useRouter()

const courseId = computed(() => route.params.id ? Number(route.params.id) : null)
const isEditing = computed(() => !!courseId.value)

const loading = ref(true)
const saving = ref(false)
const activeTab = ref('details')

const course = reactive({
  title: '',
  slug: '',
  short_description: '',
  description: '',
  category_id: '',
  instructor_id: '',
  level: '',
  price: 0,
  is_free: false,
  is_featured: false,
  thumbnail: '',
  objectives: '',
  requirements: '',
  status: 'draft'
})

const categories = ref<any[]>([])
const instructors = ref<any[]>([])
const sections = ref<any[]>([])

// Section management
const showAddSection = ref(false)
const newSection = reactive({ title: '' })
const editingSection = ref<number | null>(null)
const expandedSections = ref<number[]>([])

// Lesson management
const addingLessonTo = ref<number | null>(null)
const newLesson = reactive({ title: '', content_type: 'video' })
const editingLesson = ref<any>(null)

// Status management
const showStatusModal = ref(false)
const newStatus = ref('draft')

onMounted(async () => {
  await loadData()
})

async function loadData() {
  try {
    // Load categories and instructors
    const [catResponse, usersResponse] = await Promise.all([
      adminService.getCategories(),
      adminService.getUsers({ user_type: 'instructor' })
    ])
    categories.value = catResponse.data.data || []
    instructors.value = usersResponse.data.data.users || []

    // Load course if editing
    if (courseId.value) {
      const courseResponse = await adminService.getCourse(courseId.value)
      const courseData = courseResponse.data.data
      Object.assign(course, courseData)
      sections.value = courseData.sections || []

      // Expand all sections by default
      expandedSections.value = sections.value.map((s: any) => s.id)
    }
  } catch (error) {
    console.error('Failed to load data:', error)
  } finally {
    loading.value = false
  }
}

async function saveCourse() {
  if (!course.title || !course.slug) {
    alert('Title and Slug are required')
    return
  }

  saving.value = true
  try {
    if (isEditing.value) {
      await adminService.updateCourse(courseId.value!, course)
      alert('Course updated successfully')
    } else {
      const response = await adminService.createCourse(course)
      const newCourseId = response.data.data.id
      alert('Course created successfully')
      router.push(`/admin/courses/${newCourseId}`)
    }
  } catch (error: any) {
    console.error('Failed to save course:', error)
    alert(error.response?.data?.message || 'Failed to save course')
  } finally {
    saving.value = false
  }
}

function updateStatus() {
  newStatus.value = course.status
  showStatusModal.value = true
}

async function confirmStatusUpdate() {
  try {
    await adminService.updateCourseStatus(courseId.value!, newStatus.value)
    course.status = newStatus.value
    showStatusModal.value = false
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to update status')
  }
}

// Section functions
function toggleLessons(sectionId: number) {
  const idx = expandedSections.value.indexOf(sectionId)
  if (idx === -1) {
    expandedSections.value.push(sectionId)
  } else {
    expandedSections.value.splice(idx, 1)
  }
}

async function addSection() {
  if (!newSection.title) return

  try {
    const response = await adminService.createSection(courseId.value!, {
      title: newSection.title,
      order_number: sections.value.length
    })
    sections.value.push({ ...response.data.data, lessons: [] })
    expandedSections.value.push(response.data.data.id)
    newSection.title = ''
    showAddSection.value = false
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to add section')
  }
}

async function updateSection(section: any) {
  try {
    await adminService.updateSection(section.id, { title: section.title })
    editingSection.value = null
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to update section')
  }
}

async function deleteSection(sectionId: number) {
  if (!confirm('Delete this section and all its lessons?')) return

  try {
    await adminService.deleteSection(sectionId)
    sections.value = sections.value.filter(s => s.id !== sectionId)
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to delete section')
  }
}

// Lesson functions
function startAddLesson(sectionId: number) {
  addingLessonTo.value = sectionId
  newLesson.title = ''
  newLesson.content_type = 'video'
}

async function addLesson(sectionId: number) {
  if (!newLesson.title) return

  try {
    const section = sections.value.find(s => s.id === sectionId)
    const response = await adminService.createLesson(sectionId, {
      title: newLesson.title,
      content_type: newLesson.content_type,
      order_number: section?.lessons?.length || 0
    })

    if (!section.lessons) section.lessons = []
    section.lessons.push(response.data.data)
    addingLessonTo.value = null
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to add lesson')
  }
}

function editLesson(lesson: any) {
  editingLesson.value = { ...lesson }
}

async function saveLesson() {
  if (!editingLesson.value) return

  try {
    await adminService.updateLesson(editingLesson.value.id, editingLesson.value)

    // Update local state
    for (const section of sections.value) {
      const lessonIdx = section.lessons?.findIndex((l: any) => l.id === editingLesson.value.id)
      if (lessonIdx !== undefined && lessonIdx !== -1) {
        section.lessons[lessonIdx] = { ...editingLesson.value }
        break
      }
    }

    editingLesson.value = null
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to update lesson')
  }
}

async function deleteLesson(sectionId: number, lessonId: number) {
  if (!confirm('Delete this lesson?')) return

  try {
    await adminService.deleteLesson(lessonId)
    const section = sections.value.find(s => s.id === sectionId)
    if (section) {
      section.lessons = section.lessons.filter((l: any) => l.id !== lessonId)
    }
  } catch (error: any) {
    alert(error.response?.data?.message || 'Failed to delete lesson')
  }
}
</script>
