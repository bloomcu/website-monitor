<script setup>
import { ref, onMounted } from 'vue'
import { useWebsiteStore } from '@/stores/website'
import { useRouter, useRoute } from 'vue-router'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const router = useRouter()
const route = useRoute()
const websiteStore = useWebsiteStore()

const websiteData = ref({
    name: '',
    base_url: ''
})
const updateError = ref('')
const loading = ref(true)

onMounted(async () => {
    try {
        await websiteStore.fetchWebsite(route.params.id)
        websiteData.value = {
            name: websiteStore.website.name,
            base_url: websiteStore.website.base_url || ''
        }
    } catch (err) {
        updateError.value = err.message || 'Failed to load website'
    } finally {
        loading.value = false
    }
})

const updateWebsite = async () => {
    try {
        await websiteStore.updateWebsite(route.params.id, websiteData.value)
        router.push({ name: 'websites.show', params: { id: route.params.id } })
    } catch (err) {
        updateError.value = err.message || 'Failed to update website'
    }
}

const cancel = () => {
    router.push({ name: 'websites.show', params: { id: route.params.id } })
}
</script>

<template>
    <DefaultLayout>
        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div v-if="loading" class="text-center py-12">
                <svg class="animate-spin h-8 w-8 text-gray-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Loading website...</p>
            </div>

            <div v-else class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Website</h3>
                        <p class="mt-1 text-sm text-gray-600">Update the website name and base URL.</p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form @submit.prevent="updateWebsite">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <div v-if="updateError" class="rounded-md bg-red-50 p-4 text-sm text-red-500">
                                    {{ updateError }}
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input
                                                type="text"
                                                name="name"
                                                id="name"
                                                v-model="websiteData.name"
                                                required
                                                class="focus:ring-neutral-500 focus:border-neutral-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300 p-2 border"
                                                placeholder="My Website"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="url" class="block text-sm font-medium text-gray-700"> Base URL (Optional) </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input
                                                type="text"
                                                name="url"
                                                id="url"
                                                v-model="websiteData.base_url"
                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300 p-2 border"
                                                placeholder="www.example.com"
                                            />
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">The base URL of the website. Used for default checking.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button
                                    type="button"
                                    @click="cancel"
                                    class="inline-flex items-center px-4 py-1.5 border border-neutral-200 bg-white text-sm font-medium rounded-full text-neutral-700 shadow-sm transition hover:border-neutral-300 hover:bg-neutral-100 active:bg-neutral-100 active:scale-95 focus:outline-none mr-3 cursor-pointer"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-1.5 border border-neutral-900 bg-neutral-900 text-sm font-semibold rounded-full text-white shadow-sm transition hover:bg-neutral-800 active:bg-neutral-900 active:scale-95 focus:outline-none cursor-pointer"
                                >
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
