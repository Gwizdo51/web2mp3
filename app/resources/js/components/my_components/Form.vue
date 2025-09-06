<script setup lang="ts">
// import Layout from '@/layouts/Layout.vue';
// import { Form } from '@inertiajs/vue3';
import { CircleAlert, LoaderCircle } from 'lucide-vue-next';

// static data
const formats = ["mp3", "m4a", "flac", "wav", "aac", "alac", "opus", "vorbis"];
const qualities = [
    {name: "best", value: 0},
    {name: "good", value: 2},
    {name: "average", value: 4},
    {name: "poor", value: 6}
];

// props
// const errors = {link: ""};
// const processing = ref(false);
const {linkError = "", processing = false} = defineProps<{
    linkError?: string;
    processing?: boolean;
}>();

// models
const inputLink = defineModel<string>("inputLink", {required: true});
const inputFormat = defineModel<string>("inputFormat", {required: true});
const inputQuality = defineModel<number>("inputQuality", {required: true});

// emits
defineEmits<{
    submit: [];
}>();
</script>


<template>
    <!-- <Layout title="Welcome"> -->
        <!-- <Link :href="route('home')" class="before:content-['>_'] before:mr-1 after:content-['_<'] after:ml-1">Return to home</Link> -->
        <!-- <Form method="post" class="flex flex-col place-content-evenly" v-slot="{ errors, processing }"> -->
        <form @submit.prevent="$emit('submit')" class="flex flex-col place-content-evenly">
            <div class="relative flex flex-col items-center">
                <label for="inputLink" class="text-xl text-zinc-400 mb-1">Link</label>
                <input id="inputLink" type="text" class="border-b-2 border-zinc-700 w-160 outline-none focus:border-red-800 text-center placeholder:text-zinc-700 hover:border-red-800/50 transition-all ease-out duration-200"
                name="link" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ" required v-model="inputLink">
                <div v-if="linkError" class="absolute flex items-center gap-1 text-sm text-red-500 -bottom-6">
                    <CircleAlert class="h-5 w-5" />
                    {{ linkError }}
                </div>
            </div>
            <div class="flex flex-col items-center">
                <h3 class="text-xl text-zinc-400 mb-1">Audio format</h3>
                <div class="grid grid-cols-4">
                    <template v-for="(format, index) in formats">
                        <label :for="'format_' + format" class="w-40 py-3 text-center has-checked:bg-red-800/70 hover:bg-red-800/30 cursor-pointer has-checked:cursor-default transition-all ease-out duration-100">
                            {{ format.toUpperCase() }}
                            <input type="radio" name="format" :id="'format_' + format" :value="format" hidden v-model="inputFormat">
                        </label>
                    </template>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <h3 class="text-xl text-zinc-400 mb-1">Quality</h3>
                <div class="grid grid-cols-4">
                    <template v-for="(quality, index) in qualities">
                        <label :for="'quality_' + quality.name" class="w-40 py-3 text-center has-checked:bg-red-800/70 hover:bg-red-800/30 cursor-pointer has-checked:cursor-default transition-all ease-out duration-100">
                            {{ quality.name.toUpperCase() }}
                            <input type="radio" name="quality" :id="'quality_' + quality.name" :value="quality.value" hidden v-model="inputQuality">
                        </label>
                    </template>
                </div>
            </div>
            <button type="submit" class="flex justify-center items-center gap-2 bg-red-800/70 rounded-full text-2xl py-2 hover:bg-red-800/50 active:bg-red-950/70 disabled:bg-red-950/70
            cursor-pointer disabled:cursor-default transition-all ease-out duration-100" :disabled="processing">
                <LoaderCircle v-if="processing" class="w-6 h-6 animate-spin" />
                Convert & Download
            </button>
        </form>
    <!-- </Layout> -->
</template>
