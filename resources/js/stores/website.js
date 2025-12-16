import { defineStore } from 'pinia'
import api from '@/services/api'

export const useWebsiteStore = defineStore('website', {
    state: () => ({
        websites: [],
        website: null,
        loading: false,
        error: null
    }),

    actions: {
        async fetchWebsites() {
            this.loading = true
            this.error = null
            try {
                const response = await api.get('/websites')
                this.websites = response
            } catch (error) {
                this.error = error.message || 'Failed to fetch websites'
                throw error
            } finally {
                this.loading = false
            }
        },

        async fetchWebsite(id) {
            this.loading = true
            this.error = null
            try {
                const response = await api.get(`/websites/${id}`)
                this.website = response
                // Pages might be included or need a separate fetch.
                // Based on standard API resources, if relationships aren't loaded, we might need to fetch pages.
                // But usually show includes details. If not, we can fetch pages separately.
                // Let's assume we might need to fetch pages if they are not in the response.
                if (!this.website.pages) {
                    await this.fetchPages(id)
                }
            } catch (error) {
                this.error = error.message || 'Failed to fetch website'
                throw error
            } finally {
                this.loading = false
            }
        },

        async storeWebsite(websiteData) {
            this.loading = true
            this.error = null
            try {
                const response = await api.post('/websites', websiteData)
                this.websites.push(response)
                return response
            } catch (error) {
                this.error = error.message || 'Failed to create website'
                throw error
            } finally {
                this.loading = false
            }
        },

        async updateWebsite(id, websiteData) {
            this.loading = true
            try {
                const response = await api.put(`/websites/${id}`, websiteData)
                // Update in list
                const index = this.websites.findIndex((w) => w.id === id)
                if (index !== -1) {
                    this.websites[index] = response
                }
                // Update current if selected
                if (this.website && this.website.id === id) {
                    this.website = { ...this.website, ...response }
                }
                return response
            } catch (error) {
                this.error = error.message || 'Failed to update website'
                throw error
            } finally {
                this.loading = false
            }
        },

        async deleteWebsite(id) {
            this.loading = true
            try {
                await api.delete(`/websites/${id}`)
                this.websites = this.websites.filter((w) => w.id !== id)
                if (this.website && this.website.id === id) {
                    this.website = null
                }
            } catch (error) {
                this.error = error.message || 'Failed to delete website'
                throw error
            } finally {
                this.loading = false
            }
        },

        // Pages
        async fetchPages(websiteId) {
            try {
                const response = await api.get(`/websites/${websiteId}/pages`)
                if (this.website && this.website.id == websiteId) {
                    this.website.pages = response
                }
                return response
            } catch (error) {
                // If it fails, just log or set error
                console.error('Failed to fetch pages', error)
            }
        },

        async storePage(websiteId, pageData) {
            this.loading = true
            try {
                const response = await api.post(`/websites/${websiteId}/pages`, pageData)
                if (this.website && this.website.id == websiteId) {
                    if (!this.website.pages) this.website.pages = []
                    this.website.pages.push(response)
                }
                return response
            } catch (error) {
                this.error = error.message || 'Failed to add page'
                throw error
            } finally {
                this.loading = false
            }
        },

        async deletePage(pageId) {
            this.loading = true
            try {
                await api.delete(`/pages/${pageId}`)
                if (this.website && this.website.pages) {
                    this.website.pages = this.website.pages.filter((p) => p.id !== pageId)
                }
            } catch (error) {
                this.error = error.message || 'Failed to delete page'
                throw error
            } finally {
                this.loading = false
            }
        },

        async checkPageUptime(pageId) {
            try {
                await api.post(`/pages/${pageId}/check`)
                // Refresh the website data to get updated page status
                if (this.website) {
                    await this.fetchWebsite(this.website.id)
                }
            } catch (error) {
                this.error = error.message || 'Failed to trigger uptime check'
                throw error
            }
        }
    }
})
