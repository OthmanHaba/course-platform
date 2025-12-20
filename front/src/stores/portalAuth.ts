import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { portalService } from '@/services/portalService'

export const usePortalAuthStore = defineStore('portalAuth', () => {
  // Initialize from localStorage
  const storedUser = localStorage.getItem('portal_user')
  const user = ref<any>(storedUser ? JSON.parse(storedUser) : null)
  const token = ref<string | null>(localStorage.getItem('portal_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  async function register(data: any) {
    loading.value = true
    error.value = null

    try {
      const response = await portalService.register(data)
      return { success: true, data: response.data }
    } catch (err: any) {
      error.value = err.response?.data?.message || 'Registration failed'
      return { success: false, error: error.value }
    } finally {
      loading.value = false
    }
  }

  async function login(email: string, password: string) {
    loading.value = true
    error.value = null

    try {
      const response = await portalService.login(email, password)
      const { user: userData, token: authToken } = response.data.data

      user.value = userData
      token.value = authToken

      // Persist to localStorage
      localStorage.setItem('portal_token', authToken)
      localStorage.setItem('portal_user', JSON.stringify(userData))

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
      await portalService.logout()
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear state and localStorage
      user.value = null
      token.value = null
      localStorage.removeItem('portal_token')
      localStorage.removeItem('portal_user')
    }
  }

  // Clear auth state (for use by axios interceptor on 401)
  function clearAuth() {
    user.value = null
    token.value = null
    localStorage.removeItem('portal_token')
    localStorage.removeItem('portal_user')
  }

  async function fetchProfile() {
    if (!isAuthenticated.value) return

    try {
      const response = await portalService.getProfile()
      user.value = response.data.data
      // Update localStorage with fresh profile data
      localStorage.setItem('portal_user', JSON.stringify(response.data.data))
    } catch (err) {
      console.error('Fetch profile error:', err)
      // If unauthorized, clear auth
      if ((err as any)?.response?.status === 401) {
        clearAuth()
      }
    }
  }

  function setUser(userData: any) {
    user.value = userData
    if (userData) {
      localStorage.setItem('portal_user', JSON.stringify(userData))
    }
  }

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    register,
    login,
    logout,
    fetchProfile,
    setUser,
    clearAuth
  }
})
