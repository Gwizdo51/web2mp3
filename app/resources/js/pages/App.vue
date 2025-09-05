<script setup lang="ts">
import { ref, computed } from "vue";
import Layout from "@/layouts/Layout.vue";
import Form from "@/components/my_components/Form.vue";
import Processing from "@/components/my_components/Processing.vue";
import Result from "@/components/my_components/Result.vue";
import { ResultData } from '@/types';

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
const downloadID = ref<string>("");
const success = ref<boolean>(true);
const fileName = ref<string>("");

// methods

async function submitForm(): Promise<void> {
    console.log("form submitted");
    console.log(`inputLink: ${inputLink.value}`);
    console.log(`inputFormat: ${inputFormat.value}`);
    console.log(`inputQuality: ${inputQuality.value}`);
    // tabIndex.value = 1;
    // const formElements = document.forms[0].elements as FormElements<"link" | "format" | "quality">;
    // console.log(formElements.link.value);
    // console.log(formElements.format.value);
    // console.log(formElements.quality.value);
    formProcessing.value = true;
    // make a JSON containing the form data
    const formData = {
        link: inputLink.value,
        format: inputFormat.value,
        quality: inputQuality.value
    };
    // post the JSON to /api/submit
    const response = await fetch("/api/submit", {
        method: "post",
        headers: {
            "Content-Type": "application/json; charset=utf-8",
            "Accept": "application/json; charset=utf-8"
        },
        body: JSON.stringify(formData)
    });
    formProcessing.value = false;
    if (response.ok) {
        const jsonResponse = await response.json();
        console.log("/api/submit/ response :", jsonResponse);
        // reset the link input field
        linkError.value = "";
        inputLink.value = "";
        // update the props for the "Processing" component
        downloadID.value = jsonResponse.data.channelName;
        // display the "Processing" component
        tabIndex.value = 1;
    }
    else if (response.status === 422) {
        const jsonResponse = await response.json();
        console.log(jsonResponse);
        if (Object.keys(jsonResponse.errors).includes("link")) {
            linkError.value = jsonResponse.errors.link[0];
        }
    }
    else {
        console.error("The request failed for an unknown reason.", response);
    }
}

function onLinkProcessed(data: ResultData) {
    console.log("LinkProcessed data :", data);
    // downloadID.value = "";
    success.value = data.success;
    fileName.value = data.fileName ?? "";
    tabIndex.value = 2;
}

function onReturn() {
    tabIndex.value = 0;
    downloadID.value = "";
    fileName.value = "";
}
</script>


<template>
    <Layout :title="tabTitle">
        <Form v-if="tabIndex === 0" v-model:input-link="inputLink" v-model:input-format="inputFormat" v-model:input-quality="inputQuality"
        :link-error="linkError" :processing="formProcessing" @submit="submitForm" />
        <Processing v-else-if="tabIndex === 1" :download-id="downloadID" @link-processed="onLinkProcessed" />
        <Result v-else-if="tabIndex === 2" :success="success" :file-name="fileName" @return="onReturn" />
    </Layout>
</template>
