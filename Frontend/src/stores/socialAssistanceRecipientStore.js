import { handleError } from "@/helpers/errorHelper";
import axiosInstance from "@/plugins/axios";
import router from "@/router";
import { defineStore } from "pinia";


export const useSocialAssistanceRecipientStore = defineStore('social-assistance-recipient', {
    state: () => ({
        socialAssistanceRecipients: [],
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
        async fetchSocialAssistanceRecipients(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('-recipientsocial-assistance', {params})

                this.socialAssistanceRecipients = response.data.data
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async fetchSocialAssistanceRecipientsPaginated(params) {
            this.loading = true

            try {
                const response = await axiosInstance.get('social-assistance-recipient/all/paginated', {params})

                this.socialAssistanceRecipients = response.data.data.data
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
                const response = await axiosInstance.get(`social-assistance-recipient/${id}`)

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

                // helper: append nilai ke FormData dengan aman
                const appendSafe = (key, val) => {
                    if (val === null || val === undefined) return
                    if (val instanceof File) return fd.append(key, val)
                    if (typeof val === 'boolean') return fd.append(key, val ? '1' : '0')
                    if (typeof val === 'object') return fd.append(key, JSON.stringify(val))
                    return fd.append(key, String(val))
                }

                for (const [key, val] of Object.entries(payload)) {
                    if (key === 'thumbnail_url') continue // hanya untuk preview
                    if (key === 'thumbnail') continue     // handle di bawah
                    if (key === 'amount') continue        // JANGAN di-append di sini (biar dinormalisasi)
                    appendSafe(key, val)
                }

                // file harus File object
                if (payload.thumbnail instanceof File) {
                    fd.append('thumbnail', payload.thumbnail)
                }

                // amount wajib decimal:2 (tanpa separator ribuan)
                fd.append('amount', normalizeAmountToDecimal2(payload.amount))

                // POST (buat data baru, ga perlu _method)
                const { data } = await axiosInstance.post('social-assistance-recipient', fd)

                this.success = data.message
                router.push({ name: 'social-assistance-recipient' })
                return data
            } catch (error) {
                this.error = handleError(error)
                throw error
            } finally {
                this.loading = false
            }
        },


        async updateSocialAssistance(payload) {
            this.loading = true
            try {
                const body = {
                ...payload,
                amount: normalizeAmountToDecimal2(payload.amount), 
                _method: 'PUT',
                }

                const response = await axiosInstance.post(`social-assistance-recipient/${payload.id}`, body)
                this.success = response.data.message
            } catch (error) {
                this.error = handleError(error)
            } finally {
                this.loading = false
            }
        },

        async deleteSocialAssistance(id) {
            this.loading = true

            try {
                const response = await axiosInstance.delete(`social-assistance-recipient/${id}`)

                this.success = response.data.message
            } catch (error) {
                this.error = handleError(error)                
            } finally {
                this.loading = false
            }
        }
    }
})

function normalizeAmountToDecimal2(input) {
  if (input === null || input === undefined) return '0.00'
  let s = String(input).trim()

  // simpan tanda minus di depan jika ada
  const isNeg = s.startsWith('-')
  s = s.replace(/(?!^)-/g, '') // hilangkan minus selain di awal

  // sisakan hanya digit, koma, titik, minus
  s = s.replace(/[^\d,.\-]/g, '')

  const hasDot = s.includes('.')
  const hasComma = s.includes(',')

  if (hasDot && hasComma) {
    // ada keduanya -> anggap pemisah desimal adalah yang paling kanan
    const lastDot = s.lastIndexOf('.')
    const lastComma = s.lastIndexOf(',')
    const decimalSep = lastDot > lastComma ? '.' : ','
    const thousandsSep = decimalSep === '.' ? ',' : '.'
    s = s.split(thousandsSep).join('')           // buang ribuan
    if (decimalSep === ',') s = s.replace(',', '.') // normalisasi desimal ke titik
  } else if (hasComma && !hasDot) {
    // hanya koma: tentukan ribuan vs desimal pakai pola 3 digit di belakang
    const parts = s.split(',')
    const lastLen = parts[parts.length - 1].length
    const looksLikeThousands = parts.length > 1 && lastLen === 3 && parts.slice(0, -1).every(p => p.length > 0 && p.length <= 3)
    if (looksLikeThousands) {
      // contoh: 80,000 -> 80000
      s = parts.join('')
    } else {
      // contoh: 12,5 -> 12.5
      s = parts.join('.')
    }
  } else if (!hasComma && hasDot) {
    // hanya titik: tentukan ribuan vs desimal
    const parts = s.split('.')
    const lastLen = parts[parts.length - 1].length
    const looksLikeThousands = parts.length > 1 && lastLen === 3 && parts.slice(0, -1).every(p => p.length > 0 && p.length <= 3)
    if (looksLikeThousands) {
      // contoh: 80.000 -> 80000
      s = parts.join('')
    } else {
      // contoh: 12.5 -> 12.5 (sudah desimal)
      // biarkan
    }
  } else {
    // hanya digit
  }

  const n = parseFloat(s)
  const val = Number.isFinite(n) ? n : 0
  const withSign = isNeg ? -val : val
  return withSign.toFixed(2)
}

