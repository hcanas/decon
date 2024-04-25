<script setup>
import {ref} from 'vue';
import {router} from '@inertiajs/vue3';
import {indexOf, omit} from "lodash";
import {Popover, PopoverButton, PopoverPanel} from "@headlessui/vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const props = defineProps({
    options: Object,
    defaultValue: String,
    field: String,
});

const selected = ref(route().params?.brands?.split(',') ?? []);
const tempSelected = ref(route().params?.brands?.split(',') ?? []);

const resetTempSelected = () => tempSelected.value = selected.value;

const filter = selection => {
    const query = {
        ...(omit(route().params, [props.field])),
        page: 1,
    };

    if (selection && selection.length) query[props.field] = selection.join();

    router.get(route(route().current(), query));
};

const isActive = option => Boolean(indexOf(tempSelected.value, option) + 1);
</script>

<template>
    <Popover class="relative">
        <PopoverButton class="flex items-center space-x-2 px-4 py-2 bg-white border border-gray-300 rounded text-gray-600 shadow-sm hover:bg-gray-200 focus:bg-gray-200 outline-none transition ease-in-out">
            <span v-if="selected.length === 1" class="text-sm">{{ selected[0] }}</span>
            <span v-else-if="selected.length > 1" class="text-sm">{{ selected.length }} Selected</span>
            <span v-else class="text-sm">{{ defaultValue }}</span>
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
                    <div v-for="(option, key) in options">
                        <input type="checkbox" v-model="tempSelected" :id="key" :value="option" name="brandSelection" class="hidden" />
                        <label :for="key" :class="{ 'active': isActive(option) }" class="text-sm px-3 py-1 border rounded bg-gray-50 cursor-pointer transition ease-in-out">{{ option }}</label>
                    </div>
                </div>

                <div v-if="options.length" class="flex justify-between">
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
