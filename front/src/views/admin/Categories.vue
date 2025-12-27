<template>
  <div class="categories-page">
    <header class="page-header">
      <h1>Categories</h1>
      <button @click="openCreateModal" class="btn btn-primary">
        Add Category
      </button>
    </header>

    <!-- Search -->
    <div class="search-bar">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search categories..."
        class="input search-input"
      />
    </div>

    <div class="categories-table-container">
      <div v-if="loading" class="loading-state">
        <p class="text-muted">Loading categories...</p>
      </div>

      <div v-else class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Slug</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="category in filteredCategories" :key="category.id">
              <td class="category-name">{{ category.name }}</td>
              <td class="text-muted">{{ category.slug }}</td>
              <td>
                <span class="badge" :class="category.is_active ? 'badge-active' : 'badge-inactive'">
                  {{ category.is_active ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td>
                <div class="action-buttons">
                  <button @click="openEditModal(category)" class="action-btn edit">
                    Edit
                  </button>
                  <button @click="toggleStatus(category)" class="action-btn">
                    {{ category.is_active ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button @click="deleteCategory(category.id)" class="action-btn delete">
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="filteredCategories.length === 0" class="empty-state">
          <p class="text-muted">No categories found</p>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div
      v-if="showModal"
      class="modal-overlay"
      @click="closeModal"
    >
      <div class="modal-content" @click.stop>
        <h2 class="modal-title">{{ editingCategory ? 'Edit Category' : 'Create Category' }}</h2>
        <form @submit.prevent="saveCategory">
          <div class="form-group">
            <label class="form-label">Name</label>
            <input
              v-model="categoryForm.name"
              type="text"
              required
              class="input"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Slug</label>
            <input
              v-model="categoryForm.slug"
              type="text"
              required
              class="input"
            />
          </div>
          <div class="form-group">
            <label class="checkbox-label">
              <input type="checkbox" v-model="categoryForm.is_active" />
              Active
            </label>
          </div>
          <div class="modal-actions">
            <button type="submit" :disabled="saving" class="btn btn-primary">
              {{ saving ? 'Saving...' : (editingCategory ? 'Update' : 'Create') }}
            </button>
            <button
              type="button"
              @click="closeModal"
              class="btn btn-outline"
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

<style scoped>
.categories-page {
  max-width: 1400px;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: var(--spacing-xl, 1.5rem);
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 600;
}

.search-bar {
  margin-bottom: var(--spacing-lg, 1.5rem);
}

.search-input {
  max-width: 300px;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.action-btn.edit {
  color: var(--color-primary, #4f46e5);
}

.action-btn.edit:hover {
  background: #eef2ff;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
}

.checkbox-label input {
  width: 1rem;
  height: 1rem;
}

.categories-table-container {
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

.category-name {
  font-weight: 500;
}

.badge-active {
  background: #d1fae5;
  color: #065f46;
}

.badge-inactive {
  background: #fee2e2;
  color: #991b1b;
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

.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100;
  padding: var(--spacing-lg);
}

.modal-content {
  background: var(--color-background);
  border-radius: var(--radius-lg);
  padding: var(--spacing-2xl);
  max-width: 500px;
  width: 100%;
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: var(--spacing-xl);
}

.form-group {
  margin-bottom: var(--spacing-lg);
}

.form-label {
  display: block;
  font-size: 0.9375rem;
  font-weight: 500;
  color: var(--color-text);
  margin-bottom: var(--spacing-sm);
}

.modal-actions {
  display: flex;
  gap: var(--spacing-md);
  margin-top: var(--spacing-xl);
}

.modal-actions .btn {
  flex: 1;
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: var(--spacing-md);
  }

  .data-table th,
  .data-table td {
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
  }
}
</style>
