<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['confirmed']);

const form = useForm({
    status: 'delivered',
});

const confirm = () => {
    form.patch(route('admin.purchase_orders.update', { purchase_order: props.data.id }), {
        onSuccess: () => emits('confirmed'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="dark:text-gray-300">You are about to confirm the delivery of order #{{ data.reference_number }}. This will lock the record to prevent further modifications. Please double check the records before proceeding.</p>
        <div class="flex justify-end">
            <button @click="confirm()" class="text-white text-sm uppercase bg-green-600 hover:bg-green-500 px-4 py-1 rounded shadow-sm transition ease-in-out">
                Confirm Delivery
            </button>
        </div>
    </div>
</template>
