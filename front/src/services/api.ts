import axios from 'axios'

const API_BASE_URL = 'http://localhost:8080' // import.meta.env.VITE_API_BASE_URL || ''

// Create axios instances for admin and portal
export const adminApi = axios.create({
    baseURL: `${API_BASE_URL}/admin`,
    headers: {
        'Content-Type': 'application/json'
    }
})

export const portalApi = axios.create({
    baseURL: `${API_BASE_URL}/portal`,
    headers: {
        'Content-Type': 'application/json',
        'Allow-Origin': '*'
    }
})

// Request interceptor for admin API
adminApi.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('admin_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Request interceptor for portal API
portalApi.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('portal_token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)

// Response interceptor for admin API
adminApi.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear all admin auth data
            localStorage.removeItem('admin_token')
            localStorage.removeItem('admin_user')
            // Redirect to login
            window.location.href = '/admin/login'
        }
        return Promise.reject(error)
    }
)

// Response interceptor for portal API
portalApi.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Clear all portal auth data
            localStorage.removeItem('portal_token')
            localStorage.removeItem('portal_user')
            // Redirect to login
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)
