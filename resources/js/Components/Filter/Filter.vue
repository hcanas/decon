<script setup>
import {ref, watch} from 'vue';
import TextInput from "@/Components/Form/TextInput.vue";
import { Listbox, ListboxButton, ListboxOption, ListboxOptions } from "@headlessui/vue";
import {debounce, find, omit} from "lodash";
import {router} from '@inertiajs/vue3';
import CategoryFilter from "@/Components/Filter/CategoryFilter.vue";
import BrandFilter from "@/Components/Filter/BrandFilter.vue";

const keyword = ref(route().params?.keyword ?? '');

const showModal = ref(false);

const sortOptions = [
    { value: 'relevance', label: 'Relevance' },
    { value: 'lowest_price', label: 'Lowest Price' },
    { value: 'highest_price', label: 'Highest Price' },
];

const activeSortOption = ref(find(sortOptions, ['value', route().params?.sort ?? 'relevance']));

watch(
    () => keyword.value,
    debounce(val => {
        const query = {
            ...(omit(route().params, ['keyword'])),
            page: 1,
        };

        if (Boolean(val)) query['keyword'] = val;

        router.get(route(route().current(), query));
    }, 300),
);

watch(() => activeSortOption.value, sortOption => {
    const query = {
        ...(omit(route().params, ['sort'])),
        page: 1,
    };

    if (sortOption.value !== 'relevance') query['sort'] = sortOption.value;

    router.get(route(route().current(), query));
});
</script>

<template>
    <div class="flex flex-col space-y-3 py-6">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-3 space-y-1 md:space-y-0">
            <TextInput type="search" v-model="keyword" class="text-sm md:w-72 py-1.5" placeholder="Search keyword..." autofocus />
            <CategoryFilter />
            <BrandFilter />
            <Listbox v-model="activeSortOption">
                <div class="relative">
                    <ListboxButton class="w-full px-4 py-2 bg-white border border-gray-300 rounded-md font-medium uppercase text-xs text-gray-600 tracking-wider shadow-sm hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-200 outline-none disabled:opacity-25 transition ease-in-out duration-150">
                        <span class="block truncate text-left">Sort By: {{ activeSortOption.label }}</span>
                    </ListboxButton>

                    <transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="translate-y-1 opacity-0"
                        enter-to-class="translate-y-0 opacity-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="translate-y-0 opacity-100"
                        leave-to-class="translate-y-1 opacity-0"
                    >
                        <ListboxOptions
                            class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                        >
                            <ListboxOption
                                v-slot="{ active, selected }"
                                v-for="option in sortOptions"
                                :key="option.value"
                                :value="option"
                                class="px-4 py-1 cursor-pointer hover:text-primary transition ease-in-out"
                                :class="{ 'text-primary': activeSortOption.value === option.value }"
                            >
                                {{ option.label }}
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
    </div>
</template>
