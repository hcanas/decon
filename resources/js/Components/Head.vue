<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head :title="title" />

    <div class="flex items-center justify-between">
        <h1 class="text-2xl dark:text-gray-300 font-semibold">{{ title }}</h1>

        <slot name="actions" />
    </div>
</template>
