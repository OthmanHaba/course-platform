<template>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-bold mb-4">Profile Information</h2>

          <form @submit.prevent="updateProfile">
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-sm font-bold mb-2">First Name</label>
                <input
                  v-model="profileData.first_name"
                  type="text"
                  class="w-full px-3 py-2 border rounded"
                />
              </div>
              <div>
                <label class="block text-sm font-bold mb-2">Last Name</label>
                <input
                  v-model="profileData.last_name"
                  type="text"
                  class="w-full px-3 py-2 border rounded"
                />
              </div>
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Email</label>
              <input
                v-model="profileData.email"
                type="email"
                disabled
                class="w-full px-3 py-2 border rounded bg-gray-100"
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Bio</label>
              <textarea
                v-model="profileData.bio"
                rows="4"
                class="w-full px-3 py-2 border rounded"
              ></textarea>
            </div>

            <button
              type="submit"
              :disabled="updating"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              {{ updating ? 'Updating...' : 'Update Profile' }}
            </button>
          </form>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mt-6">
          <h2 class="text-xl font-bold mb-4">Change Password</h2>

          <form @submit.prevent="changePassword">
            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">Current Password</label>
              <input
                v-model="passwordData.current_password"
                type="password"
                class="w-full px-3 py-2 border rounded"
              />
            </div>

            <div class="mb-4">
              <label class="block text-sm font-bold mb-2">New Password</label>
              <input
                v-model="passwordData.new_password"
                type="password"
                class="w-full px-3 py-2 border rounded"
              />
            </div>

            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              Change Password
            </button>
          </form>
        </div>
      </div>

      <div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-bold mb-4">Profile Picture</h2>
          <div class="text-center">
            <img
              :src="profileData.avatar || 'https://via.placeholder.com/150'"
              alt="Avatar"
              class="w-32 h-32 rounded-full mx-auto mb-4"
            />
            <input
              type="file"
              ref="avatarInput"
              @change="uploadAvatar"
              accept="image/*"
              class="hidden"
            />
            <button
              @click="$refs.avatarInput.click()"
              class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
              Change Avatar
            </button>
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
  }
}

async function updateProfile() {
  updating.value = true
  try {
    await portalService.updateProfile({
      first_name: profileData.value.first_name,
      last_name: profileData.value.last_name,
      bio: profileData.value.bio
    })
    alert('Profile updated successfully')
  } catch (error) {
    console.error('Failed to update profile:', error)
    alert('Failed to update profile')
  } finally {
    updating.value = false
  }
}

async function changePassword() {
  try {
    await portalService.changePassword(
      passwordData.value.current_password,
      passwordData.value.new_password
    )
    alert('Password changed successfully')
    passwordData.value = { current_password: '', new_password: '' }
  } catch (error) {
    console.error('Failed to change password:', error)
    alert('Failed to change password')
  }
}

async function uploadAvatar(event: any) {
  const file = event.target.files[0]
  if (!file) return

  const formData = new FormData()
  formData.append('avatar', file)

  try {
    await portalService.uploadAvatar(formData)
    await fetchProfile()
    alert('Avatar uploaded successfully')
  } catch (error) {
    console.error('Failed to upload avatar:', error)
    alert('Failed to upload avatar')
  }
}
</script>
