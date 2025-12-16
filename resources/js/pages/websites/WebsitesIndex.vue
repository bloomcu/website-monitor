<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { storeToRefs } from 'pinia'
import { useWebsiteStore } from '@/stores/website'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const websiteStore = useWebsiteStore()
const { websites, loading, error } = storeToRefs(websiteStore)

const lastUpdated = ref(null)
const minutesUntilRefresh = ref(30)
let refreshInterval = null
let countdownInterval = null

const fetchData = async () => {
    await websiteStore.fetchWebsites()
    lastUpdated.value = new Date()
    minutesUntilRefresh.value = 30
}

onMounted(() => {
    fetchData()

    // Refresh every 30 minutes (1800000 milliseconds)
    refreshInterval = setInterval(() => {
        fetchData()
    }, 1800000)

    // Update countdown every minute
    countdownInterval = setInterval(() => {
        if (minutesUntilRefresh.value > 0) {
            minutesUntilRefresh.value--
        }
    }, 60000)
})

onBeforeUnmount(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval)
    }
    if (countdownInterval) {
        clearInterval(countdownInterval)
    }
})

const goToCreate = () => {
    router.push({ name: 'websites.create' })
}

const goToWebsite = (id) => {
    router.push({ name: 'websites.show', params: { id } })
}
</script>

<template>
    <DefaultLayout>
        <div class="py-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Websites</h1>
                    <p v-if="lastUpdated" class="text-xs text-gray-500 mt-1">
                        Last updated: {{ lastUpdated.toLocaleTimeString(undefined, { hour: 'numeric', minute: '2-digit' }) }} • Next refresh in {{ minutesUntilRefresh }} {{ minutesUntilRefresh === 1 ? 'minute' : 'minutes' }}
                    </p>
                </div>
                <button
                    @click="goToCreate"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
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
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd"
                            />
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
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Loading websites...</p>
            </div>

            <!-- Empirical State -->
            <div v-else-if="websites.length === 0" class="text-center py-12 border-2 border-dashed border-gray-300 rounded-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path
                        vector-effect="non-scaling-stroke"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No websites</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new website project.</p>
                <div class="mt-6">
                    <button
                        @click="goToCreate"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path
                                fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd"
                            />
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
                    class="relative rounded-lg border bg-white px-6 py-5 shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                    :class="{
                        'border-green-300 bg-green-50': website.is_healthy && website.total_pages_count > 0,
                        'border-red-300 bg-red-50': !website.is_healthy && website.total_pages_count > 0,
                        'border-gray-300': website.total_pages_count === 0
                    }"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <span
                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full"
                                    :class="{
                                        'bg-green-100': website.is_healthy && website.total_pages_count > 0,
                                        'bg-red-100': !website.is_healthy && website.total_pages_count > 0,
                                        'bg-indigo-100': website.total_pages_count === 0
                                    }"
                                >
                                    <span
                                        class="font-medium leading-none"
                                        :class="{
                                            'text-green-600': website.is_healthy && website.total_pages_count > 0,
                                            'text-red-600': !website.is_healthy && website.total_pages_count > 0,
                                            'text-indigo-600': website.total_pages_count === 0
                                        }"
                                    >
                                        {{ website.name.substring(0, 2).toUpperCase() }}
                                    </span>
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
                        <div class="flex-shrink-0 ml-2">
                            <span
                                v-if="website.total_pages_count > 0"
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-green-100 text-green-800': website.is_healthy,
                                    'bg-red-100 text-red-800': !website.is_healthy
                                }"
                            >
                                <svg
                                    class="-ml-0.5 mr-1.5 h-2 w-2"
                                    :class="{
                                        'text-green-400': website.is_healthy,
                                        'text-red-400': !website.is_healthy
                                    }"
                                    fill="currentColor"
                                    viewBox="0 0 8 8"
                                >
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ website.is_healthy ? 'Healthy' : 'Issues' }}
                            </span>
                            <span
                                v-else
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
                            >
                                No pages
                            </span>
                        </div>
                    </div>

                    <div v-if="website.total_pages_count > 0" class="mt-4 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-green-600">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="font-medium">{{ website.pages_up_count }}</span>
                                <span class="text-gray-500 ml-1">up</span>
                            </div>
                            <div class="flex items-center" :class="website.pages_down_count > 0 ? 'text-red-600' : 'text-gray-400'">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="font-medium">{{ website.pages_down_count }}</span>
                                <span class="text-gray-500 ml-1">down</span>
                            </div>
                        </div>
                        <div class="text-gray-500">
                            {{ website.total_pages_count }} {{ website.total_pages_count === 1 ? 'page' : 'pages' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
