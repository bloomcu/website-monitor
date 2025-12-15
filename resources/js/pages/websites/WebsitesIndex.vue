<script setup>
import { ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useWebsiteStore } from '@/stores/website'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import Modal from '@/components/ui/Modal.vue'
import Button from '@/components/ui/Button.vue'
import Input from '@/components/ui/Input.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const websiteStore = useWebsiteStore()
const { websites, loading, error } = storeToRefs(websiteStore)

const showCreateModal = ref(false)
const newWebsite = ref({
    name: '',
    base_url: ''
})
const createError = ref('')

onMounted(() => {
    websiteStore.fetchWebsites()
})

const openCreateModal = () => {
    newWebsite.value = { name: '', base_url: '' }
    createError.value = ''
    showCreateModal.value = true
}

const createWebsite = async () => {
    try {
        await websiteStore.storeWebsite(newWebsite.value)
        showCreateModal.value = false
        // Optional: Notify success
    } catch (err) {
        createError.value = err.message || 'Failed to create website'
        // Ideally the store sets the error, but for the modal we want local feedback 
        // if we want to keep it open on error.
    }
}

const goToWebsite = (id) => {
    router.push({ name: 'websites.show', params: { id } })
}
</script>

<template>
    <DefaultLayout>
        <div class="py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Websites</h1>
                <button 
                    @click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Add Website
                </button>
            </div>

            <!-- Error State -->
            <div v-if="error" class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Heroicon name: solid/x-circle -->
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="loading && websites.length === 0" class="text-center py-12">
                <svg class="animate-spin h-8 w-8 text-gray-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Loading websites...</p>
            </div>

            <!-- Empirical State -->
            <div v-else-if="websites.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No websites</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new website project.</p>
                <div class="mt-6">
                    <button 
                        @click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Website
                    </button>
                </div>
            </div>

            <!-- Grid -->
            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div 
                    v-for="website in websites" 
                    :key="website.id" 
                    @click="goToWebsite(website.id)"
                    class="relative rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 cursor-pointer"
                >
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-100">
                             <span class="text-indigo-600 font-medium leading-none">{{ website.name.substring(0, 2).toUpperCase() }}</span>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="#" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">{{ website.name }}</p>
                            <p class="text-sm text-gray-500 truncate" v-if="website.base_url">{{ website.base_url }}</p>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Create Modal -->
            <Modal :show="showCreateModal" title="Create Website" @close="showCreateModal = false">
                <form @submit.prevent="createWebsite" class="space-y-4">
                     <div v-if="createError" class="rounded-md bg-red-50 p-4 text-sm text-red-500">
                        {{ createError }}
                    </div>
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" v-model="newWebsite.name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                    </div>
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">Base URL (Optional)</label>
                        <input type="url" id="url" v-model="newWebsite.base_url" placeholder="https://example.com" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2 border">
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Create
                        </button>
                        <button type="button" @click="showCreateModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </Modal>
        </div>
    </DefaultLayout>
</template>
