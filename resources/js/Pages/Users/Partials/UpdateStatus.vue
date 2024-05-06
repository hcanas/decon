<script setup>
import { useForm } from '@inertiajs/vue3';
import SuccessButton from '@/Components/Button/SuccessButton.vue';
import DangerButton from '@/Components/Button/DangerButton.vue';

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
    form.patch(route('admin.users.update', { user: props.user.id }), {
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">
            You are about to {{ user.status === 'blocked' ? 'unblock' : 'block' }}
            <span class="px-1 py-0.5 bg-neutral-100 dark:bg-neutral-700 rounded">{{ user.email }}</span>.
            <span v-if="user.status !== 'blocked'">They will be logged out if they are currently online.</span>
        </p>

        <div class="flex items-center justify-end">
            <DangerButton v-if="user.status !== 'blocked'" @click="submit">Block</DangerButton>
            <SuccessButton v-else-if=" user.status === 'blocked'" @click="submit">Unblock</SuccessButton>
        </div>
    </div>
</template>
