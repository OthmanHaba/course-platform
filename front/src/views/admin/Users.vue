<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Users Management</h1>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">Loading...</div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id">
              <td class="px-6 py-4">{{ user.first_name }} {{ user.last_name }}</td>
              <td class="px-6 py-4">{{ user.email }}</td>
              <td class="px-6 py-4">
                <span class="px-2 py-1 rounded text-sm bg-blue-100 text-blue-800">
                  {{ user.user_type }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span
                  :class="{
                    'px-2 py-1 rounded text-sm': true,
                    'bg-green-100 text-green-800': user.is_active,
                    'bg-red-100 text-red-800': !user.is_active
                  }"
                >
                  {{ user.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <button
                  @click="toggleUserStatus(user)"
                  class="text-blue-600 hover:underline mr-3"
                >
                  {{ user.is_active ? 'Deactivate' : 'Activate' }}
                </button>
                <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
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
  if (!confirm('Are you sure you want to delete this user?')) return

  try {
    await adminService.deleteUser(id)
    await fetchUsers()
  } catch (error) {
    console.error('Failed to delete user:', error)
    alert('Failed to delete user')
  }
}
</script>
