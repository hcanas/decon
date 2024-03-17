<script setup>
import { router } from '@inertiajs/vue3';
import {map, omit} from "lodash";
import {onMounted, ref} from "vue";
import {Popover, PopoverButton, PopoverPanel} from "@headlessui/vue";

const filter = (category, subCategory) => {
    const params = {
        ...omit(route().params, ['category', 'sub_category']),
        page: 1,
    }

    if (category) params['category'] = category;
    if (subCategory) params['sub_category'] = subCategory;

    router.get(route(route().current()), params);
};

const categories = ref([]);

const activeCategory = route().params?.category ?? 'all';
const activeSubCategory = route().params?.sub_category ?? null;

const isActive = (category, subCategory) => {
    if (category && subCategory) return activeCategory === category && activeSubCategory === subCategory;
    else if (category) return activeCategory === category;

    return false;
};

onMounted(() => {
    window.axios.get(route('json.product.categories'))
        .then(response => {
            categories.value = map(response.data, category => {
                return {
                    value: category.value,
                    subCategories: map(category.sub_categories, 'value'),
                };
            });
        });
});
</script>

<template>
    <Popover class="relative">
        <PopoverButton class="group w-full md:w-auto inline-flex items-center border rounded-md text-gray-600 bg-white hover:bg-gray-100 px-4 py-2 text-xs font-medium uppercase focus:outline-none">
            <span>Category:</span>
            <span class="mx-1">{{ activeCategory }}</span>
            <span v-if="activeSubCategory">- {{ activeSubCategory }}</span>
        </PopoverButton>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel class="absolute z-10 w-full md:w-auto">
                <div class="flex flex-col space-y-2 px-6 py-3 mt-1 bg-white border rounded shadow">
                    <a href="#" @click.prevent="filter()" :class=" { 'active': isActive('all') }">All Products</a>
                    <div v-for="category in categories">
                        <a href="#" @click.prevent="filter(category.value)" :class="{ 'active': isActive(category.value) }">{{ category.value }}</a>
                        <div v-if="category.subCategories" class="ms-3 flex flex-col space-y-2">
                            <a href="#" @click.prevent="filter(category.value, subCategory)" v-for="subCategory in category.subCategories" :class="{ 'active': isActive(category.value, subCategory) }">
                                {{ subCategory }}
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
</style>
