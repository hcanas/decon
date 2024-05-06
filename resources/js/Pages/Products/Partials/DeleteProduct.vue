<script setup>
import { router } from '@inertiajs/vue3';
import DangerButton from '@/Components/Button/DangerButton.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['success', 'close']);

const submit = () => {
    router.delete(route('admin.products.destroy', { product: props.data.id }), {
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">
            You are about to delete
            <span class="px-1 py-0.5 bg-neutral-100 dark:bg-neutral-700 rounded">{{ data.name }}</span>.
            Deletion will fail if there are records attached to it. If you wish to hide this product from public view, simply update the stock to 0.
        </p>

        <div class="flex items-center justify-end">
            <DangerButton @click="submit">Delete</DangerButton>
        </div>
    </div>
</template>
