<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['confirmed']);

const form = useForm({
    status: 'confirmed',
});

const confirm = () => {
    form.patch(route('admin.quotations.update', { quotation: props.data.id }), {
        onSuccess: () => emits('confirmed'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="dark:text-gray-300">You are about to confirm this quotation. This record will be locked to prevent further modifications and a purchase order will be created. </p>
        <div class="flex justify-end">
            <button @click="confirm()" class="text-white text-sm uppercase bg-green-600 hover:bg-green-500 px-4 py-1 rounded shadow-sm transition ease-in-out">
                Confirm Quotation
            </button>
        </div>
    </div>
</template>
