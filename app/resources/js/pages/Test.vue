<script setup lang="ts">
const props = defineProps<{
    link: string;
    format: string;
    quality: number;
}>();
console.log(props);
</script>


<template>
{{ props.link }}<br />{{ props.format }}<br />{{ props.quality }}
</template>
