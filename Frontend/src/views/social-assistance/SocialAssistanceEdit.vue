<script setup>
import router from '@/router';
import { useSocialAssistanceStore } from '@/stores/socialAssistanceStore';
import { storeToRefs } from 'pinia';
import { onMounted, ref, watch } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { formatRupiah, formatToClientTimezone } from '@/helpers/format';
import Input from '@/components/ui/Input.vue';
import Button from '@/components/ui/Button.vue';

import iconEditSecondaryGreen from '@/assets/images/icons/edit-secondary-green.svg'
import iconEditBlack from '@/assets/images/icons/edit-black.svg'
import iconDollarSquareSecondaryGreen from '@/assets/images/icons/dollar-square-secondary-green.svg'
import iconDollarSquareBlack from '@/assets/images/icons/dollar-square-black.svg'
import IconUserSquareSecondaryGreen from '@/assets/images/icons/user-square-secondary-green.svg'
import IconUserSquareBlack from '@/assets/images/icons/user-square-black.svg'

const socialAssistace = ref({
    'id'            : null,
    'thumbnail'     : null,
    'thumbnail_url' : null,
    'name'          : null,
    'category'      : null,
    'amount'        : 0,
    'provider'      : null,
    'description'   : null,
    'is_available'  : true
})

const categories = ref(['staple', 'cash', 'subsidized fuel', 'health'])

const socialAssistanceStore = useSocialAssistanceStore()
const { loading, error, success } = storeToRefs(socialAssistanceStore)
const { fetchSocialAssistance, updateSocialAssistance } = socialAssistanceStore

const route = useRoute()
const fetchData = async () => {
    const response = await fetchSocialAssistance(route.params.id)

    socialAssistace.value = response

    socialAssistace.value.thumbnail_url = response.thumbnail
    socialAssistace.value.thumbnail = null
}

const handleSubmit = async () => {
    await updateSocialAssistance(socialAssistace.value)

    router.push({name: 'manage-social-assistance', params: { id: socialAssistace.id }})
}

const handleImageChange = (event) => {
    const file = event.target.files?.[0]
    socialAssistace.value.thumbnail = file
    socialAssistace.value.thumbnail_url = URL.createObjectURL(file)

    console.log('file', file, file.name, file.type, file.size)
}

watch(() => socialAssistace.value.amount, (newAmount) => {
    socialAssistace.value.amount = formatRupiah(newAmount)
})

onMounted(fetchData)
</script>

<template>
    <div id="Header" class="flex items-center justify-between">
        <div class="flex flex-col gap-2">
            <div class="flex gap-1 items-center leading-5 text-desa-secondary">
                <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">Bantuan sosial</p>
                <span>/</span>
                <p class="last-of-type:text-desa-dark-green last-of-type:font-semibold capitalize ">tambah bantuan sosial</p>
            </div>
            <h1 class="font-semibold text-2xl">Tambah Bantuan Sosial</h1>
        </div>
    </div>
    <form @submit.prevent="handleSubmit" id="myForm" class="capitalize">
        <div class="shrink-0 rounded-3xl p-6 bg-white flex flex-col gap-6 h-fit">
            <section id="Thumbnail" class="flex items-center justify-between">
                <h2 class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Thumbnail Bantuan Sosial</h2>
                <div class="flex-1 flex items-center justify-between">
                    <div id="Photo-Preview" class="flex itce justify-center size-[100px] rounded-full overflow-hidden bg-desa-foreshadow">
                        <img id="Photo" :src="socialAssistace.thumbnail_url" alt="image" class="size-full object-cover" />
                    </div>
                    <div class="relative">
                        <input id="File" type="file" name="file" class="absolute opacity-0 left-0 w-full top-0 h-full" @change="handleImageChange" ref="thumbnail"/>
                        <button id="Upload" type="button" class="relative flex items-center py-4 px-6 rounded-2xl bg-desa-black gap-[10px]" @click="$refs.thumbnail.click()">
                            <img src="@/assets/images/icons/send-square-white.svg" alt="icon" class="size-6 shrink-0" />
                            <p class="font-medium leading-5 text-white">Upload</p>
                        </button>
                    </div>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Nama-Bantuan-Sosial" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Bantuan Sosial</p>
                <div class="flex flex-col gap-3 flex-1 shrink-0">
                    <Input v-model="socialAssistace.name" type="text" placeholder="Ketik Nama Bantuan Sosial" :error-message="error?.name" :icon="iconEditSecondaryGreen" :filled-icon="iconEditBlack"/>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Kategori" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Pilih Opsi Kategori</p>
                <div class="grid grid-cols-2 flex-1 gap-6 shrink-0">
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input value="staple" v-model="socialAssistace.category" type="radio" name="category" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            Bahan Pokok
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/bag-2-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/bag-2-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input value="cash" v-model="socialAssistace.category" type="radio" name="category" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            Uang Tunai
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/money-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/money-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input value="subsidized fuel" v-model="socialAssistace.category" type="radio" name="category" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            BBM Subsidi
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/gas-station-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/gas-station-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input value="health" v-model="socialAssistace.category" type="radio" name="category" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            Kesehatan
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/health-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/health-secondary-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Nominal Bantuan" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nominal Bantuan</p>
                <div class="flex flex-col gap-3 flex-1 shrink-0">
                    <Input v-model="socialAssistace.amount" type="text" placeholder="Ketik Jumlah Nominal Bantuan Sosial" :error-message="error?.amount" :icon="iconDollarSquareSecondaryGreen" :filled-icon="iconDollarSquareBlack"/>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Nama-Pemberi-Bantuan" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Nama Pemberi Bantuan</p>
                <div class="flex flex-col gap-3 flex-1 shrink-0">
                    <Input v-model="socialAssistace.provider" type="text" placeholder="Ketik nama orang atau organisasi" :error-message="error?.provider" :icon="IconUserSquareSecondaryGreen" :filled-icon="IconUserSquareBlack"/>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Deskripsi" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Deskripsi Bantuan Sosial</p>
                <div class="flex flex-col gap-3 flex-1 shrink-0">
                    <textarea v-model="socialAssistace.description" name="" id="" placeholder="Jelaskan lebih detail tentang bantuan" rows="6" class="appearance-none outline-none w-full rounded-2xl ring-[1.5px] ring-desa-background focus:ring-desa-black py-4 px-4 gap-2 font-medium placeholder:text-desa-secondary transition-all duration-300">Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis, ad.
                    </textarea>
                </div>
            </section>
            <hr class="border-desa-background" />
            <section id="Ketersediaan" class="flex items-center justify-between">
                <p class="font-medium leading-5 text-desa-secondary w-[calc(424/904*100%)]">Pilih Opsi Ketersediaan</p>
                <div class="flex flex-1 gap-6 shrink-0">
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input v-model="socialAssistace.is_available" value="1" type="radio" name="gender" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            Tersedia
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/tick-circle-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/tick-circle-dark-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                    <label class="group flex w-full items-center h-14 rounded-2xl p-4 ring-[1.5px] ring-desa-background gap-2 has-[:checked]:ring-none has-[:checked]:bg-desa-foreshadow transition-setup">
                        <input v-model="socialAssistace.is_available" value="0" type="radio" name="gender" id="" class="flex size-[18px] shrink-0 accent-desa-secondary checked:accent-desa-dark-green transition-setup">
                        <span class="font-medium leading-5 text-desa-secondary w-full group-has-[:checked]:text-desa-dark-green transition-setup">
                            Tidak Tersedia
                        </span>
                        <div class="flex size-6 shrink-0">
                            <img src="@/assets/images/icons/close-circle-secondary-green.svg" class="size-6 flex group-has-[:checked]:hidden" alt="icon">
                            <img src="@/assets/images/icons/close-circle-secondary-green.svg" class="size-6 hidden group-has-[:checked]:flex" alt="icon">
                        </div>
                    </label>
                </div>
            </section>
            <hr class="border-desa-background w-[calc(100%+48px)] -mx-6" />
            <section id="Buttons" class="flex items-center justify-end gap-4">
                <RouterLink :to="{name: 'manage-social-assistance', params: {id: socialAssistace.id}}">
                    <div class="py-[18px] rounded-2xl bg-desa-red w-[180px] text-center flex justify-center font-medium text-white">Batal, Tidak jadi</div>
                </RouterLink>
                <Button type="submit" label="Save Changes" :loading="loading" class="py-[18px] rounded-2xl disabled:bg-desa-grey w-[180px] text-center flex justify-center font-medium text-white bg-desa-dark-green transition-all duration-300">Save Changes</Button>
            </section>
        </div>
    </form>
</template>