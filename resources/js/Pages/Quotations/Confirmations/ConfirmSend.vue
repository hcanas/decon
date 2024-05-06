<script setup>
import {useForm} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['confirmed']);

const form = useForm({
    status: 'sent',
});

const confirm = () => {
    form.patch(route('admin.quotations.update', { quotation: props.data.id }), {
        onSuccess: () => emits('confirmed'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">You are about to send a quotation to {{ data.customer.email }}.</p>
        <div class="flex justify-end">
            <SecondaryButton @click="confirm()">
                Send Quotation
            </SecondaryButton>
        </div>
    </div>
</template>
