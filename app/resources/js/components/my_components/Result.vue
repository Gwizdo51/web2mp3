<script setup lang="ts">
import { Check, X, Download } from "lucide-vue-next";
import { onMounted } from "vue";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";

// props
const {success, downloadId, fileName, error} = defineProps<{
    success: boolean;
    downloadId: string;
    fileName: string;
    error: string;
}>();

const fileUrl = `/storage/${downloadId}/${fileName}`;

function onDownloadButtonClick(): void {
    (document.getElementById("downloadLink") as HTMLAnchorElement).click();
}

onMounted(() => {
    if (success) {
        // download the file automatically
        onDownloadButtonClick();
    }
});

function cropString(stringToCrop: string, maxLength: number): string {
    return stringToCrop.length > maxLength ? stringToCrop.substring(0, maxLength) + "..." : stringToCrop;
}

// emits
defineEmits<{
    return: [];
}>();
</script>


<template>
    <div class="flex flex-col place-content-evenly">
        <div class="relative flex flex-col items-center">
            <template v-if="success">
                <Check strokeWidth="1" class="absolute bottom-5 w-30 h-30 text-green-600" />
                <p class="text-xl pt-20"><span class="text-amber-400">{{ cropString(fileName, 60) }}</span> is ready !</p>
            </template>
            <template v-else>
                <X strokeWidth="1" class="absolute bottom-5 w-30 h-30 text-red-800" />
                <p class="text-xl pt-20">Something went wrong ...</p>
                <Popover>
                    <PopoverTrigger as-child>
                        <button class="absolute -bottom-18 py-2 px-5 border-2 border-zinc-700 hover:bg-zinc-700 rounded-md cursor-pointer transition-all ease-out duration-100">
                            Show error
                        </button>
                    </PopoverTrigger>
                    <PopoverContent class="w-175 flex overflow-x-auto border-2 border-red-800 bg-zinc-950">
                        {{ error }}
                    </PopoverContent>
                </Popover>
            </template>
        </div>
        <!-- <div class="relative"> -->
            <div class="flex flex-col items-center gap-3" :class="success ? '' : 'invisible'">
                <button class="flex items-center gap-2 bg-green-600 rounded-full py-2 px-5 text-2xl hover:bg-green-700 active:bg-green-800 disabled:bg-green-950 disabled:text-zinc-500
                cursor-pointer disabled:cursor-default transition-all ease-out duration-100" :disabled="!success" @click="onDownloadButtonClick">
                    <Download class="w-6 h-6" />
                    Download
                    <a id="downloadLink" :href="fileUrl" :download="fileName" hidden></a>
                </button>
                <p class="text-zinc-500">The download should start automatically</p>
            </div>
            <!-- <div v-if="!success" class="absolute inset-x-0 top-0 flex justify-center">
                <Popover>
                    <PopoverTrigger as-child>
                        <button class="py-2 px-5 border-2 border-zinc-700 hover:bg-zinc-700 rounded-md cursor-pointer transition-all ease-out duration-100">
                            Show error
                        </button>
                    </PopoverTrigger>
                    <PopoverContent class="w-175 flex overflow-x-auto border-2 border-red-800 bg-zinc-950">
                        {{ error }}
                    </PopoverContent>
                </Popover>
            </div>
        </div> -->
        <div class="text-center text-xl">
            <a href="/" @click.prevent="$emit('return');" class="underline hover:decoration-red-600 transition-all ease-out duration-200">Convert another link</a>
        </div>
    </div>
</template>
