<template>
  <div class="max-w-7xl">
    <header class="flex justify-between items-center mb-6 flex-wrap gap-4">
      <h1 class="text-2xl font-semibold text-gray-900">Categories</h1>
      <button @click="openCreateModal" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
        Add Category
      </button>
    </header>

    <!-- Search -->
    <div class="mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search categories..."
        class="w-full max-w-xs px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
      />
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center">
        <p class="text-gray-500">Loading categories...</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Slug</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="category in filteredCategories" :key="category.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ category.name }}</td>
              <td class="px-5 py-4 text-sm text-gray-500">{{ category.slug }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full"
                  :class="category.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                >
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <button @click="openEditModal(category)" class="px-3 py-1.5 text-sm font-medium text-indigo-600 hover:bg-indigo-50 rounded-md transition-colors">
                    Edit
                  </button>
                  <button @click="toggleStatus(category)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-md transition-colors">
                    {{ category.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button @click="deleteCategory(category.id)" class="px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-md transition-colors">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="filteredCategories.length === 0" class="p-8 text-center">
          <p class="text-gray-500">No categories found</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-6"
      @click="closeModal"
    >
      <div class="bg-white rounded-xl p-8 max-w-md w-full" @click.stop>
        <h2 class="text-xl font-semibold text-gray-900 mb-6">{{ editingCategory ? 'Edit Category' : 'Create Category' }}</h2>
        <form @submit.prevent="saveCategory" class="space-y-4">
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="categoryForm.name"
              type="text"
              required
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Slug</label>
            <input
              v-model="categoryForm.slug"
              type="text"
              required
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div>
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="categoryForm.is_active" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
              <span class="text-sm text-gray-700">Active</span>
            </label>
          </div>
          <div class="flex gap-3 mt-6">
            <button type="submit" :disabled="saving" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
              {{ saving ? 'Saving...' : (editingCategory ? 'Update' : 'Create') }}
            </button>
            <button
              type="button"
              @click="closeModal"
              class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
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
import { ref, computed, onMounted, reactive } from 'vue'
import { adminService } from '@/services/adminService'

const categories = ref<any[]>([])
const loading = ref(true)
const saving = ref(false)
const showModal = ref(false)
const editingCategory = ref<any>(null)
const searchQuery = ref('')

const categoryForm = reactive({
  name: '',
  slug: '',
  is_active: true
})

const filteredCategories = computed(() => {
  if (!searchQuery.value) return categories.value
  const query = searchQuery.value.toLowerCase()
  return categories.value.filter(
    c => c.name.toLowerCase().includes(query) || c.slug.toLowerCase().includes(query)
  )
})

onMounted(async () => {
  await fetchCategories()
})

async function fetchCategories() {
  try {
    const response = await adminService.getCategories()
    categories.value = response.data.data || []
  } catch (error) {
    console.error('Failed to fetch categories:', error)
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  editingCategory.value = null
  categoryForm.name = ''
  categoryForm.slug = ''
  categoryForm.is_active = true
  showModal.value = true
}

function openEditModal(category: any) {
  editingCategory.value = category
  categoryForm.name = category.name
  categoryForm.slug = category.slug
  categoryForm.is_active = category.is_active
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingCategory.value = null
}

async function saveCategory() {
  saving.value = true
  try {
    if (editingCategory.value) {
      await adminService.updateCategory(editingCategory.value.id, categoryForm)
    } else {
      await adminService.createCategory(categoryForm)
    }
    closeModal()
    await fetchCategories()
  } catch (error: any) {
    console.error('Failed to save category:', error)
    alert(error.response?.data?.message || 'Failed to save category')
  } finally {
    saving.value = false
  }
}

async function toggleStatus(category: any) {
  try {
    await adminService.updateCategory(category.id, {
      ...category,
      is_active: !category.is_active
    })
    await fetchCategories()
  } catch (error) {
    console.error('Failed to update status:', error)
    alert('Failed to update status')
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
