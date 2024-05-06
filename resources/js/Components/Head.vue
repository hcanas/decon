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

    <div class="flex flex-col md:flex-row items-center md:justify-between space-y-3">
        <h1 class="text-2xl font-semibold">{{ title }}</h1>
        <slot name="actions" />
    </div>
</template>
