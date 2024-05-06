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
        ? 'text-sm text-primary dark:text-primary bg-primary-100 dark:bg-primary-200 font-medium px-6 py-3 md:rounded transition ease-in-out'
        : 'text-sm dark:text-white hover:text-primary dark:hover:text-primary font-medium hover:bg-primary-100 dark:hover:bg-primary-200 px-6 py-3 md:rounded transition ease-in-out'
);
</script>

<template>
    <Link :href="href ?? ''" :class="classes">
        <slot />
    </Link>
</template>
