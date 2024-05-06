<script setup>
import {Menu, MenuButton, MenuItems, MenuItem} from "@headlessui/vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faEllipsis} from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <Menu as="div" class="relative">
        <MenuButton class="absolute top-0 right-2 md:static text-sm z-0 hover:text-neutral-600 dark:hover:text-neutral-200 transition ease-in-out">
            <FontAwesomeIcon :icon="faEllipsis" class="w-3 h-3" />
        </MenuButton>

        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems as="div" class="absolute top-6 right-0 p-1 bg-neutral-50 dark:bg-neutral-800 border dark:border-neutral-600 shadow rounded z-10 w-max flex flex-col">
                <slot />
            </MenuItems>
        </Transition>
    </Menu>
</template>
