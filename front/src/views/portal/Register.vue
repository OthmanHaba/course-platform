<template>
  <div class="register-page">
    <div class="register-card">
      <div class="register-header">
        <h1>Create Account</h1>
        <p class="text-muted text-sm">Join us today</p>
      </div>

      <form @submit.prevent="handleRegister" class="register-form">
        <div class="form-row">
          <div class="form-group">
            <label for="first_name" class="form-label">First Name</label>
            <input
              v-model="formData.first_name"
              type="text"
              id="first_name"
              required
              class="input"
              placeholder="John"
            />
          </div>

          <div class="form-group">
            <label for="last_name" class="form-label">Last Name</label>
            <input
              v-model="formData.last_name"
              type="text"
              id="last_name"
              required
              class="input"
              placeholder="Doe"
            />
          </div>
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input
            v-model="formData.email"
            type="email"
            id="email"
            required
            class="input"
            placeholder="you@example.com"
          />
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input
            v-model="formData.password"
            type="password"
            id="password"
            required
            minlength="6"
            class="input"
            placeholder="At least 6 characters"
          />
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <div v-if="success" class="success-message">
          {{ success }}
        </div>

        <button type="submit" :disabled="loading" class="btn btn-primary w-full">
          {{ loading ? 'Creating Account...' : 'Create Account' }}
        </button>

        <div class="register-footer">
          <router-link to="/login" class="link">
            Already have an account? Sign in
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { usePortalAuthStore } from '@/stores/portalAuth'

const router = useRouter()
const authStore = usePortalAuthStore()

const formData = ref({
  first_name: '',
  last_name: '',
  email: '',
  password: ''
})

const error = ref('')
const success = ref('')
const loading = ref(false)

async function handleRegister() {
  loading.value = true
  error.value = ''
  success.value = ''

  const result = await authStore.register(formData.value)

  if (result.success) {
    success.value = 'Registration successful! Redirecting...'
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } else {
    error.value = result.error || 'Registration failed'
  }

  loading.value = false
}
</script>

<style scoped>
.register-page {
  min-height: calc(100vh - 64px);
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--color-background-alt);
  padding: var(--spacing-2xl) var(--spacing-lg);
}

.register-card {
  width: 100%;
  max-width: 480px;
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: var(--spacing-2xl);
}

.register-header {
  text-align: center;
  margin-bottom: var(--spacing-2xl);
}

.register-header h1 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: var(--spacing-xs);
}

.register-form {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-lg);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--spacing-md);
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-sm);
}

.form-label {
  font-size: 0.9375rem;
  font-weight: 500;
  color: var(--color-text);
}

.error-message {
  padding: 0.75rem;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: var(--radius-md);
  color: var(--color-error);
  font-size: 0.875rem;
}

.success-message {
  padding: 0.75rem;
  background: #f0fdf4;
  border: 1px solid #bbf7d0;
  border-radius: var(--radius-md);
  color: var(--color-success);
  font-size: 0.875rem;
}

.w-full {
  width: 100%;
}

.register-footer {
  text-align: center;
  padding-top: var(--spacing-sm);
}

.link {
  font-size: 0.875rem;
  color: var(--color-text-muted);
}

.link:hover {
  color: var(--color-text);
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

@media (max-width: 640px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>
