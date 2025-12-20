<template>
  <div class="portal-layout">
    <!-- Header -->
    <header class="header">
      <nav class="nav-container">
        <router-link to="/" class="logo">
          LMS
        </router-link>

         <router-link to="/admin" class="logo">
          Admin
        </router-link>

        <div class="nav-links">
          <router-link to="/" class="nav-item">Home</router-link>
          <router-link to="/courses" class="nav-item">Courses</router-link>

          <template v-if="authStore.isAuthenticated">
            <router-link to="/my-courses" class="nav-item">My Courses</router-link>
            <router-link to="/profile" class="nav-item">Profile</router-link>
            <button @click="handleLogout" class="btn btn-ghost">
              Logout
            </button>
          </template>

          <template v-else>
            <router-link to="/login" class="btn btn-ghost">
              Login
            </router-link>
            <router-link to="/register" class="btn btn-primary">
              Sign Up
            </router-link>
          </template>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <router-view />
    </main>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-content">
        <p class="text-sm text-muted">2024 Learning Management System. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { usePortalAuthStore } from '@/stores/portalAuth'

const router = useRouter()
const authStore = usePortalAuthStore()

async function handleLogout() {
  await authStore.logout()
  router.push('/')
}
</script>

<style scoped>
.portal-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.header {
  background: var(--color-background);
  border-bottom: 1px solid var(--color-border);
  position: sticky;
  top: 0;
  z-index: 50;
}

.nav-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-text);
  letter-spacing: -0.025em;
}

.logo:hover {
  color: var(--color-primary);
}

.nav-links {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
}

.nav-item {
  padding: 0.5rem 0.875rem;
  color: var(--color-text-muted);
  font-size: 0.9375rem;
  font-weight: 500;
  transition: color 0.2s;
  border-radius: var(--radius-md);
}

.nav-item:hover {
  color: var(--color-text);
  background: var(--color-background-alt);
}

.main-content {
  flex: 1;
}

.footer {
  background: var(--color-background);
  border-top: 1px solid var(--color-border);
  padding: var(--spacing-2xl) 0;
  margin-top: var(--spacing-2xl);
}

.footer-content {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  text-align: center;
}

@media (max-width: 768px) {
  .nav-links {
    gap: var(--spacing-xs);
  }

  .nav-item {
    padding: 0.5rem;
    font-size: 0.875rem;
  }
}
</style>
