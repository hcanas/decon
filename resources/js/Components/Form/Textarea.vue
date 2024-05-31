<script setup>
const model = defineModel({
    type: String,
    required: true,
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        class="dark:bg-neutral-900 border-neutral-300 dark:border-neutral-950 focus:border-primary-300 dark:focus:border-primary focus:ring-0 rounded-md"
        v-model="model"
    ></textarea>
</template>
