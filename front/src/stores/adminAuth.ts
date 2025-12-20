import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { adminService } from '@/services/adminService'

export const useAdminAuthStore = defineStore('adminAuth', () => {
  // Initialize from localStorage
  const storedUser = localStorage.getItem('admin_user')
  const user = ref<any>(storedUser ? JSON.parse(storedUser) : null)
  const token = ref<string | null>(localStorage.getItem('admin_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  async function login(email: string, password: string) {
    loading.value = true
    error.value = null

    try {
      const response = await adminService.login(email, password)
      const { user: userData, token: authToken } = response.data.data

      user.value = userData
      token.value = authToken

      // Persist to localStorage
      localStorage.setItem('admin_token', authToken)
      localStorage.setItem('admin_user', JSON.stringify(userData))

      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await adminService.logout()
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear state and localStorage
      user.value = null
      token.value = null
      localStorage.removeItem('admin_token')
      localStorage.removeItem('admin_user')
    }
  }

  // Clear auth state (for use by axios interceptor on 401)
  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
  }

  function setUser(userData: any) {
    user.value = userData
    if (userData) {
      localStorage.setItem('admin_user', JSON.stringify(userData))
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    login,
    logout,
    setUser,
    clearAuth
  }
})
