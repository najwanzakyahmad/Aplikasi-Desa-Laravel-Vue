import { handleError } from "@/helpers/errorHelper";
import axiosInstance from "@/plugins/axios";
import router from "@/router";
import { defineStore } from "pinia";

export const  useHeadOfFamilyStore = defineStore('head-of-family', {
    state: () => ({
        headOfFamilies: [],
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
        async fetchHeadOfFamilies(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('head-of-family', {params})

                this.headOfFamilies = response.data.data
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        },

        async fetchHeadOfFamiliesPaginated(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('head-of-family/all/paginated', {params})

                this.headOfFamilies = response.data.data.data
                this.meta = response.data.data.meta
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        },

        async fetchHeadOfFamily(id) {
            this.loading = true

            try {
                const response = await axiosInstance.get(`head-of-family/${id}`)

                return response.data.data
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        },

        async createHeadOfFamily(payload) {
            this.loading = true
            this.error = null
            try {
                const fd = new FormData()

                // append field primitif yang ada
                for (const [key, val] of Object.entries(payload)) {
                if (val === null || val === undefined) continue
                if (key === 'profile_picture_url') continue // ini hanya untuk preview
                if (key === 'profile_picture') continue     // handle khusus di bawah
                fd.append(key, val)
                }

                // file harus File object
                if (payload.profile_picture instanceof File) {
                fd.append('profile_picture', payload.profile_picture)
                }

                // JANGAN set Content-Type manual; biar browser yang set (dengan boundary)
                const { data } = await axiosInstance.post('head-of-family', fd)

                this.success = data.message
                router.push({ name: 'head-of-family' })
                return data
            } catch (error) {
                // simpan detail validation errors dari Laravel
                this.error = handleError(error)
                throw error
            } finally {
                this.loading = false
            }
        },

        async deleteHeadOfFamily(id) {
            this.loading = true

            try {
                const response = await axiosInstance.delete(`head-of-family/${id}`)

                this.success = response.data.message
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        }
    }
})