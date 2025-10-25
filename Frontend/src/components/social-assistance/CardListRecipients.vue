<script setup>
import { formatRupiah } from '@/helpers/format';

defineProps({
    item: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="card flex flex-col rounded-2xl border border-desa-background p-4 gap-4">
        <div class="flex items-center justify-between">
            <p class="font-medium text-sm text-desa-secondary">Tue, 31 Dec 2024 </p>
            <img src="@/assets/images/icons/calendar-2-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center gap-3">
            <div class="flex flex-col gap-[6px] w-full">
                <p class="font-semibold text-lg leading-5">Rp. {{ formatRupiah(item.amount) }}</p>
                <p class="font-medium text-sm text-desa-secondary">Nominal Pengajuan</p>
            </div>
            <div class="badge rounded-full p-3 gap-2 flex w-[100px] justify-center shrink-0 bg-desa-yellow">
                <span class="font-semibold text-xs text-white uppercase">Menunggu</span>
            </div>
        </div>
        <hr class="border-desa-background">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-0.5">
                <img src="@/assets/images/icons/profile-secondary-green.svg" class="flex size-[18px] shrink-0" alt="icon">
                <p class="font-medium text-sm text-desa-secondary">Diberikan Kepada:</p>
            </div>
            <div class="flex items-center gap-1">
                <p class="font-medium leading-5">Udin Louvre</p>
                <div class="flex size-8 shrink-0 rounded-full bg-desa-foreshadow overflow-hidden">
                    <img src="@/assets/images/photos/kk-photo-5.png" class="w-full h-full object-cover" alt="photo">
                </div>
            </div>
        </div>
    </div>
</template>