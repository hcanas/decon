<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { MenuItem } from '@headlessui/vue';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'text-primary bg-primary-light/20 hover:text-primary-dark hover:bg-primary-light/50 px-2 py-1 rounded transition duration-150 ease-in-out'
        : 'text-gray-600 hover:text-primary hover:bg-primary-light/20 rounded px-2 py-1 transition duration-150 ease-in-out'
);
</script>

<template>
    <MenuItem>
        <Link
            :href="href"
            :class="classes"
        >
            <slot />
        </Link>
    </MenuItem>
</template>
