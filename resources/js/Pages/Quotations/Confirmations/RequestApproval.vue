<script setup>
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['success']);

const form = useForm({
    status: 'for approval',
});

const confirm = () => {
    form.patch(route('admin.quotations.update', { quotation: props.data.id }), {
        onSuccess: () => emits('success'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">You are about to request for approval of the quotation for {{ data.customer.email }}. This will lock the record to prevent further modifications.</p>
        <div class="flex justify-end">
            <PrimaryButton @click="confirm()">
                Request Approval
            </PrimaryButton>
        </div>
    </div>
</template>
