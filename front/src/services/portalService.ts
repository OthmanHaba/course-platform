import { portalApi } from './api'

export const portalService = {
  // Auth
  register: (data: any) => portalApi.post('/auth/register', data),
  login: (email: string, password: string) =>
    portalApi.post('/auth/login', { email, password }),
  logout: () => portalApi.post('/auth/logout'),
  forgotPassword: (email: string) =>
    portalApi.post('/auth/forgot-password', { email }),
  resetPassword: (token: string, password: string) =>
    portalApi.post('/auth/reset-password', { token, password }),
  verifyEmail: (token: string) =>
    portalApi.get(`/auth/verify-email/${token}`),

  // Profile
  getProfile: () => portalApi.get('/profile'),
  updateProfile: (data: any) => portalApi.put('/profile', data),
  changePassword: (currentPassword: string, newPassword: string) =>
    portalApi.put('/profile/password', {
      current_password: currentPassword,
      new_password: newPassword
    }),
  uploadAvatar: (formData: FormData) =>
    portalApi.post('/profile/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),

  // Courses
  getCourses: (params?: any) => portalApi.get('/courses', { params }),
  getFeaturedCourses: () => portalApi.get('/courses/featured'),
  getPopularCourses: () => portalApi.get('/courses/popular'),
  getCourse: (id: number) => portalApi.get(`/courses/${id}`),
  getCoursePreview: (id: number) => portalApi.get(`/courses/${id}/preview`),

  // Categories
  getCategories: () => portalApi.get('/categories'),
  getCoursesByCategory: (categoryId: number, params?: any) =>
    portalApi.get(`/categories/${categoryId}/courses`, { params }),

  // Instructors
  getInstructor: (id: number) => portalApi.get(`/instructors/${id}`),

  // Enrollments
  getMyCourses: () => portalApi.get('/my-courses'),
  enrollCourse: (courseId: number) => portalApi.post(`/courses/${courseId}/enroll`),
  unenrollCourse: (courseId: number) => portalApi.delete(`/courses/${courseId}/unenroll`),
  getEnrollment: (id: number) => portalApi.get(`/enrollments/${id}`),

  // Learning
  getCourseCurriculum: (courseId: number) =>
    portalApi.get(`/courses/${courseId}/curriculum`),
  getLesson: (id: number) => portalApi.get(`/lessons/${id}`),
  completeLesson: (id: number) => portalApi.post(`/lessons/${id}/complete`),
  getNextLesson: (id: number) => portalApi.get(`/lessons/${id}/next`),
  getCourseProgress: (courseId: number) =>
    portalApi.get(`/courses/${courseId}/progress`),
  getOverallProgress: () => portalApi.get('/progress'),

  // Quizzes
  getQuiz: (id: number) => portalApi.get(`/quizzes/${id}`),
  submitQuiz: (id: number, data: any) => portalApi.post(`/quizzes/${id}/submit`, data),
  getQuizResult: (id: number) => portalApi.get(`/quizzes/${id}/result`),
  getQuizAttempts: (id: number) => portalApi.get(`/quizzes/${id}/attempts`),

  // Reviews
  createReview: (courseId: number, data: any) =>
    portalApi.post(`/courses/${courseId}/reviews`, data),
  updateReview: (id: number, data: any) => portalApi.put(`/reviews/${id}`, data),
  deleteReview: (id: number) => portalApi.delete(`/reviews/${id}`),
  getCourseReviews: (courseId: number, params?: any) =>
    portalApi.get(`/courses/${courseId}/reviews`, { params }),
  markReviewHelpful: (id: number) => portalApi.post(`/reviews/${id}/helpful`),

  // Wishlist
  getWishlist: () => portalApi.get('/wishlist'),
  addToWishlist: (courseId: number) => portalApi.post(`/wishlist/${courseId}`),
  removeFromWishlist: (courseId: number) =>
    portalApi.delete(`/wishlist/${courseId}`),

  // Notes
  getLessonNotes: (lessonId: number) => portalApi.get(`/lessons/${lessonId}/notes`),
  createNote: (lessonId: number, data: any) =>
    portalApi.post(`/lessons/${lessonId}/notes`, data),
  updateNote: (id: number, data: any) => portalApi.put(`/notes/${id}`, data),
  deleteNote: (id: number) => portalApi.delete(`/notes/${id}`)
}
