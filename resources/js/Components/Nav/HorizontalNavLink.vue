<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'text-primary font-medium transition duration-0 ease-in-out'
        : 'text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary font-medium transition duration-0 ease-in-out'
);
</script>

<template>
    <Link :href="href ?? ''" :class="classes">
        <slot />
    </Link>
</template>
