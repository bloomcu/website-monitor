<script setup>
import { ref } from 'vue'
import { useWebsiteStore } from '@/stores/website'
import { useRouter } from 'vue-router'
import DefaultLayout from '@/layouts/DefaultLayout.vue'

const router = useRouter()
const websiteStore = useWebsiteStore()

const newWebsite = ref({
    name: '',
    base_url: ''
})
const createError = ref('')

const createWebsite = async () => {
    try {
        await websiteStore.storeWebsite(newWebsite.value)
        router.push({ name: 'websites.index' })
    } catch (err) {
        createError.value = err.message || 'Failed to create website'
    }
}

const cancel = () => {
    router.push({ name: 'websites.index' })
}
</script>

<template>
    <DefaultLayout>
        <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Create Website</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Add a new website to monitor. You can add specific pages to monitor after creating the website.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <form @submit.prevent="createWebsite">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <div v-if="createError" class="rounded-md bg-red-50 p-4 text-sm text-red-500">
                                    {{ createError }}
                                </div>
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700"> Name </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input
                                                type="text"
                                                name="name"
                                                id="name"
                                                v-model="newWebsite.name"
                                                required
                                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-md sm:text-sm border-gray-300 p-2 border"
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
                                                v-model="newWebsite.base_url"
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
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 active:bg-gray-100 active:scale-95 transition-transform focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
