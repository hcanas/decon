<script setup>
import {onMounted, ref, watch} from 'vue';
import { router } from '@inertiajs/vue3';
import {indexOf, map, omit} from "lodash";
import {Popover, PopoverButton, PopoverPanel} from "@headlessui/vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const brands = ref([]);
const selected = ref(route().params?.brands?.split(',') ?? []);
const tempSelected = ref(route().params?.brands?.split(',') ?? []);

const resetTempSelected = () => tempSelected.value = selected.value;

const filter = selection => {
    const query = {
        ...(omit(route().params, ['brands'])),
        page: 1,
    };

    if (selection && selection.length) query['brands'] = selection.join();

    router.get(route(route().current(), query));
};

const isActive = brand => Boolean(indexOf(tempSelected.value, brand) + 1);

onMounted(() => {
    window.axios.get(route('json.brands'))
        .then(response => brands.value = map(response.data, 'value'));
});
</script>

<template>
    <Popover class="relative">
        <PopoverButton class="group w-full md:w-auto inline-flex items-center border rounded-md text-gray-600 bg-white hover:bg-gray-100 px-4 py-2 text-xs font-medium uppercase focus:outline-none">
            <span class="me-1">Brands:</span>
            <span v-if="selected.length === 1">{{ selected[0] }}</span>
            <span v-else-if="selected.length > 1">{{ selected.length }} Selected</span>
            <span v-else>All</span>
        </PopoverButton>

        <transition
            @after-leave="resetTempSelected()"
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="translate-y-1 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="translate-y-1 opacity-0"
        >
            <PopoverPanel class="absolute z-10 md:w-[40rem] max-h-96 md:max-h-none flex flex-col space-y-6 px-6 py-3 mt-1 bg-white border rounded shadow overflow-y-auto">
                <div class="flex gap-x-1.5 gap-y-3 flex-wrap">
                    <div v-for="(brand, key) in brands">
                        <input type="checkbox" v-model="tempSelected" :id="key" :value="brand" name="brandSelection" class="hidden" />
                        <label :for="key" :class="{ 'active': isActive(brand) }" class="text-sm px-3 py-1 border rounded bg-gray-50 cursor-pointer transition ease-in-out">{{ brand }}</label>
                    </div>
                </div>

                <div class="flex justify-between">
                    <SecondaryButton @click="filter()">Reset</SecondaryButton>
                    <PrimaryButton @click="filter(tempSelected)">Apply</PrimaryButton>
                </div>
            </PopoverPanel>
        </transition>
    </Popover>
</template>

<style scoped>
    label:hover,
    label.active {
        @apply bg-primary text-white
    }
</style>
