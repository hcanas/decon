<script setup>
import {router} from '@inertiajs/vue3';
import {omit} from "lodash";
import {ref} from "vue";
import {Popover, PopoverButton, PopoverPanel} from "@headlessui/vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faChevronDown} from "@fortawesome/free-solid-svg-icons";

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
        <PopoverButton class="w-full md:w-fit flex items-center justify-between space-x-2 px-4 py-2 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-950 hover:bg-neutral-100 dark:hover:bg-neutral-950 rounded outline-none transition ease-in-out">
            <div class="flex items-center space-x-1">
                <span class="text-sm">{{ activeOption }}</span>
                <span v-if="activeSubOption" class="text-sm">&rarr; {{ activeSubOption }}</span>
            </div>
            <FontAwesomeIcon :icon="faChevronDown" class="text-sm" />
        </PopoverButton>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel class="absolute mt-1 z-10 w-full md:w-max md:max-h-96 shadow-lg ring-1 ring-black/5 rounded overflow-y-auto">
                <div class="flex flex-col space-y-2 px-6 py-3 mt-1 bg-white dark:bg-neutral-900 rounded shadow">
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
        </Transition>
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
