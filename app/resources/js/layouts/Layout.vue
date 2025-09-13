<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";

defineProps<{
    title: string;
}>();
</script>


<template>
    <Head :title="title" />
    <div class="bg-zinc-950 min-h-svh flex flex-col justify-center items-center selection:bg-foreground selection:text-gray-950">
        <div class="md:relative w-full md:w-3xl grow md:grow-0 md:h-144 md:pt-10 md:mt-[38px] bg-red-950/30 md:rounded-3xl md:border-2 md:border-red-800 flex flex-col md:flex-row justify-center">
            <Link href="/" class="sticky top-0 md:absolute z-10 h-15 md:h-20 w-full md:w-xl md:-top-10 bg-zinc-900 md:rounded-full border-b-2 md:border-2 border-red-800 flex justify-center items-center text-3xl text-shadow-lg/80">
                Web 2 mp3
            </Link>
            <!-- <Link :href="route('home')" class="before:content-['>_'] before:mr-1 after:content-['_<'] after:ml-1">Return to home</Link> -->
            <div class="flex grow flex-col md:flex-row justify-center p-5 md:p-0">
                <slot />
            </div>
        </div>
    </div>
</template>
