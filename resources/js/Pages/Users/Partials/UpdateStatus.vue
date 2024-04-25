<script setup>
import { useForm } from '@inertiajs/vue3';
import SuccessButton from '@/Components/Button/SuccessButton.vue';
import DangerButton from '@/Components/Button/DangerButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['success', 'close']);

const form = useForm({
    status: props.user.status === 'blocked' ? 'active' : 'blocked',
});

const submit = () => {
    form.patch(route('admin.users.update', { user: props.user.id }));
    emit('success');
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="text-gray-600 dark:text-gray-300">
            You are about to {{ user.status === 'blocked' ? 'unblock' : 'block' }}
            <span class="px-1 py-0.5 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">{{ user.email }}</span>.
            <span v-if="user.status !== 'blocked'">They will be logged out if they are currently online.</span>
        </p>

        <div class="flex items-center justify-between">
            <SecondaryButton @click="$emit('close')">Close</SecondaryButton>
            <DangerButton v-if="user.status !== 'blocked'" @click="submit">Block</DangerButton>
            <SuccessButton v-else-if=" user.status === 'blocked'" @click="submit">Unblock</SuccessButton>
        </div>
    </div>
</template>
