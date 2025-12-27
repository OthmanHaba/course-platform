<template>
  <div class="course-editor">
    <header class="page-header">
      <div class="header-content">
        <button @click="router.push('/admin/courses')" class="back-btn">
          ← Back to Courses
        </button>
        <h1>{{ isEditing ? 'Edit Course' : 'Create Course' }}</h1>
      </div>
      <div class="header-actions">
        <button
          v-if="isEditing"
          @click="updateStatus"
          class="status-btn"
          :class="`status-${course.status}`"
        >
          Status: {{ course.status }}
        </button>
        <button @click="saveCourse" :disabled="saving" class="save-btn">
          {{ saving ? 'Saving...' : 'Save Course' }}
        </button>
      </div>
    </header>

    <div v-if="loading" class="loading-state">
      <p>Loading...</p>
    </div>

    <div v-else class="editor-content">
      <!-- Course Details Tab -->
      <div class="tabs">
        <button
          :class="{ active: activeTab === 'details' }"
          @click="activeTab = 'details'"
        >
          Course Details
        </button>
        <button
          :class="{ active: activeTab === 'curriculum' }"
          @click="activeTab = 'curriculum'"
          :disabled="!isEditing"
        >
          Curriculum
        </button>
      </div>

      <!-- Details Tab -->
      <div v-if="activeTab === 'details'" class="tab-content">
        <div class="form-grid">
          <div class="form-group full-width">
            <label>Course Title *</label>
            <input
              v-model="course.title"
              type="text"
              placeholder="Enter course title"
              class="form-input"
            />
          </div>

          <div class="form-group full-width">
            <label>Slug *</label>
            <input
              v-model="course.slug"
              type="text"
              placeholder="course-url-slug"
              class="form-input"
            />
          </div>

          <div class="form-group full-width">
            <label>Short Description</label>
            <textarea
              v-model="course.short_description"
              rows="2"
              placeholder="Brief description for course cards"
              class="form-input"
            ></textarea>
          </div>

          <div class="form-group full-width">
            <label>Full Description</label>
            <textarea
              v-model="course.description"
              rows="5"
              placeholder="Detailed course description (HTML supported)"
              class="form-input"
            ></textarea>
          </div>

          <div class="form-group">
            <label>Category</label>
            <select v-model="course.category_id" class="form-input">
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Instructor</label>
            <select v-model="course.instructor_id" class="form-input">
              <option value="">Select Instructor</option>
              <option v-for="inst in instructors" :key="inst.id" :value="inst.id">
                {{ inst.first_name }} {{ inst.last_name }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label>Level</label>
            <select v-model="course.level" class="form-input">
              <option value="">Select Level</option>
              <option value="beginner">Beginner</option>
              <option value="intermediate">Intermediate</option>
              <option value="advanced">Advanced</option>
            </select>
          </div>

          <div class="form-group">
            <label>Price ($)</label>
            <input
              v-model.number="course.price"
              type="number"
              min="0"
              step="0.01"
              placeholder="0.00"
              class="form-input"
            />
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="course.is_free" />
              Free Course
            </label>
          </div>

          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="course.is_featured" />
              Featured Course
            </label>
          </div>

          <div class="form-group full-width">
            <label>Thumbnail URL</label>
            <input
              v-model="course.thumbnail"
              type="text"
              placeholder="https://example.com/image.jpg"
              class="form-input"
            />
          </div>

          <div class="form-group full-width">
            <label>Learning Objectives</label>
            <textarea
              v-model="course.objectives"
              rows="4"
              placeholder="What students will learn (HTML supported)"
              class="form-input"
            ></textarea>
          </div>

          <div class="form-group full-width">
            <label>Requirements</label>
            <textarea
              v-model="course.requirements"
              rows="4"
              placeholder="Prerequisites for the course (HTML supported)"
              class="form-input"
            ></textarea>
          </div>
        </div>
      </div>

      <!-- Curriculum Tab -->
      <div v-if="activeTab === 'curriculum' && isEditing" class="tab-content">
        <div class="curriculum-header">
          <h2>Course Sections</h2>
          <button @click="showAddSection = true" class="add-btn">
            + Add Section
          </button>
        </div>

        <!-- Add Section Form -->
        <div v-if="showAddSection" class="add-form">
          <input
            v-model="newSection.title"
            type="text"
            placeholder="Section Title"
            class="form-input"
          />
          <div class="form-actions">
            <button @click="addSection" :disabled="!newSection.title" class="save-btn">
              Add Section
            </button>
            <button @click="showAddSection = false; newSection.title = ''" class="cancel-btn">
              Cancel
            </button>
          </div>
        </div>

        <!-- Sections List -->
        <div v-if="sections.length === 0" class="empty-state">
          <p>No sections yet. Add your first section to get started.</p>
        </div>

        <div v-else class="sections-list">
          <div v-for="(section, sIndex) in sections" :key="section.id" class="section-card">
            <div class="section-header">
              <div class="section-info">
                <span class="section-number">Section {{ sIndex + 1 }}</span>
                <input
                  v-if="editingSection === section.id"
                  v-model="section.title"
                  class="form-input inline"
                  @blur="updateSection(section)"
                  @keyup.enter="updateSection(section)"
                />
                <h3 v-else @click="editingSection = section.id">{{ section.title }}</h3>
              </div>
              <div class="section-actions">
                <button @click="toggleLessons(section.id)" class="toggle-btn">
                  {{ expandedSections.includes(section.id) ? '▼' : '►' }}
                  {{ section.lessons?.length || 0 }} lessons
                </button>
                <button @click="deleteSection(section.id)" class="delete-btn">Delete</button>
              </div>
            </div>

            <!-- Lessons -->
            <div v-if="expandedSections.includes(section.id)" class="lessons-container">
              <div class="add-lesson-btn">
                <button @click="startAddLesson(section.id)" class="add-btn small">
                  + Add Lesson
                </button>
              </div>

              <!-- Add Lesson Form -->
              <div v-if="addingLessonTo === section.id" class="add-form nested">
                <input
                  v-model="newLesson.title"
                  type="text"
                  placeholder="Lesson Title"
                  class="form-input"
                />
                <select v-model="newLesson.content_type" class="form-input">
                  <option value="video">Video</option>
                  <option value="article">Article</option>
                  <option value="quiz">Quiz</option>
                  <option value="file">File</option>
                </select>
                <div class="form-actions">
                  <button @click="addLesson(section.id)" :disabled="!newLesson.title" class="save-btn small">
                    Add Lesson
                  </button>
                  <button @click="addingLessonTo = null" class="cancel-btn small">
                    Cancel
                  </button>
                </div>
              </div>

              <!-- Lessons List -->
              <div v-for="(lesson, lIndex) in section.lessons" :key="lesson.id" class="lesson-card">
                <div class="lesson-header">
                  <div class="lesson-info">
                    <span class="lesson-number">{{ sIndex + 1 }}.{{ lIndex + 1 }}</span>
                    <span class="lesson-type" :class="`type-${lesson.content_type}`">
                      {{ lesson.content_type }}
                    </span>
                    <span class="lesson-title">{{ lesson.title }}</span>
                  </div>
                  <div class="lesson-actions">
                    <button @click="editLesson(lesson)" class="edit-btn">Edit</button>
                    <button @click="deleteLesson(section.id, lesson.id)" class="delete-btn">Delete</button>
                  </div>
                </div>
              </div>

              <div v-if="!section.lessons?.length" class="empty-lessons">
                <p>No lessons in this section</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Lesson Editor Modal -->
    <div v-if="editingLesson" class="modal-overlay" @click.self="editingLesson = null">
      <div class="modal">
        <div class="modal-header">
          <h2>Edit Lesson</h2>
          <button @click="editingLesson = null" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Title</label>
            <input v-model="editingLesson.title" type="text" class="form-input" />
          </div>
          <div class="form-group">
            <label>Content Type</label>
            <select v-model="editingLesson.content_type" class="form-input">
              <option value="video">Video</option>
              <option value="article">Article</option>
              <option value="quiz">Quiz</option>
              <option value="file">File</option>
            </select>
          </div>
          <div v-if="editingLesson.content_type === 'video'" class="form-group">
            <label>Video URL</label>
            <input v-model="editingLesson.video_url" type="text" class="form-input" placeholder="https://..." />
          </div>
          <div v-if="editingLesson.content_type === 'article'" class="form-group">
            <label>Content (HTML)</label>
            <textarea v-model="editingLesson.content" rows="8" class="form-input"></textarea>
          </div>
          <div v-if="editingLesson.content_type === 'file'" class="form-group">
            <label>File URL</label>
            <input v-model="editingLesson.file_url" type="text" class="form-input" placeholder="https://..." />
          </div>
          <div v-if="editingLesson.content_type === 'quiz'" class="form-group">
            <label>Quiz ID</label>
            <input v-model.number="editingLesson.quiz_id" type="number" class="form-input" />
            <p class="hint">Create quizzes in the Quiz Builder and link them here</p>
          </div>
          <div class="form-group">
            <label>Duration (minutes)</label>
            <input v-model.number="editingLesson.duration" type="number" class="form-input" />
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="editingLesson.is_preview" />
              Available as Preview
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="saveLesson" class="save-btn">Save Changes</button>
          <button @click="editingLesson = null" class="cancel-btn">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Status Modal -->
    <div v-if="showStatusModal" class="modal-overlay" @click.self="showStatusModal = false">
      <div class="modal small">
        <div class="modal-header">
          <h2>Update Status</h2>
          <button @click="showStatusModal = false" class="close-btn">&times;</button>
        </div>
        <div class="modal-body">
          <div class="status-options">
            <label v-for="status in ['draft', 'published', 'archived']" :key="status" class="status-option">
              <input
                type="radio"
                :value="status"
                v-model="newStatus"
                name="status"
              />
              <span :class="`status-badge status-${status}`">{{ status }}</span>
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="confirmStatusUpdate" class="save-btn">Update Status</button>
          <button @click="showStatusModal = false" class="cancel-btn">Cancel</button>
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
      sort_order: sections.value.length
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
      sort_order: section?.lessons?.length || 0
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

<style scoped>
.course-editor {
  max-width: 1200px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.header-content {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.back-btn {
  color: var(--color-text-muted);
  font-size: 0.875rem;
  padding: 0;
  background: none;
}

.back-btn:hover {
  color: var(--color-text);
}

.header-actions {
  display: flex;
  gap: 1rem;
}

.status-btn {
  padding: 0.5rem 1rem;
  border-radius: var(--radius-md);
  font-size: 0.875rem;
  font-weight: 500;
}

.status-draft { background: #fef3c7; color: #92400e; }
.status-published { background: #d1fae5; color: #065f46; }
.status-archived { background: #e5e7eb; color: #6b7280; }

.save-btn {
  padding: 0.5rem 1.5rem;
  background: var(--color-primary);
  color: white;
  border-radius: var(--radius-md);
  font-weight: 500;
}

.save-btn:hover { opacity: 0.9; }
.save-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.cancel-btn {
  padding: 0.5rem 1rem;
  background: var(--color-background-alt);
  border-radius: var(--radius-md);
}

.loading-state {
  text-align: center;
  padding: 3rem;
}

.tabs {
  display: flex;
  gap: 0;
  border-bottom: 1px solid var(--color-border);
  margin-bottom: 1.5rem;
}

.tabs button {
  padding: 0.75rem 1.5rem;
  background: none;
  border-bottom: 2px solid transparent;
  color: var(--color-text-muted);
  font-weight: 500;
}

.tabs button.active {
  color: var(--color-primary);
  border-bottom-color: var(--color-primary);
}

.tabs button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.tab-content {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: 1.5rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-group label {
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--color-text);
}

.form-input {
  padding: 0.625rem 0.875rem;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  font-size: 0.9375rem;
  width: 100%;
}

.form-input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.checkbox-label input {
  width: 1.125rem;
  height: 1.125rem;
}

/* Curriculum styles */
.curriculum-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.curriculum-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
}

.add-btn {
  padding: 0.5rem 1rem;
  background: var(--color-primary);
  color: white;
  border-radius: var(--radius-md);
  font-size: 0.875rem;
}

.add-btn.small {
  padding: 0.375rem 0.75rem;
  font-size: 0.8125rem;
}

.add-form {
  background: var(--color-background-alt);
  padding: 1rem;
  border-radius: var(--radius-md);
  margin-bottom: 1rem;
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.add-form.nested {
  margin-left: 1rem;
  margin-right: 1rem;
}

.form-actions {
  display: flex;
  gap: 0.5rem;
}

.sections-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.section-card {
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  overflow: hidden;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: var(--color-background-alt);
}

.section-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.section-number {
  font-size: 0.75rem;
  color: var(--color-text-muted);
  text-transform: uppercase;
}

.section-info h3 {
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
}

.section-info h3:hover {
  color: var(--color-primary);
}

.section-info .form-input.inline {
  padding: 0.25rem 0.5rem;
  font-size: 1rem;
  font-weight: 600;
}

.section-actions {
  display: flex;
  gap: 0.5rem;
}

.toggle-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.8125rem;
  color: var(--color-text-muted);
  background: none;
}

.delete-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.8125rem;
  color: var(--color-error);
  background: none;
}

.delete-btn:hover {
  background: #fef2f2;
}

.lessons-container {
  padding: 1rem;
  background: var(--color-background);
}

.add-lesson-btn {
  margin-bottom: 1rem;
}

.lesson-card {
  padding: 0.75rem 1rem;
  border: 1px solid var(--color-border);
  border-radius: var(--radius-sm);
  margin-bottom: 0.5rem;
}

.lesson-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.lesson-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.lesson-number {
  font-size: 0.75rem;
  color: var(--color-text-muted);
  font-family: monospace;
}

.lesson-type {
  font-size: 0.6875rem;
  padding: 0.125rem 0.5rem;
  border-radius: 999px;
  text-transform: uppercase;
  font-weight: 600;
}

.type-video { background: #dbeafe; color: #1e40af; }
.type-article { background: #d1fae5; color: #065f46; }
.type-quiz { background: #fef3c7; color: #92400e; }
.type-file { background: #e5e7eb; color: #6b7280; }

.lesson-title {
  font-size: 0.9375rem;
}

.lesson-actions {
  display: flex;
  gap: 0.5rem;
}

.edit-btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.8125rem;
  color: var(--color-primary);
  background: none;
}

.empty-state, .empty-lessons {
  text-align: center;
  padding: 2rem;
  color: var(--color-text-muted);
}

/* Modal styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background: var(--color-background);
  border-radius: var(--radius-lg);
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal.small {
  max-width: 400px;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid var(--color-border);
}

.modal-header h2 {
  font-size: 1.125rem;
  font-weight: 600;
}

.close-btn {
  font-size: 1.5rem;
  color: var(--color-text-muted);
  background: none;
  padding: 0;
  line-height: 1;
}

.modal-body {
  padding: 1.5rem;
}

.modal-body .form-group {
  margin-bottom: 1rem;
}

.modal-body .hint {
  font-size: 0.75rem;
  color: var(--color-text-muted);
  margin-top: 0.25rem;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border-top: 1px solid var(--color-border);
}

.status-options {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.status-option {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
}

.status-badge {
  padding: 0.375rem 0.75rem;
  border-radius: var(--radius-sm);
  font-size: 0.875rem;
  font-weight: 500;
  text-transform: capitalize;
}

@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .header-actions {
    width: 100%;
    justify-content: space-between;
  }
}
</style>
