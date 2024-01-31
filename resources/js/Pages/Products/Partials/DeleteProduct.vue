<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import DangerButton from '@/Components/Button/DangerButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['success', 'close']);

const canDelete = ref(false);

const timer = ref(5);

const submit = () => {
    router.delete(route('products.destroy', { product: props.data.id }), {
        onSuccess: () => emit('success'),
    });
};

onMounted(() => {
    const intervalHandler = setInterval(() => {
        timer.value--;
    }, 1000);

    const timeoutHandler = setTimeout(() => {
        canDelete.value = true;
        clearInterval(intervalHandler);
        clearTimeout(timeoutHandler);
    }, 5000);
});
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="text-gray-600">
            You are about to delete
            <span class="px-1 py-0.5 bg-gray-100 rounded">{{ data.name }}</span>.
            Deletion will fail if there are records attached to it. If you wish to hide this product from public view, simply update the stock to 0.
        </p>

        <div class="flex items-center justify-between">
            <SecondaryButton @click="$emit('close')">Close</SecondaryButton>
            <DangerButton v-if="canDelete" @click="submit">Delete</DangerButton>
            <span v-else class="text-sm text-gray-500">You can delete in {{ timer }}.</span>
        </div>
    </div>
</template>
