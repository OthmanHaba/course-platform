import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { adminService } from '@/services/adminService'

export const useAdminAuthStore = defineStore('adminAuth', () => {
  const user = ref<any>(null)
  const token = ref<string | null>(localStorage.getItem('admin_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)

  async function login(email: string, password: string) {
    loading.value = true
    error.value = null

    try {
      const response = await adminService.login(email, password)
      const { user: userData, token: authToken } = response.data.data

      user.value = userData
      token.value = authToken
      localStorage.setItem('admin_token', authToken)

      return { success: true }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Login failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  function logout() {
    user.value = null
    token.value = null
    localStorage.removeItem('admin_token')
  }

  function setUser(userData: any) {
    user.value = userData
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    login,
    logout,
    setUser
  }
})
