<template>
  <div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>Admin</h2>
      </div>

      <nav class="sidebar-nav">
        <router-link to="/admin/dashboard" class="nav-link" active-class="active">
          <span>Dashboard</span>
        </router-link>
        <router-link to="/admin/courses" class="nav-link" active-class="active">
          <span>Courses</span>
        </router-link>
        <router-link to="/admin/users" class="nav-link" active-class="active">
          <span>Users</span>
        </router-link>
        <router-link to="/admin/enrollments" class="nav-link" active-class="active">
          <span>Enrollments</span>
        </router-link>
        <router-link to="/admin/reviews" class="nav-link" active-class="active">
          <span>Reviews</span>
        </router-link>
        <router-link to="/admin/categories" class="nav-link" active-class="active">
          <span>Categories</span>
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <button @click="handleLogout" class="btn-logout">
          Logout
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAdminAuthStore } from '@/stores/adminAuth'

const router = useRouter()
const authStore = useAdminAuthStore()

function handleLogout() {
  authStore.logout()
  router.push('/admin/login')
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  height: 100vh;
  background: var(--color-background-alt);
}

.sidebar {
  width: 240px;
  background: var(--color-background);
  border-right: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
}

.sidebar-header {
  padding: var(--spacing-xl) var(--spacing-lg);
  border-bottom: 1px solid var(--color-border);
}

.sidebar-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  letter-spacing: -0.025em;
}

.sidebar-nav {
  flex: 1;
  padding: var(--spacing-lg) 0;
  overflow-y: auto;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.625rem var(--spacing-lg);
  color: var(--color-text-muted);
  font-size: 0.9375rem;
  font-weight: 500;
  transition: all 0.2s;
  border-left: 2px solid transparent;
  margin: 2px 0;
}

.nav-link:hover {
  color: var(--color-text);
  background: var(--color-background-alt);
}

.nav-link.active {
  color: var(--color-primary);
  background: var(--color-background-alt);
  border-left-color: var(--color-primary);
}

.sidebar-footer {
  padding: var(--spacing-lg);
  border-top: 1px solid var(--color-border);
}

.btn-logout {
  width: 100%;
  padding: 0.625rem 1rem;
  background: transparent;
  color: var(--color-text-muted);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-md);
  font-size: 0.9375rem;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-logout:hover {
  background: var(--color-background-alt);
  color: var(--color-text);
  border-color: var(--color-text-muted);
}

.main-content {
  flex: 1;
  overflow-y: auto;
  padding: var(--spacing-2xl);
}

@media (max-width: 768px) {
  .sidebar {
    width: 200px;
  }

  .main-content {
    padding: var(--spacing-lg);
  }
}
</style>
