import { defineStore } from "pinia";
import { handleError } from "@/helpers/errorHelper";
import axiosInstance from '@/plugins/axios';
import Cookies from "js-cookie";
import router from "@/router";

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        loading: false,
        error: null, 
        success: null
    }),

    getters: {
        token: () => Cookies.get('token') || null
    },

    actions: {
        async login(credentials) {
            this.loading = true
            try {
                const response = await axiosInstance.post('/login', credentials)
                const token = response.data.token

                console.log("Berhasil login", token)
                Cookies.set('token', token, { path: '/' })
                await this.checkAuth()

                this.success = ('Login successful')
                return true
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async logout() {
            this.loading = true
            try {
                await axiosInstance.post('/logout')

                Cookies.remove('token')
            
                router.push({name: 'login'})
                this.user = null
                this.error = null
                this.success = ('Logout Successful')
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async checkAuth() {
            this.loading = true
            try {
                const response = await axiosInstance.get('/me')

                this.user = response.data.data
                return this.user
            } catch (error) {
                // gunakan optional chaining biar ga melempar error baru
                if (error?.response?.status === 401) {
                    this.logout()
                } else {
                    // log error lain (CORS, network, dsb) tapi JANGAN lempar error baru
                    console.error('checkAuth failed:', error)
                }
                return null
            } finally {
                this.loading = false
            }
        }
    }
})