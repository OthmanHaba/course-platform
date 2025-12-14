import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080'

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
        'Content-Type': 'application/json'
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
            localStorage.removeItem('admin_token')
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
            localStorage.removeItem('portal_token')
            window.location.href = '/login'
        }
        return Promise.reject(error)
    }
)
