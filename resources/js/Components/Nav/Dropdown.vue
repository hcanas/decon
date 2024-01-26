<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { ChevronDownIcon } from '@heroicons/vue/24/solid';
</script>

<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton as="template">
                <button>
                    <div class="flex items-center space-x-2">
                        <span class="text-gray-600 text-sm">{{ $page.props.auth.user.name }}</span>
                        <ChevronDownIcon class="w-4 h-4 dark:text-gray-100" />
                    </div>
                </button>
            </MenuButton>
        </div>
        <Transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-out"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems class="absolute top-8 right-0 w-48 p-3 rounded grid gap-2 bg-white border shadow-lg outline-none">
                <slot />
            </MenuItems>
        </Transition>
    </Menu>
</template>