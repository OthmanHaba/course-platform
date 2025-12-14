import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { portalService } from '@/services/portalService'

export const usePortalAuthStore = defineStore('portalAuth', () => {
  const user = ref<any>(null)
  const token = ref<string | null>(localStorage.getItem('portal_token'))
  const loading = ref(false)
  const error = ref<string | null>(null)

  const isAuthenticated = computed(() => !!token.value)

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
      localStorage.setItem('portal_token', authToken)

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
      user.value = null
      token.value = null
      localStorage.removeItem('portal_token')
    }
  }

  async function fetchProfile() {
    if (!isAuthenticated.value) return

    try {
      const response = await portalService.getProfile()
      user.value = response.data.data
    } catch (err) {
      console.error('Fetch profile error:', err)
    }
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
    register,
    login,
    logout,
    fetchProfile,
    setUser
  }
})
