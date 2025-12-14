<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Categories</h1>
      <button
        @click="showCreateModal = true"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Add Category
      </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center">Loading...</div>

      <div v-else>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="category in categories" :key="category.id">
              <td class="px-6 py-4">{{ category.name }}</td>
              <td class="px-6 py-4">{{ category.slug }}</td>
              <td class="px-6 py-4">
                <span :class="category.is_active ? 'text-green-600' : 'text-red-600'">
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <button @click="deleteCategory(category.id)" class="text-red-600 hover:underline">
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Modal -->
    <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
      @click="showCreateModal = false"
    >
      <div class="bg-white p-6 rounded-lg w-96" @click.stop>
        <h2 class="text-xl font-bold mb-4">Create Category</h2>
        <form @submit.prevent="createCategory">
          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Name</label>
            <input
              v-model="newCategory.name"
              type="text"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>
          <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Slug</label>
            <input
              v-model="newCategory.slug"
              type="text"
              required
              class="w-full px-3 py-2 border rounded"
            />
          </div>
          <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
            <button
              type="button"
              @click="showCreateModal = false"
              class="px-4 py-2 bg-gray-300 rounded"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { adminService } from '@/services/adminService'

const categories = ref<any[]>([])
const loading = ref(true)
const showCreateModal = ref(false)
const newCategory = ref({ name: '', slug: '' })

onMounted(async () => {
  await fetchCategories()
})

async function fetchCategories() {
  try {
    const response = await adminService.getCategories()
    categories.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch categories:', error)
  } finally {
    loading.value = false
  }
}

async function createCategory() {
  try {
    await adminService.createCategory(newCategory.value)
    showCreateModal.value = false
    newCategory.value = { name: '', slug: '' }
    await fetchCategories()
  } catch (error) {
    console.error('Failed to create category:', error)
    alert('Failed to create category')
  }
}

async function deleteCategory(id: number) {
  if (!confirm('Are you sure you want to delete this category?')) return

  try {
    await adminService.deleteCategory(id)
    await fetchCategories()
  } catch (error) {
    console.error('Failed to delete category:', error)
    alert('Failed to delete category')
  }
}
</script>
