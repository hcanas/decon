<script setup>
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faXmark} from "@fortawesome/free-solid-svg-icons";

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    width: String,
});

defineEmits(['close']);
</script>

<template>
    <TransitionRoot appear as="template" :show="show">
        <Dialog as="div" class="relative z-50">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95">
                        <DialogPanel :class="width === 'full' ? 'max-w-7xl' : 'max-w-lg'" class="w-full transform overflow-hidden rounded-2xl bg-white dark:bg-neutral-800 p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle>
                                <div class="flex items-start justify-between space-x-6">
                                    <h3 class="mb-4 font-medium leading-6">
                                        <slot name="title" />
                                    </h3>
                                    <button @click="$emit('close')" class=""><FontAwesomeIcon :icon="faXmark" /></button>
                                </div>
                            </DialogTitle>
                            <slot />
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
  </template>
