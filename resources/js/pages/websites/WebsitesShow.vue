<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useWebsiteStore } from '@/stores/website'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import StatusBadge from '@/components/ui/StatusBadge.vue'

const route = useRoute()
const router = useRouter()
const websiteStore = useWebsiteStore()
const { website, loading, error } = storeToRefs(websiteStore)

const showAddForm = ref(false)
const newPage = ref({
    url: ''
})
const pageError = ref('')

const websiteId = route.params.id

onMounted(() => {
    websiteStore.fetchWebsite(websiteId)
})

const toggleAddForm = () => {
    if (showAddForm.value) {
        // Hide form
        showAddForm.value = false
        newPage.value = { url: '' }
        pageError.value = ''
    } else {
        // Show form
        showAddForm.value = true
    }
}

const addPage = async () => {
    try {
        await websiteStore.storePage(websiteId, newPage.value)
        showAddForm.value = false
        newPage.value = { url: '' }
        pageError.value = ''
    } catch (err) {
        pageError.value = err.message || 'Failed to add page'
    }
}

const deletePage = async (pageId) => {
    if (!confirm('Are you sure you want to delete this page?')) return
    await websiteStore.deletePage(pageId)
}

const deleteWebsite = async () => {
    if (!confirm('Are you sure you want to delete this website project? This action cannot be undone.')) return
    
    try {
        await websiteStore.deleteWebsite(websiteId)
        router.push({ name: 'websites.index' })
    } catch (err) {
        alert('Failed to delete website')
    }
}

const refresh = async () => {
    await websiteStore.fetchPages(websiteId)
}
</script>

<template>
    <DefaultLayout>
        <div v-if="loading && !website" class="py-12 text-center">
             <svg class="animate-spin h-8 w-8 text-gray-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div v-else-if="error" class="py-12 text-center">
             <div class="rounded-md bg-red-50 p-4 mb-6 inline-block text-left">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">{{ error }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="website" class="py-6">
            <!-- Header -->
            <div class="md:flex md:items-center md:justify-between mb-8">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        {{ website.name }}
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500" v-if="website.base_url">
                            <!-- Heroicon name: solid/link -->
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                            </svg>
                            <a :href="website.base_url" target="_blank" class="hover:underline">{{ website.base_url }}</a>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                     <button
                        @click="refresh"
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
                    >
                        Refresh
                    </button>
                    <button
                        @click="router.push({ name: 'websites.edit', params: { id: websiteId } })"
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
                    >
                        Edit
                    </button>
                    <button
                        @click="deleteWebsite"
                        type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        Delete Project
                    </button>
                </div>
            </div>

            <!-- Pages Section -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Monitored Pages</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">List of pages being checked for uptime.</p>
                    </div>
                    <button
                         @click="toggleAddForm"
                         class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        {{ showAddForm ? 'Cancel' : 'Add Page' }}
                    </button>
                </div>

                <!-- Inline Add Page Form -->
                <div v-if="showAddForm" class="border-t border-gray-200 bg-white px-4 py-6 sm:px-6 shadow-sm">
                    <form @submit.prevent="addPage" class="max-w-2xl">
                        <div v-if="pageError" class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="ml-3 text-sm text-red-600">{{ pageError }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex-1">
                                <label for="page-url" class="block text-sm font-medium text-gray-700 mb-2">Page URL</label>
                                <input
                                    id="page-url"
                                    type="url"
                                    v-model="newPage.url"
                                    required
                                    placeholder="https://example.com/page"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors"
                                    autofocus
                                >
                            </div>
                            <div class="pt-6">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-colors"
                                >
                                    Add Page
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-t border-gray-200">
                    <ul role="list" class="divide-y divide-gray-200" v-if="website.pages && website.pages.length > 0">
                        <li v-for="page in website.pages" :key="page.id" class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-1 items-center min-w-0 mr-4">
                                   <div class="flex-shrink-0">
                                         <StatusBadge :status="page.is_up" />
                                   </div>
                                    <div class="ml-4 truncate">
                                        <p class="text-sm font-medium text-indigo-600 truncate">{{ page.url }}</p>
                                        <div class="flex text-sm text-gray-500 space-x-4 mt-1">
                                            <span v-if="page.last_status_code">Code: {{ page.last_status_code }}</span>
                                            <span v-if="page.last_response_time_ms">{{ page.last_response_time_ms }}ms</span>
                                            <span v-if="page.last_checked_at">Last Checked: {{ new Date(page.last_checked_at).toLocaleString() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button @click="deletePage(page.id)" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                     <div v-else class="text-center py-6 text-gray-500 text-sm">
                        No pages added yet.
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
