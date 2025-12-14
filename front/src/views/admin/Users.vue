<template>
  <div class="users-page">
    <header class="page-header">
      <h1>Users</h1>
    </header>

    <div class="users-table-container">
      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading users...</p>
      </div>

      <div v-else class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Type</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td class="user-name">{{ user.first_name }} {{ user.last_name }}</td>
              <td class="text-muted">{{ user.email }}</td>
              <td>
                <span class="badge badge-type">{{ user.user_type }}</span>
              </td>
              <td>
                <span class="badge" :class="user.is_active ? 'badge-active' : 'badge-inactive'">
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button @click="toggleUserStatus(user)" class="action-btn">
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button @click="deleteUser(user.id)" class="action-btn delete">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="users.length === 0" class="empty-state">
          <p class="text-muted">No users found</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const users = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  await fetchUsers()
})

async function fetchUsers() {
  try {
    const response = await adminService.getUsers()
    users.value = response.data.data.users
  } catch (error) {
    console.error('Failed to fetch users:', error)
  } finally {
    loading.value = false
  }
}

async function toggleUserStatus(user: any) {
  try {
    await adminService.updateUser(user.id, { is_active: user.is_active ? 0 : 1 })
    await fetchUsers()
  } catch (error) {
    console.error('Failed to update user:', error)
    alert('Failed to update user status')
  }
}

async function deleteUser(id: number) {
  if (!confirm('Delete this user?')) return

  try {
    await adminService.deleteUser(id)
    await fetchUsers()
  } catch (error) {
    console.error('Failed to delete user:', error)
    alert('Failed to delete user')
  }
}
</script>

<style scoped>
.users-page {
  max-width: 1400px;
}

.page-header {
  margin-bottom: var(--spacing-2xl);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.users-table-container {
  background: var(--color-background);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-lg);
  overflow: hidden;
}

.loading-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

.table-wrapper {
  overflow-x: auto;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table thead {
  background: var(--color-background-alt);
  border-bottom: 1px solid var(--color-border);
}

.data-table th {
  padding: 0.875rem 1.25rem;
  text-align: left;
  font-size: 0.8125rem;
  font-weight: 600;
  color: var(--color-text-muted);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.data-table tbody tr {
  border-bottom: 1px solid var(--color-border);
  transition: background-color 0.2s;
}

.data-table tbody tr:hover {
  background: var(--color-background-alt);
}

.data-table tbody tr:last-child {
  border-bottom: none;
}

.data-table td {
  padding: 1rem 1.25rem;
  font-size: 0.9375rem;
  color: var(--color-text);
}

.user-name {
  font-weight: 500;
}

.badge-type {
  background: #dbeafe;
  color: #1e40af;
}

.badge-active {
  background: #d1fae5;
  color: #065f46;
}

.badge-inactive {
  background: #fee2e2;
  color: #991b1b;
}

.action-buttons {
  display: flex;
  gap: var(--spacing-sm);
}

.action-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--color-text-muted);
  background: transparent;
  border-radius: var(--radius-sm);
  transition: all 0.2s;
}

.action-btn:hover {
  color: var(--color-text);
  background: var(--color-background-alt);
}

.action-btn.delete:hover {
  color: var(--color-error);
  background: #fef2f2;
}

.empty-state {
  padding: var(--spacing-2xl);
  text-align: center;
}

@media (max-width: 768px) {
  .data-table th,
  .data-table td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }

  .action-buttons {
    flex-direction: column;
    gap: 0.25rem;
  }
}
</style>
