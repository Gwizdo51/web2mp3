<script setup lang="ts">
import { Check, X, Download } from 'lucide-vue-next';

// props
const {success, fileName} = defineProps<{
    success: boolean;
    fileName: string;
}>();

// emits
defineEmits<{
    return: [];
}>()
</script>


<template>
    <div class="flex flex-col place-content-evenly">
        <div v-if="success" class="relative flex flex-col items-center">
            <Check strokeWidth="1" class="absolute bottom-5 w-30 h-30 text-green-600" />
            <p class="text-xl pt-20"><span class="text-amber-400">{{ fileName }}</span> is ready !</p>
        </div>
        <div v-else class="relative flex flex-col items-center">
            <X strokeWidth="1" class="absolute bottom-5 w-30 h-30 text-red-800" />
            <p class="text-xl pt-20">Something went wrong ...</p>
        </div>
        <div class="flex flex-col items-center gap-3">
            <button class="flex items-center gap-2 bg-green-600 rounded-full py-2 px-5 text-2xl hover:bg-green-700 active:bg-green-800 transition-all ease-out duration-100 disabled:bg-green-950 disabled:text-zinc-500" :disabled="!success">
                <Download class="w-6 h-6" />
                Download
            </button>
            <p class="text-zinc-500">The download should start automatically</p>
        </div>
        <div class="text-center">
            <a href="/" @click.prevent="$emit('return')" class="hover:underline">Convert another link</a>
        </div>
    </div>
</template>
