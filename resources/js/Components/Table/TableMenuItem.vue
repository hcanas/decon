<script setup>
import {MenuItem} from "@headlessui/vue";
</script>

<template>
    <MenuItem as="button" class="px-3 py-2 rounded text-xs text-left hover:bg-neutral-200 dark:hover:bg-neutral-900 transition ease-in-out">
        <slot />
    </MenuItem>
</template>
