import { createRouter, createWebHistory } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'
import { usePortalAuthStore } from '@/stores/portalAuth'

// Layouts
import AdminLayout from '@/layouts/AdminLayout.vue'
import PortalLayout from '@/layouts/PortalLayout.vue'

// Admin Views
import AdminLogin from '@/views/admin/Login.vue'
import AdminDashboard from '@/views/admin/Dashboard.vue'
import AdminCourses from '@/views/admin/Courses.vue'

// Portal Views
import PortalHome from '@/views/portal/Home.vue'
import PortalLogin from '@/views/portal/Login.vue'
import PortalRegister from '@/views/portal/Register.vue'
import CourseDetail from '@/views/portal/CourseDetail.vue'
import MyCourses from '@/views/portal/MyCourses.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        // Admin Routes
        {
            path: '/admin/login',
            name: 'AdminLogin',
            component: AdminLogin,
            meta: { requiresGuest: true, isAdmin: true }
        },
        {
            path: '/admin',
            component: AdminLayout,
            meta: { requiresAuth: true, isAdmin: true },
            children: [
                {
                    path: '',
                    redirect: '/admin/dashboard'
                },
                {
                    path: 'dashboard',
                    name: 'AdminDashboard',
                    component: AdminDashboard
                },
                {
                    path: 'courses',
                    name: 'AdminCourses',
                    component: AdminCourses
                },
                {
                    path: 'users',
                    name: 'AdminUsers',
                    component: () => import('@/views/admin/Users.vue')
                },
                {
                    path: 'enrollments',
                    name: 'AdminEnrollments',
                    component: () => import('@/views/admin/Enrollments.vue')
                },
                {
                    path: 'reviews',
                    name: 'AdminReviews',
                    component: () => import('@/views/admin/Reviews.vue')
                },
                {
                    path: 'categories',
                    name: 'AdminCategories',
                    component: () => import('@/views/admin/Categories.vue')
                }
            ]
        },

        // Portal Routes
        {
            path: '/login',
            name: 'Login',
            component: PortalLogin,
            meta: { requiresGuest: true }
        },
        {
            path: '/register',
            name: 'Register',
            component: PortalRegister,
            meta: { requiresGuest: true }
        },
        {
            path: '/',
            component: PortalLayout,
            children: [
                {
                    path: '',
                    name: 'Home',
                    component: PortalHome
                },
                {
                    path: 'courses',
                    name: 'Courses',
                    component: () => import('@/views/portal/Courses.vue')
                },
                {
                    path: 'courses/:id',
                    name: 'CourseDetail',
                    component: CourseDetail
                },
                {
                    path: 'my-courses',
                    name: 'MyCourses',
                    component: MyCourses,
                    meta: { requiresAuth: true }
                },
                {
                    path: 'learn/:courseId',
                    name: 'Learn',
                    component: () => import('@/views/portal/Learn.vue'),
                    meta: { requiresAuth: true }
                },
                {
                    path: 'profile',
                    name: 'Profile',
                    component: () => import('@/views/portal/Profile.vue'),
                    meta: { requiresAuth: true }
                },
                {
                    path: 'categories/:id',
                    name: 'CategoryCourses',
                    component: () => import('@/views/portal/CategoryCourses.vue')
                }
            ]
        }
    ]
})

// Navigation guards
router.beforeEach((to, from, next) => {
    const adminAuthStore = useAdminAuthStore()
    const portalAuthStore = usePortalAuthStore()

    const isAdmin = to.meta.isAdmin
    const requiresAuth = to.meta.requiresAuth
    const requiresGuest = to.meta.requiresGuest

    if (isAdmin) {
        // Admin routes
        if (requiresAuth && !adminAuthStore.isAuthenticated) {
            next('/admin/login')
        } else if (requiresGuest && adminAuthStore.isAuthenticated) {
            next('/admin/dashboard')
        } else {
            next()
        }
    } else {
        // Portal routes
        if (requiresAuth && !portalAuthStore.isAuthenticated) {
            next('/login')
        } else if (requiresGuest && portalAuthStore.isAuthenticated) {
            next('/')
        } else {
            next()
        }
    }
})

export default router
