<script setup lang="ts">
import { LoaderCircle } from "lucide-vue-next";
import { useEchoPublic } from "@laravel/echo-vue";
import { QueueUpdatedMessage, ResultData } from "@/types";
import { ref, onMounted } from "vue";

interface StatusApiResponseData {
    queuePosition: number;
    state: number;
    fileName: Nullable<string>;
    error: Nullable<string>;
}

// state
const initialized = ref<boolean>(false);
const displayedText = ref<string>("Requesting status ...");

// props
const {downloadId} = defineProps<{
    downloadId: string;
}>();

// emits
const emit = defineEmits<{
    linkProcessed: [data: ResultData];
}>();

// websocket events
useEchoPublic(
    downloadId,
    "LinkProcessed",
    (message: ResultData) => {
        emit("linkProcessed", message);
    }
);
useEchoPublic(
    downloadId,
    "QueueUpdated",
    (message: QueueUpdatedMessage) => {
        console.log("QueueUpdated message :", message);
        initialized.value = true;
        if (message.queuePosition === 0) {
            displayedText.value = "Processing ...";
        }
        else {
            displayedText.value = `Download queued (position: ${message.queuePosition}) ...`;
        }
    }
);

// get the download status when the component is mounted (in the case of a slow connection)
onMounted(async () => {
    // get the JSON from /api/status
    const response = await fetch(`/api/status/${downloadId}`, {
        headers: {
            "Accept": "application/json; charset=utf-8"
        }
    });
    if (response.ok) {
        const jsonResponse = await response.json();
        console.log(`/api/status/${downloadId} response :`, jsonResponse);
        if (!initialized.value) {
            processApiResponse(jsonResponse.data);
            initialized.value = true;
        }
    }
    else if (response.status === 404) {
        const jsonResponse = await response.json();
        console.error("The download ID is incorrect.", jsonResponse);
    }
    else {
        console.error("The request failed for an unknown reason.", response);
    }
});

function processApiResponse(jsonResponseData: StatusApiResponseData) {
    switch (jsonResponseData.state) {
        case 1: // waiting
            displayedText.value = `Download queued (position: ${jsonResponseData.queuePosition}) ...`;
            break;
        case 2: // processing
            displayedText.value = "Processing ...";
            break;
        default: // succeeded or failed
            emit("linkProcessed", {
                success: jsonResponseData.state === 3,
                fileName: jsonResponseData.fileName,
                error: jsonResponseData.error
            });
    }
}
</script>


<template>
    <div class="flex flex-col justify-center items-center gap-5">
        <LoaderCircle strokeWidth="1" class="w-25 h-25 animate-spin text-red-800" />
        <p class="text-xl text-center">{{ displayedText }}</p>
    </div>
</template>
