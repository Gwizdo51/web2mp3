<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
}>();
</script>


<template>
    <Head :title="title" />
    <!-- bg-[#141719] -->
    <div class="bg-zinc-950 min-h-screen flex flex-col justify-center items-center selection:bg-foreground selection:text-gray-950">
        <!-- bg-[#2A2222] -->
        <div class="relative w-3xl h-144 pt-10 bg-red-950/30 rounded-3xl border-2 border-red-800 flex justify-center">
            <Link :href="route('home')" class="absolute h-20 w-xl -top-10 bg-zinc-900 rounded-full border-2 border-red-800 flex justify-center items-center text-3xl text-shadow-lg/80">
                YouTube 2 mp3
            </Link>
            <!-- <Link :href="route('home')" class="before:content-['>_'] before:mr-1 after:content-['_<'] after:ml-1">Return to home</Link> -->
            <slot />
        </div>
    </div>
</template>
