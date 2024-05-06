<script setup>
import {useForm} from "@inertiajs/vue3";
import DangerButton from "@/Components/Button/DangerButton.vue";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['confirmed']);

const form = useForm({
    status: 'cancelled',
});

const confirm = () => {
    form.patch(route('admin.purchase_orders.update', { purchase_order: props.data.id }), {
        onSuccess: () => emits('confirmed'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">You are about to cancel order #{{ data.reference_number }}. This will lock the record to prevent further modifications. Please double check the records before proceeding.</p>
        <div class="flex justify-end">
            <DangerButton @click="confirm()">
                Cancel Order
            </DangerButton>
        </div>
    </div>
</template>
