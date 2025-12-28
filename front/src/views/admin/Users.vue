<template>
  <div class="max-w-7xl">
    <header class="mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Users</h1>
    </header>

    <!-- Filters -->
    <div class="flex gap-4 mb-6 flex-wrap">
      <div class="flex gap-2 flex-1 min-w-[200px]">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search users..."
          class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          @keyup.enter="fetchUsers"
        />
        <button @click="fetchUsers" class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition-colors">
          Search
        </button>
      </div>
      <select v-model="typeFilter" @change="fetchUsers" class="px-3 py-2 border border-gray-300 rounded-lg text-sm min-w-[150px] focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">All Types</option>
        <option value="admin">Admin</option>
        <option value="instructor">Instructor</option>
        <option value="student">Student</option>
      </select>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <p class="text-gray-500">Loading users...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ user.first_name }} {{ user.last_name }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ user.email }}</td>
              <td class="px-5 py-4">
                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                  {{ user.user_type }}
                </span>
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full"
                  :class="user.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                >
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="toggleUserStatus(user)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                    {{ user.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button @click="deleteUser(user.id)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="users.length === 0" class="p-8 text-center">
          <p class="text-gray-500">No users found</p>
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
const searchQuery = ref('')
const typeFilter = ref('')

onMounted(async () => {
  await fetchUsers()
})

async function fetchUsers() {
  loading.value = true
  try {
    const params: any = {}
    if (searchQuery.value) params.search = searchQuery.value
    if (typeFilter.value) params.user_type = typeFilter.value

    const response = await adminService.getUsers(params)
    users.value = response.data.data.users || []
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
