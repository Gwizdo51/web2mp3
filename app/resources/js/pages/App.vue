<script setup lang="ts">
import { ref, computed } from "vue";
import Layout from "@/layouts/Layout.vue";
import Form from "@/components/my_components/Form.vue";
import Processing from "@/components/my_components/Processing.vue";
import Result from "@/components/my_components/Result.vue";
import { WSMessage } from '@/types';

// static data
const tabTitles: string[] = [
    "Landing",
    "Processing",
    "Result"
];

// state variables
const tabIndex = ref<number>(0);
const tabTitle = computed<string>(() => tabTitles[tabIndex.value]);
const inputLink = ref<string>("");
const inputFormat = ref<string>("mp3");
const inputQuality = ref<number>(10);
const linkError = ref<string>("");
const formProcessing = ref<boolean>(false);
const success = ref<boolean>(true);
const fileName = ref<string>("XXX.xxx");

// methods

async function submitForm(): Promise<void> {
    console.log("form submitted");
    console.log(`inputLink: ${inputLink.value}`);
    console.log(`inputFormat: ${inputFormat.value}`);
    console.log(`inputQuality: ${inputQuality.value}`);
    tabIndex.value = 1;
}

function onMessage(message: WSMessage) {
    console.log(message);
    success.value = message.success;
    tabIndex.value = 2;
}
</script>


<template>
    <Layout :title="tabTitle">
        <Form v-if="tabIndex === 0" v-model:input-link="inputLink" v-model:input-format="inputFormat" v-model:input-quality="inputQuality"
        :link-error="linkError" :processing="formProcessing" @submit="submitForm" />
        <Processing v-else-if="tabIndex === 1" channel-name="channel-name" @message="onMessage" />
        <Result v-else-if="tabIndex === 2" :success="success" :file-name="fileName" @return="tabIndex = 0" />
    </Layout>
</template>
