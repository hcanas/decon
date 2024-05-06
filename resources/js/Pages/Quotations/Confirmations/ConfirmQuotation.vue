<script setup>
import {useForm} from "@inertiajs/vue3";
import SuccessButton from "@/Components/Button/SuccessButton.vue";

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
        <p class="">You are about to confirm this quotation. This record will be locked to prevent further modifications and a purchase order will be created. </p>
        <div class="flex justify-end">
            <SuccessButton @click="confirm()">
                Confirm Quotation
            </SuccessButton>
        </div>
    </div>
</template>
