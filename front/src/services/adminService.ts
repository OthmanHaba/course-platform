import { adminApi } from './api'

export const adminService = {
  // Auth
  login: (email: string, password: string) =>
    adminApi.post('/auth/login', { email, password }),

  // Users
  getUsers: (params?: any) => adminApi.get('/users', { params }),
  getUser: (id: number) => adminApi.get(`/users/${id}`),
  updateUser: (id: number, data: any) => adminApi.put(`/users/${id}`, data),
  deleteUser: (id: number) => adminApi.delete(`/users/${id}`),

  // Courses
  getCourses: (params?: any) => adminApi.get('/courses', { params }),
  getCourse: (id: number) => adminApi.get(`/courses/${id}`),
  createCourse: (data: any) => adminApi.post('/courses', data),
  updateCourse: (id: number, data: any) => adminApi.put(`/courses/${id}`, data),
  deleteCourse: (id: number) => adminApi.delete(`/courses/${id}`),
  updateCourseStatus: (id: number, status: string) =>
    adminApi.put(`/courses/${id}/status`, { status }),

  // Sections
  createSection: (courseId: number, data: any) =>
    adminApi.post(`/courses/${courseId}/sections`, data),
  updateSection: (id: number, data: any) => adminApi.put(`/sections/${id}`, data),
  deleteSection: (id: number) => adminApi.delete(`/sections/${id}`),

  // Lessons
  createLesson: (sectionId: number, data: any) =>
    adminApi.post(`/sections/${sectionId}/lessons`, data),
  updateLesson: (id: number, data: any) => adminApi.put(`/lessons/${id}`, data),
  deleteLesson: (id: number) => adminApi.delete(`/lessons/${id}`),

  // Quizzes
  createQuiz: (lessonId: number, data: any) =>
    adminApi.post(`/lessons/${lessonId}/quizzes`, data),
  updateQuiz: (id: number, data: any) => adminApi.put(`/quizzes/${id}`, data),
  deleteQuiz: (id: number) => adminApi.delete(`/quizzes/${id}`),

  // Questions
  addQuestion: (quizId: number, data: any) =>
    adminApi.post(`/quizzes/${quizId}/questions`, data),
  updateQuestion: (id: number, data: any) => adminApi.put(`/questions/${id}`, data),
  deleteQuestion: (id: number) => adminApi.delete(`/questions/${id}`),

  // Categories
  getCategories: () => adminApi.get('/categories'),
  createCategory: (data: any) => adminApi.post('/categories', data),
  updateCategory: (id: number, data: any) => adminApi.put(`/categories/${id}`, data),
  deleteCategory: (id: number) => adminApi.delete(`/categories/${id}`),

  // Enrollments
  getEnrollments: (params?: any) => adminApi.get('/enrollments', { params }),
  createEnrollment: (data: any) => adminApi.post('/enrollments', data),
  deleteEnrollment: (id: number) => adminApi.delete(`/enrollments/${id}`),

  // Reviews
  getReviews: (params?: any) => adminApi.get('/reviews', { params }),
  approveReview: (id: number) => adminApi.put(`/reviews/${id}/approve`),
  deleteReview: (id: number) => adminApi.delete(`/reviews/${id}`),

  // Analytics
  getOverview: () => adminApi.get('/analytics/overview'),
  getUserAnalytics: () => adminApi.get('/analytics/users'),
  getCourseAnalytics: () => adminApi.get('/analytics/courses'),

  // Media
  uploadMedia: (formData: FormData) =>
    adminApi.post('/media/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
  deleteMedia: (id: number) => adminApi.delete(`/media/${id}`)
}
