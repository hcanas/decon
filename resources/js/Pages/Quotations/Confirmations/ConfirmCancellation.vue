<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['confirmed']);

const form = useForm({
    status: 'cancelled',
});

const confirm = () => {
    form.patch(route('admin.quotations.update', { quotation: props.data.id }), {
        onSuccess: () => emits('confirmed'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="dark:text-gray-300">You are about to cancel this quotation. This will lock the record to prevent further modifications. Please double check the records before proceeding.</p>
        <div class="flex justify-end">
            <button @click="confirm()" class="text-white text-sm uppercase bg-red-600 hover:bg-red-500 px-4 py-1 rounded shadow-sm transition ease-in-out">
                Cancel Quotation
            </button>
        </div>
    </div>
</template>
