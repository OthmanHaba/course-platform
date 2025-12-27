<template>
  <div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
        <p class="mt-2 text-gray-600">Manage your profile information and preferences</p>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="successMessage" class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
          </div>
          <div class="ml-auto pl-3">
            <button @click="successMessage = ''" class="text-green-500 hover:text-green-700">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div v-if="errorMessage" class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-red-800">{{ errorMessage }}</p>
          </div>
          <div class="ml-auto pl-3">
            <button @click="errorMessage = ''" class="text-red-500 hover:text-red-700">
              <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Profile & Password -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Profile Information Card -->
          <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
              <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
              <p class="mt-1 text-sm text-gray-600">Update your account details and profile information</p>
            </div>
            <form @submit.prevent="updateProfile" class="px-6 py-6 space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                    First Name
                  </label>
                  <input
                    v-model="profileData.first_name"
                    id="first_name"
                    type="text"
                    required
                    class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>
                <div>
                  <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Last Name
                  </label>
                  <input
                    v-model="profileData.last_name"
                    id="last_name"
                    type="text"
                    required
                    class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                  />
                </div>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  Email Address
                </label>
                <input
                  v-model="profileData.email"
                  id="email"
                  type="email"
                  disabled
                  class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                />
                <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
              </div>

              <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                  Bio
                </label>
                <textarea
                  v-model="profileData.bio"
                  id="bio"
                  rows="4"
                  class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                  placeholder="Tell us a bit about yourself..."
                ></textarea>
              </div>

              <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <button
                  type="submit"
                  :disabled="updating"
                  class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                >
                  <svg v-if="updating" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ updating ? 'Saving...' : 'Save Changes' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Change Password Card -->
          <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
              <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
              <p class="mt-1 text-sm text-gray-600">Ensure your account is using a strong password</p>
            </div>
            <form @submit.prevent="changePassword" class="px-6 py-6 space-y-6">
              <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                  Current Password
                </label>
                <input
                  v-model="passwordData.current_password"
                  id="current_password"
                  type="password"
                  required
                  class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
              </div>

              <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                  New Password
                </label>
                <input
                  v-model="passwordData.new_password"
                  id="new_password"
                  type="password"
                  required
                  minlength="6"
                  class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                />
                <p class="mt-1 text-xs text-gray-500">Must be at least 6 characters</p>
              </div>

              <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                <button
                  type="submit"
                  :disabled="changingPassword"
                  class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm"
                >
                  <svg v-if="changingPassword" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ changingPassword ? 'Updating...' : 'Update Password' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Column - Avatar -->
        <div class="lg:col-span-1">
          <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden sticky top-4">
            <div class="px-6 py-5 border-b border-gray-200">
              <h2 class="text-xl font-semibold text-gray-900">Profile Picture</h2>
              <p class="mt-1 text-sm text-gray-600">Upload a photo for your account</p>
            </div>
            <div class="px-6 py-6">
              <div class="flex flex-col items-center">
                <div class="relative mb-6">
                  <div v-if="profileData.avatar" class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-gray-100">
                    <img
                      :src="profileData.avatar"
                      alt="Avatar"
                      class="w-full h-full object-cover"
                    />
                  </div>
                  <div v-else class="w-32 h-32 rounded-full bg-indigo-100 flex items-center justify-center ring-4 ring-gray-100">
                    <span class="text-4xl font-bold text-indigo-600">
                      {{ profileData.first_name?.[0] || 'U' }}
                    </span>
                  </div>
                  <div v-if="uploadingAvatar" class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-white border-t-transparent"></div>
                  </div>
                </div>

                <input
                  type="file"
                  ref="avatarInput"
                  @change="uploadAvatar"
                  accept="image/*"
                  class="hidden"
                />
                <button
                  @click="avatarInput?.click()"
                  :disabled="uploadingAvatar"
                  class="w-full px-4 py-2.5 bg-white border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ uploadingAvatar ? 'Uploading...' : 'Change Picture' }}
                </button>
                <p class="mt-3 text-xs text-gray-500 text-center">JPG, GIF or PNG. Max size 2MB</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { portalService } from '@/services/portalService'
import { usePortalAuthStore } from '@/stores/portalAuth'

const authStore = usePortalAuthStore()

const profileData = ref({
  first_name: '',
  last_name: '',
  email: '',
  bio: '',
  avatar: ''
})

const passwordData = ref({
  current_password: '',
  new_password: ''
})

const updating = ref(false)
const changingPassword = ref(false)
const uploadingAvatar = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const avatarInput = ref<any>(null)

onMounted(async () => {
  await fetchProfile()
})

async function fetchProfile() {
  try {
    const response = await portalService.getProfile()
    profileData.value = response.data.data
  } catch (error) {
    console.error('Failed to fetch profile:', error)
    errorMessage.value = 'Failed to load profile'
  }
}

async function updateProfile() {
  updating.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await portalService.updateProfile({
      first_name: profileData.value.first_name,
      last_name: profileData.value.last_name,
      bio: profileData.value.bio
    })
    successMessage.value = 'Profile updated successfully!'

    // Auto-hide success message after 5 seconds
    setTimeout(() => {
      successMessage.value = ''
    }, 5000)
  } catch (error: any) {
    console.error('Failed to update profile:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to update profile'
  } finally {
    updating.value = false
  }
}

async function changePassword() {
  changingPassword.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    await portalService.changePassword(
      passwordData.value.current_password,
      passwordData.value.new_password
    )
    successMessage.value = 'Password changed successfully!'
    passwordData.value = { current_password: '', new_password: '' }

    // Auto-hide success message after 5 seconds
    setTimeout(() => {
      successMessage.value = ''
    }, 5000)
  } catch (error: any) {
    console.error('Failed to change password:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to change password'
  } finally {
    changingPassword.value = false
  }
}

async function uploadAvatar(event: any) {
  const file = event.target.files[0]
  if (!file) return

  // Validate file size (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    errorMessage.value = 'Image size must be less than 2MB'
    return
  }

  uploadingAvatar.value = true
  errorMessage.value = ''
  successMessage.value = ''

  const formData = new FormData()
  formData.append('avatar', file)

  try {
    await portalService.uploadAvatar(formData)
    await fetchProfile()
    successMessage.value = 'Profile picture updated successfully!'

    // Auto-hide success message after 5 seconds
    setTimeout(() => {
      successMessage.value = ''
    }, 5000)
  } catch (error: any) {
    console.error('Failed to upload avatar:', error)
    errorMessage.value = error.response?.data?.message || 'Failed to upload avatar'
  } finally {
    uploadingAvatar.value = false

    // Reset file input
    if (avatarInput.value) {
      avatarInput.value.value = ''
    }
  }
}
</script>
