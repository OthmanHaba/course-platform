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
          @keyup.enter="searchUsers"
        />
        <button @click="searchUsers" class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm hover:bg-gray-100 transition-colors">
          Search
        </button>
      </div>
      <select v-model="typeFilter" @change="searchUsers" class="px-3 py-2 border border-gray-300 rounded-lg text-sm min-w-[150px] focus:outline-none focus:ring-2 focus:ring-indigo-500">
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

      <!-- Pagination -->
      <div v-if="totalPages > 1" class="px-5 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-500">
          Showing {{ (currentPage - 1) * perPage + 1 }} to {{ Math.min(currentPage * perPage, totalItems) }} of {{ totalItems }} results
        </div>
        <div class="flex gap-2">
          <button
            @click="goToPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="px-3 py-1.5 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Previous
          </button>
          <template v-for="page in visiblePages" :key="page">
            <button
              v-if="page !== '...'"
              @click="goToPage(page)"
              :class="[
                'px-3 py-1.5 text-sm border rounded-md',
                currentPage === page
                  ? 'bg-indigo-600 text-white border-indigo-600'
                  : 'border-gray-300 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            <span v-else class="px-2 py-1.5 text-gray-500">...</span>
          </template>
          <button
            @click="goToPage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="px-3 py-1.5 text-sm border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const users = ref<any[]>([])
const loading = ref(true)
const searchQuery = ref('')
const typeFilter = ref('')

// Pagination
const currentPage = ref(1)
const totalPages = ref(1)
const totalItems = ref(0)
const perPage = ref(5)

const visiblePages = computed(() => {
  const pages: (number | string)[] = []
  const total = totalPages.value

  if (total <= 7) {
    for (let i = 1; i <= total; i++) pages.push(i)
  } else {
    if (currentPage.value <= 3) {
      pages.push(1, 2, 3, 4, '...', total)
    } else if (currentPage.value >= total - 2) {
      pages.push(1, '...', total - 3, total - 2, total - 1, total)
    } else {
      pages.push(1, '...', currentPage.value - 1, currentPage.value, currentPage.value + 1, '...', total)
    }
  }
  return pages
})

onMounted(async () => {
  await fetchUsers()
})

async function fetchUsers() {
  loading.value = true
  try {
    const params: any = { page: currentPage.value, per_page: perPage.value }
    if (searchQuery.value) params.search = searchQuery.value
    if (typeFilter.value) params.user_type = typeFilter.value

    const response = await adminService.getUsers(params)
    const data = response.data.data
    users.value = data.users || []
    const pagination = data.pagination || {}
    totalItems.value = pagination.total || 0
    totalPages.value = pagination.total_pages || 1
    currentPage.value = pagination.current_page || 1
  } catch (error) {
    console.error('Failed to fetch users:', error)
  } finally {
    loading.value = false
  }
}

function goToPage(page: number | string) {
  if (typeof page === 'number' && page >= 1 && page <= totalPages.value) {
    currentPage.value = page
    fetchUsers()
  }
}

function searchUsers() {
  currentPage.value = 1
  fetchUsers()
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
