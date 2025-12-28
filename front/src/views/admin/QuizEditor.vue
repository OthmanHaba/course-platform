<template>
  <div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-4">
        <button
          @click="goBack"
          class="text-gray-600 hover:text-gray-900 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
        </button>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">
            {{ isEditing ? 'Edit Quiz' : 'Create Quiz' }}
          </h1>
          <p class="text-sm text-gray-600">Lesson: {{ lessonTitle }}</p>
        </div>
      </div>
      <button
        @click="saveQuiz"
        :disabled="saving"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 transition-colors"
      >
        {{ saving ? 'Saving...' : 'Save Quiz' }}
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-200 border-t-indigo-600"></div>
    </div>

    <div v-else class="space-y-6">
      <!-- Quiz Settings -->
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quiz Settings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input
              v-model="quiz.title"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Quiz title"
            />
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="quiz.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Quiz description (optional)"
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pass Percentage</label>
            <input
              v-model.number="quiz.pass_percentage"
              type="number"
              min="0"
              max="100"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="70"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Time Limit (minutes)</label>
            <input
              v-model.number="quiz.time_limit"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="0 = No limit"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Max Attempts</label>
            <input
              v-model.number="quiz.max_attempts"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="0 = Unlimited"
            />
          </div>
          <div class="flex items-center gap-6">
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                v-model="quiz.shuffle_questions"
                type="checkbox"
                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              />
              <span class="text-sm text-gray-700">Shuffle Questions</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
              <input
                v-model="quiz.show_results"
                type="checkbox"
                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
              />
              <span class="text-sm text-gray-700">Show Results</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Questions -->
      <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Questions ({{ questions.length }})</h2>
          <button
            @click="addQuestion"
            class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors"
          >
            + Add Question
          </button>
        </div>

        <div v-if="questions.length === 0" class="text-center py-10 text-gray-500">
          No questions yet. Click "Add Question" to create one.
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="(question, index) in questions"
            :key="question.id || `new-${index}`"
            class="border border-gray-200 rounded-lg p-4"
          >
            <div class="flex items-start justify-between mb-3">
              <span class="text-sm font-medium text-gray-500">Question {{ index + 1 }}</span>
              <button
                @click="removeQuestion(index)"
                class="text-red-500 hover:text-red-700 transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Question Text</label>
                <textarea
                  v-model="question.question_text"
                  rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Enter your question"
                ></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Question Type</label>
                <select
                  v-model="question.question_type"
                  @change="onQuestionTypeChange(question)"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                >
                  <option value="multiple_choice">Multiple Choice</option>
                  <option value="true_false">True/False</option>
                  <option value="short_answer">Short Answer</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                <input
                  v-model.number="question.points"
                  type="number"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                />
              </div>

              <!-- Multiple Choice Options -->
              <div v-if="question.question_type === 'multiple_choice'" class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <div class="space-y-2">
                  <div
                    v-for="(option, optIndex) in question.options"
                    :key="optIndex"
                    class="flex items-center gap-2"
                  >
                    <input
                      type="radio"
                      :name="`correct-${index}`"
                      :checked="question.correct_answer === option"
                      @change="question.correct_answer = option"
                      class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <input
                      v-model="question.options[optIndex]"
                      type="text"
                      class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                      :placeholder="`Option ${optIndex + 1}`"
                    />
                    <button
                      v-if="question.options.length > 2"
                      @click="removeOption(question, optIndex)"
                      class="text-red-500 hover:text-red-700 p-1"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                      </svg>
                    </button>
                  </div>
                  <button
                    @click="addOption(question)"
                    class="text-sm text-indigo-600 hover:text-indigo-700"
                  >
                    + Add Option
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Select the radio button next to the correct answer</p>
              </div>

              <!-- True/False -->
              <div v-if="question.question_type === 'true_false'" class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="radio"
                      :name="`tf-${index}`"
                      value="true"
                      v-model="question.correct_answer"
                      class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <span>True</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input
                      type="radio"
                      :name="`tf-${index}`"
                      value="false"
                      v-model="question.correct_answer"
                      class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                    />
                    <span>False</span>
                  </label>
                </div>
              </div>

              <!-- Short Answer -->
              <div v-if="question.question_type === 'short_answer'" class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Correct Answer</label>
                <input
                  v-model="question.correct_answer"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Enter the correct answer"
                />
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Explanation (Optional)</label>
                <textarea
                  v-model="question.explanation"
                  rows="2"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Explanation shown after answering"
                ></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminService } from '@/services/adminService'

interface Question {
  id?: number
  quiz_id?: number
  question_text: string
  question_type: 'multiple_choice' | 'true_false' | 'short_answer'
  options: string[]
  correct_answer: string
  points: number
  explanation: string
  order_number: number
  _isNew?: boolean
  _isDeleted?: boolean
}

const route = useRoute()
const router = useRouter()

const lessonId = computed(() => Number(route.query.lessonId))
const quizId = computed(() => route.params.id ? Number(route.params.id) : null)
const isEditing = computed(() => !!quizId.value)
const lessonTitle = ref(route.query.lessonTitle as string || 'Unknown Lesson')

const loading = ref(false)
const saving = ref(false)

const quiz = ref({
  title: '',
  description: '',
  pass_percentage: 70,
  time_limit: 0,
  max_attempts: 0,
  shuffle_questions: false,
  show_results: true
})

const questions = ref<Question[]>([])
const deletedQuestionIds = ref<number[]>([])

onMounted(async () => {
  if (isEditing.value) {
    await fetchQuiz()
  }
})

async function fetchQuiz() {
  loading.value = true
  try {
    const response = await adminService.getQuiz(quizId.value!)
    const data = response.data.data

    quiz.value = {
      title: data.title || '',
      description: data.description || '',
      pass_percentage: data.pass_percentage || 70,
      time_limit: data.time_limit || 0,
      max_attempts: data.max_attempts || 0,
      shuffle_questions: !!data.shuffle_questions,
      show_results: data.show_results !== false
    }

    questions.value = (data.questions || []).map((q: any) => ({
      ...q,
      options: parseOptions(q.options),
      points: q.points || 1
    }))
  } catch (error) {
    console.error('Failed to fetch quiz:', error)
    alert('Failed to load quiz')
  } finally {
    loading.value = false
  }
}

function parseOptions(options: any): string[] {
  if (!options) return ['', '']
  if (Array.isArray(options)) return options
  try {
    let parsed = JSON.parse(options)
    // Handle double-encoded JSON
    if (typeof parsed === 'string') {
      parsed = JSON.parse(parsed)
    }
    return Array.isArray(parsed) ? parsed : ['', '']
  } catch {
    return ['', '']
  }
}

function addQuestion() {
  questions.value.push({
    question_text: '',
    question_type: 'multiple_choice',
    options: ['', '', '', ''],
    correct_answer: '',
    points: 1,
    explanation: '',
    order_number: questions.value.length,
    _isNew: true
  })
}

function removeQuestion(index: number) {
  const question = questions.value[index]
  if (question.id) {
    deletedQuestionIds.value.push(question.id)
  }
  questions.value.splice(index, 1)
}

function onQuestionTypeChange(question: Question) {
  if (question.question_type === 'multiple_choice') {
    question.options = ['', '', '', '']
    question.correct_answer = ''
  } else if (question.question_type === 'true_false') {
    question.options = []
    question.correct_answer = 'true'
  } else {
    question.options = []
    question.correct_answer = ''
  }
}

function addOption(question: Question) {
  question.options.push('')
}

function removeOption(question: Question, index: number) {
  const removedOption = question.options[index]
  question.options.splice(index, 1)
  if (question.correct_answer === removedOption) {
    question.correct_answer = ''
  }
}

async function saveQuiz() {
  if (!quiz.value.title.trim()) {
    alert('Please enter a quiz title')
    return
  }

  saving.value = true
  try {
    let savedQuizId = quizId.value

    // Save or create quiz
    if (isEditing.value) {
      await adminService.updateQuiz(quizId.value!, quiz.value)
    } else {
      const response = await adminService.createQuiz(lessonId.value, quiz.value)
      savedQuizId = response.data.data.id
    }

    // Delete removed questions
    for (const id of deletedQuestionIds.value) {
      await adminService.deleteQuestion(id)
    }

    // Save questions
    for (let i = 0; i < questions.value.length; i++) {
      const question = questions.value[i]
      const questionData = {
        question_text: question.question_text,
        question_type: question.question_type,
        options: question.question_type === 'multiple_choice' ? JSON.stringify(question.options.filter(o => o.trim())) : null,
        correct_answer: question.correct_answer,
        points: question.points,
        explanation: question.explanation,
        order_number: i
      }

      if (question._isNew || !question.id) {
        await adminService.addQuestion(savedQuizId!, questionData)
      } else {
        await adminService.updateQuestion(question.id, questionData)
      }
    }

    alert('Quiz saved successfully!')
    goBack()
  } catch (error: any) {
    console.error('Failed to save quiz:', error)
    alert(error.response?.data?.message || 'Failed to save quiz')
  } finally {
    saving.value = false
  }
}

function goBack() {
  const courseId = route.query.courseId
  if (courseId) {
    router.push(`/admin/courses/${courseId}`)
  } else {
    router.back()
  }
}
</script>
