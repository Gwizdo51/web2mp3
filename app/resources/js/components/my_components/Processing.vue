<script setup lang="ts">
import { LoaderCircle } from 'lucide-vue-next';
import { useEchoPublic } from "@laravel/echo-vue";
import { WSMessage } from '@/types';

// props
const {channelName} = defineProps<{
    channelName: string;
}>();

// emits
const emit = defineEmits<{
    message: [message: WSMessage];
}>();

useEchoPublic(
    channelName,
    "LinkProcessed",
    (message: WSMessage) => {
        emit('message', message);
    }
);
</script>


<template>
    <div class="flex flex-col justify-center items-center gap-5">
        <LoaderCircle strokeWidth="1" class="w-25 h-25 animate-spin text-red-800" />
        <p class="text-xl">Processing ...</p>
    </div>
</template>
