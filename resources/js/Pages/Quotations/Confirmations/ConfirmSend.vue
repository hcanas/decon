<script setup>
import {useForm} from "@inertiajs/vue3";

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
        <p class="dark:text-gray-300">You are about to send a quotation to {{ data.customer.email }}.</p>
        <div class="flex justify-end">
            <button @click="confirm()" class="text-white text-sm uppercase bg-blue-600 hover:bg-blue-500 px-4 py-1 rounded shadow-sm transition ease-in-out">
                Send Quotation
            </button>
        </div>
    </div>
</template>
