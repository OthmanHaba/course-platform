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
                  'p-2 cursor-pointer rounded text-sm flex items-center': true,
                  'bg-blue-100 text-blue-700': currentLesson?.id === lesson.id,
                  'hover:bg-gray-100': currentLesson?.id !== lesson.id
                }"
              >
                <span v-if="lesson.is_completed" class="text-green-600 mr-1">✓</span>
                <span v-if="lesson.content_type === 'quiz'" class="mr-1">📝</span>
                {{ lesson.title }}
              </div>
            </div>
          </div>
        </div>

        <!-- Notes Sidebar -->
        <div class="bg-white rounded-lg shadow p-4 mt-4">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-lg">Notes</h2>
            <button
              v-if="currentLesson && !showNoteForm"
              @click="showNoteForm = true"
              class="text-sm text-blue-600 hover:text-blue-700"
            >
              + Add Note
            </button>
          </div>

          <!-- Add Note Form -->
          <div v-if="showNoteForm" class="mb-4">
            <textarea
              v-model="newNoteContent"
              placeholder="Write your note..."
              class="w-full p-2 border rounded text-sm resize-none"
              rows="3"
            ></textarea>
            <div class="flex gap-2 mt-2">
              <button
                @click="saveNote"
                :disabled="savingNote"
                class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50"
              >
                {{ savingNote ? 'Saving...' : 'Save' }}
              </button>
              <button
                @click="showNoteForm = false; newNoteContent = ''"
                class="px-3 py-1 border text-sm rounded hover:bg-gray-50"
              >
                Cancel
              </button>
            </div>
          </div>

          <!-- Notes List -->
          <div v-if="loadingNotes" class="text-sm text-gray-500">Loading notes...</div>
          <div v-else-if="notes.length === 0" class="text-sm text-gray-500">No notes yet</div>
          <div v-else class="space-y-3 max-h-64 overflow-y-auto">
            <div v-for="note in notes" :key="note.id" class="p-2 bg-gray-50 rounded text-sm">
              <div v-if="editingNoteId === note.id">
                <textarea
                  v-model="editNoteContent"
                  class="w-full p-2 border rounded text-sm resize-none"
                  rows="2"
                ></textarea>
                <div class="flex gap-2 mt-2">
                  <button
                    @click="updateNote(note.id)"
                    class="px-2 py-1 bg-blue-600 text-white text-xs rounded"
                  >
                    Save
                  </button>
                  <button
                    @click="editingNoteId = null"
                    class="px-2 py-1 border text-xs rounded"
                  >
                    Cancel
                  </button>
                </div>
              </div>
              <div v-else>
                <p class="text-gray-700">{{ note.note_text }}</p>
                <div class="flex gap-2 mt-1">
                  <button
                    @click="editingNoteId = note.id; editNoteContent = note.note_text"
                    class="text-xs text-blue-600 hover:underline"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteNote(note.id)"
                    class="text-xs text-red-600 hover:underline"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Certificate Section (show when course is completed) -->
        <div v-if="courseCompleted" class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg shadow p-4 mt-4 text-white">
          <div class="flex items-center gap-3 mb-3">
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
              <h3 class="font-bold">Course Completed!</h3>
              <p class="text-sm text-green-100">Congratulations on finishing!</p>
            </div>
          </div>
          <button
            @click="router.push(`/certificate/${courseId}`)"
            class="w-full py-2 bg-white text-green-600 font-semibold rounded-lg hover:bg-green-50 transition-colors"
          >
            View Certificate
          </button>
        </div>
      </div>

      <!-- Lesson Content -->
      <div class="lg:col-span-3">
        <div v-if="loadingLesson" class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-t-blue-600"></div>
            <span class="ml-3 text-gray-600">Loading...</span>
          </div>
        </div>

        <div v-else-if="currentLesson" class="bg-white rounded-lg shadow p-6">
          <h1 class="text-2xl font-bold mb-4">{{ currentLesson.title }}</h1>

          <!-- Video Content -->
          <div v-if="currentLesson.content_type === 'video' && currentLesson.video_url">
            <iframe
              :src="currentLesson.video_url"
              controls
              class="w-full rounded mb-4"
            ></iframe>
          </div>

          <!-- Article Content -->
          <div
            v-if="currentLesson.content_type === 'article' && currentLesson.content"
            v-html="currentLesson.content"
            class="prose max-w-none mb-6"
          ></div>

          <!-- Quiz Content (show if lesson has a quiz) -->
          <div v-if="currentLesson.quiz_id" class="mb-6">
            <!-- Quiz Loading -->
            <div v-if="loadingQuiz" class="text-center py-8">
              <div class="animate-spin rounded-full h-8 w-8 border-4 border-blue-200 border-t-blue-600 mx-auto"></div>
              <p class="mt-3 text-gray-600">Loading quiz...</p>
            </div>

            <!-- Quiz Result -->
            <div v-else-if="quizResult" class="text-center py-8">
              <div :class="quizResult.is_passed ? 'text-green-600' : 'text-red-600'">
                <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                  <path v-if="quizResult.is_passed" fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  <path v-else fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <h2 class="text-2xl font-bold mt-4" :class="quizResult.is_passed ? 'text-green-600' : 'text-red-600'">
                {{ quizResult.is_passed ? 'Congratulations!' : 'Try Again' }}
              </h2>
              <p class="text-xl mt-2">Score: {{ quizResult.score }}%</p>
              <p class="text-gray-600 mt-1">
                {{ quizResult.earned_points }} / {{ quizResult.total_points }} points
              </p>
              <div class="mt-6 space-x-4">
                <button
                  @click="retakeQuiz"
                  class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                  Retake Quiz
                </button>
                <button
                  @click="loadNextLesson"
                  class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50"
                >
                  Next Lesson →
                </button>
              </div>
            </div>

            <!-- Quiz Questions -->
            <div v-else-if="quiz">
              <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800">{{ quiz.title }}</h3>
                <p v-if="quiz.description" class="text-blue-600 text-sm mt-1">{{ quiz.description }}</p>
                <div class="flex gap-4 mt-2 text-sm text-blue-700">
                  <span>{{ quiz.questions?.length || 0 }} questions</span>
                  <span v-if="quiz.passing_score">Passing score: {{ quiz.passing_score }}%</span>
                  <span v-if="quiz.time_limit">Time limit: {{ quiz.time_limit }} min</span>
                </div>
              </div>

              <div class="space-y-6">
                <div
                  v-for="(question, qIndex) in quiz.questions"
                  :key="question.id"
                  class="p-4 border rounded-lg"
                >
                  <p class="font-medium mb-3">
                    <span class="text-gray-500">{{ qIndex + 1 }}.</span>
                    {{ question.question_text }}
                    <span v-if="question.points" class="text-sm text-gray-500 ml-2">({{ question.points }} pts)</span>
                  </p>

                  <!-- Multiple Choice -->
                  <div v-if="question.question_type === 'multiple_choice'" class="space-y-2">
                    <label
                      v-for="(option, oIndex) in parseOptions(question.options)"
                      :key="oIndex"
                      class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50"
                      :class="{ 'border-blue-500 bg-blue-50': quizAnswers[question.id] === option }"
                    >
                      <input
                        type="radio"
                        :name="`question_${question.id}`"
                        :value="option"
                        v-model="quizAnswers[question.id]"
                        class="mr-3"
                      />
                      {{ option }}
                    </label>
                  </div>

                  <!-- True/False -->
                  <div v-else-if="question.question_type === 'true_false'" class="space-y-2">
                    <label
                      v-for="option in ['True', 'False']"
                      :key="option"
                      class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50"
                      :class="{ 'border-blue-500 bg-blue-50': quizAnswers[question.id] === option }"
                    >
                      <input
                        type="radio"
                        :name="`question_${question.id}`"
                        :value="option"
                        v-model="quizAnswers[question.id]"
                        class="mr-3"
                      />
                      {{ option }}
                    </label>
                  </div>

                  <!-- Short Answer -->
                  <div v-else-if="question.question_type === 'short_answer'">
                    <input
                      type="text"
                      v-model="quizAnswers[question.id]"
                      placeholder="Type your answer..."
                      class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                  </div>
                </div>
              </div>

              <div class="mt-6 flex justify-end">
                <button
                  @click="submitQuiz"
                  :disabled="submittingQuiz"
                  class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:opacity-50"
                >
                  {{ submittingQuiz ? 'Submitting...' : 'Submit Quiz' }}
                </button>
              </div>
            </div>

            <!-- No Quiz Found -->
            <div v-else class="text-center py-8 text-gray-500">
              <p>Quiz not available for this lesson.</p>
            </div>
          </div>

          <!-- File Content -->
          <div v-if="currentLesson.content_type === 'file' && currentLesson.file_url" class="mb-6">
            <a
              :href="currentLesson.file_url"
              target="_blank"
              class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Download File
            </a>
          </div>

          <!-- Lesson Actions (not for quiz) -->
          <div v-if="!currentLesson.quiz_id" class="flex justify-between items-center mt-6 pt-6 border-t">
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
import { ref, computed, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { portalService } from '@/services/portalService'

const route = useRoute()
const router = useRouter()
const courseId = Number(route.params.courseId)

const curriculum = ref<any[]>([])
const currentLesson = ref<any>(null)
const loadingLesson = ref(false)

// Check if all lessons are completed
const courseCompleted = computed(() => {
  if (curriculum.value.length === 0) return false
  let totalLessons = 0
  let completedLessons = 0

  curriculum.value.forEach(section => {
    section.lessons?.forEach((lesson: any) => {
      totalLessons++
      if (lesson.is_completed == 1 || lesson.is_completed === true) {
        completedLessons++
      }
    })
  })

  return totalLessons > 0 && completedLessons === totalLessons
})

// Quiz state
const quiz = ref<any>(null)
const loadingQuiz = ref(false)
const quizAnswers = ref<Record<number, string>>({})
const submittingQuiz = ref(false)
const quizResult = ref<any>(null)

// Notes state
const notes = ref<any[]>([])
const loadingNotes = ref(false)
const showNoteForm = ref(false)
const newNoteContent = ref('')
const savingNote = ref(false)
const editingNoteId = ref<number | null>(null)
const editNoteContent = ref('')

onMounted(async () => {
  await fetchCurriculum()
})

// Watch for lesson changes to load quiz and notes
watch(currentLesson, async (newLesson) => {
  if (newLesson) {
    if (newLesson.quiz_id) {
      await loadQuiz()
    }
    await fetchNotes()
  }
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
  quiz.value = null
  quizResult.value = null
  quizAnswers.value = {}

  try {
    const response = await portalService.getLesson(lessonId)
    currentLesson.value = response.data.data
  } catch (error) {
    console.error('Failed to load lesson:', error)
  } finally {
    loadingLesson.value = false
  }
}

async function loadQuiz() {
  if (!currentLesson.value?.quiz_id) return

  loadingQuiz.value = true
  try {
    const response = await portalService.getQuiz(currentLesson.value.quiz_id)
    const data = response.data.data
    // API returns { quiz: {...}, questions: [...], attempts: n }
    quiz.value = {
      ...data.quiz,
      questions: data.questions,
      attempts: data.attempts
    }
  } catch (error) {
    console.error('Failed to load quiz:', error)
  } finally {
    loadingQuiz.value = false
  }
}

function parseOptions(options: string | any[]): string[] {
  if (Array.isArray(options)) return options
  try {
    let parsed = JSON.parse(options)
    // Handle double-encoded JSON
    if (typeof parsed === 'string') {
      parsed = JSON.parse(parsed)
    }
    return Array.isArray(parsed) ? parsed : []
  } catch {
    return options ? String(options).split(',').map((o: string) => o.trim()) : []
  }
}

async function submitQuiz() {
  if (!quiz.value) return

  submittingQuiz.value = true
  try {
    const response = await portalService.submitQuiz(quiz.value.id, { answers: quizAnswers.value })
    quizResult.value = response.data.data

    // Mark lesson as complete if passed
    if (quizResult.value.is_passed) {
      await portalService.completeLesson(currentLesson.value.id)
      currentLesson.value.progress = { is_completed: 1 }
      await fetchCurriculum()
    }
  } catch (error: any) {
    console.error('Failed to submit quiz:', error)
    alert(error.response?.data?.message || 'Failed to submit quiz')
  } finally {
    submittingQuiz.value = false
  }
}

function retakeQuiz() {
  quizResult.value = null
  quizAnswers.value = {}
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

// Notes functions
async function fetchNotes() {
  if (!currentLesson.value) return

  loadingNotes.value = true
  try {
    const response = await portalService.getLessonNotes(currentLesson.value.id)
    notes.value = response.data.data || []
  } catch (error) {
    console.error('Failed to fetch notes:', error)
  } finally {
    loadingNotes.value = false
  }
}

async function saveNote() {
  if (!newNoteContent.value.trim() || !currentLesson.value) return

  savingNote.value = true
  try {
    await portalService.createNote(currentLesson.value.id, { note_text  : newNoteContent.value })
    newNoteContent.value = ''
    showNoteForm.value = false
    await fetchNotes()
  } catch (error) {
    console.error('Failed to save note:', error)
    alert('Failed to save note')
  } finally {
    savingNote.value = false
  }
}

async function updateNote(noteId: number) {
  if (!editNoteContent.value.trim()) return

  try {
    await portalService.updateNote(noteId, { note_text: editNoteContent.value })
    editingNoteId.value = null
    await fetchNotes()
  } catch (error) {
    console.error('Failed to update note:', error)
    alert('Failed to update note')
  }
}

async function deleteNote(noteId: number) {
  if (!confirm('Delete this note?')) return

  try {
    await portalService.deleteNote(noteId)
    await fetchNotes()
  } catch (error) {
    console.error('Failed to delete note:', error)
    alert('Failed to delete note')
  }
}
</script>
