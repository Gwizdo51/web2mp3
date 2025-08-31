<script setup lang="ts">
import Layout from '@/layouts/Layout.vue';
import { Form } from '@inertiajs/vue3';
import { CircleAlert, LoaderCircle } from 'lucide-vue-next';

const formats = ["mp3", "m4a", "flac", "wav", "aac", "alac", "opus", "vorbis"];
const qualities = [
    {name: "best", value: 10},
    {name: "good", value: 8},
    {name: "average", value: 6},
    {name: "poor", value: 4}
];
</script>

<template>
    <Layout title="Welcome">
        <!-- <Link :href="route('home')" class="before:content-['>_'] before:mr-1 after:content-['_<'] after:ml-1">Return to home</Link> -->
        <Form method="post" class="flex flex-col place-content-evenly" v-slot="{ errors, processing }">
            <div class="relative flex flex-col justify-center items-center">
                <label for="inputLink" class="text-xl text-zinc-400 mb-1">YouTube link</label>
                <input id="inputLink" type="text" class="border-b-2 border-zinc-700 w-160 outline-none focus:border-red-800 text-center placeholder:text-zinc-700 hover:border-red-800/50 transition-all ease-out duration-200"
                name="link" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ" required>
                <div v-if="errors.link" class="absolute inline-flex items-center gap-1 text-sm text-red-500 -bottom-6">
                    <CircleAlert class="h-5 w-5" />
                    {{ errors.link }}
                </div>
            </div>
            <div class="flex flex-col justify-center items-center">
                <h3 class="text-xl text-zinc-400 mb-1">Audio format</h3>
                <div class="grid grid-cols-4">
                    <template v-for="(format, index) in formats">
                        <div class="flex justify-center has-checked:bg-red-800/70 hover:bg-red-800/30 transition-all ease-out duration-100">
                            <label :for="'format_' + format" class="w-40 py-3 text-center">
                                {{ format.toUpperCase() }}
                            </label>
                            <input type="radio" name="format" :id="'format_' + format" :checked="index == 0" :value="format" class="hidden">
                        </div>
                    </template>
                </div>
            </div>
            <div class="flex flex-col justify-center items-center">
                <h3 class="text-xl text-zinc-400 mb-1">Quality</h3>
                <div class="grid grid-cols-4">
                    <template v-for="(quality, index) in qualities">
                        <div class="flex justify-center has-checked:bg-red-800/70 hover:bg-red-800/30 transition-all ease-out duration-100">
                            <label :for="'quality_' + quality.name" class="w-40 py-3 text-center">
                                {{ quality.name.toUpperCase() }}
                            </label>
                            <input type="radio" name="quality" :id="'quality_' + quality.name" :checked="index == 0" :value="quality.value" class="hidden">
                        </div>
                    </template>
                </div>
            </div>
            <button type="submit" class="inline-flex gap-2 justify-center items-center bg-red-800/70 rounded-full text-2xl py-2 hover:bg-red-800/50 active:bg-red-950/70 transition-all ease-out duration-100 disabled:bg-red-950/70" :disabled="processing">
                <LoaderCircle v-if="processing" class="w-6 h-6 animate-spin" />
                Convert & Download
            </button>
        </Form>
    </Layout>
</template>
