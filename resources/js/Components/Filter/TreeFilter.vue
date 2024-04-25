<script setup>
import {router} from '@inertiajs/vue3';
import {omit} from "lodash";
import {ref} from "vue";
import {Popover, PopoverButton, PopoverPanel} from "@headlessui/vue";

const props = defineProps({
    options: Object,
    field: String,
    subField: String,
    defaultValue: String,
});

const filter = (value, subValue) => {
    const params = {
        ...omit(route().params, [props.field, props.subField]),
        page: 1,
    }

    if (value) params[props.field] = value;
    if (subValue) params[props.subField] = subValue;

    router.get(route(route().current()), params);
};

const categories = ref([]);

const activeOption = route().params[props.field] ?? props.defaultValue;
const activeSubOption = route().params[props.subField] ?? '';

const isActive = (value, subValue) => {
    if (value && subValue) return activeOption === value && activeSubOption === subValue;
    else if (value) return activeOption === value;

    return false;
};
</script>

<template>
    <Popover class="relative">
        <PopoverButton class="flex items-center space-x-1 px-4 py-2 bg-white border border-gray-300 rounded text-gray-600 shadow-sm hover:bg-gray-200 focus:bg-gray-200 outline-none transition ease-in-out">
            <span class="text-sm">{{ activeOption }}</span>
            <span v-if="activeSubOption" class="text-sm">&rarr; {{ activeSubOption }}</span>
        </PopoverButton>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel class="absolute z-10 w-full md:w-max md:max-h-96 overflow-y-auto">
                <div class="flex flex-col space-y-2 px-6 py-3 mt-1 bg-white border rounded shadow">
                    <a href="#" @click.prevent="filter()" :class="{ 'active': activeOption === defaultValue }">{{ defaultValue }}</a>
                    <div v-for="option in options">
                        <a href="#" @click.prevent="filter(option.value)" :class="{ 'active': isActive(option.value) }">{{ option.value }}</a>
                        <div v-if="option.subValues" class="ms-6 flex flex-col space-y-2">
                            <a href="#" @click.prevent="filter(option.value, subValue)" v-for="subValue in option.subValues" :class="{ 'active': isActive(option.value, subValue) }">
                                {{ subValue }}
                            </a>
                        </div>
                    </div>
                </div>
            </PopoverPanel>
        </transition>
    </Popover>
</template>

<style scoped>
    a:hover,
    a.active {
        @apply text-primary
    }

    a {
        @apply text-sm
    }
</style>
