import { handleError } from "@/helpers/errorHelper";
import axiosInstance from "@/plugins/axios";
import { defineStore } from "pinia";


export const useSocialAssistanceStore = defineStore('social-assistance', {
    state: () => ({
        socialAssistances: [],
        meta: {
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0
        },
        loading: false,
        error: null,
        success: null
    }),

    actions: {
        async fetchSocialAssistances(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('social-assistance', {params})

                this.socialAssistances = response.data.data
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async fetchSocialAssistancesPaginated(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('social-assistance/all/paginated', {params})

                this.socialAssistances = response.data.data.data
                this.meta = response.data.data.meta
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async fetchSocialAssistance(id) {
            this.loading = true

            try {
                const response = await axiosInstance.get(`social-assistance/${id}`)

                return response.data.data
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        },

        async createSocialAssistance(payload) {
            this.loading = true
            this.error = null

            try {
                const fd = new FormData()

                // append field primitif yang ada
                for (const [key, val] of Object.entries(payload)) {
                    if (val === null || val === undefined) continue
                    if (key === 'thumbnail_url') continue // ini hanya untuk preview
                    if (key === 'thumbnail') continue     // handle khusus di bawah
                    fd.append(key, val)
                }

                // file harus File object
                if (payload.thumbnail instanceof File) {
                    fd.append('thumbnail', payload.thumbnail)
                }

                // JANGAN set Content-Type manual; biar browser yang set (dengan boundary)
                const { data } = await axiosInstance.post('social-assistance', fd)

                this.success = data.message
                router.push({ name: 'social-assistance' })
                return data
            } catch (error) {
                // simpan detail validation errors dari Laravel
                this.error = handleError(error)
                throw error
            } finally {
                this.loading = false
            }
        },

        async deleteSocialAssistance(id) {
            this.loading = true

            try {
                const response = await axiosInstance.delete(`social-assistance/${id}`)

                this.success = response.data.message
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        }
    }
})