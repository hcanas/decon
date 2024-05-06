<script setup>
import DangerButton from '@/Components/Button/DangerButton.vue';
import {ref} from "vue";

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['success', 'close']);

const deleteFailed = ref(false);

const submit = () => {
    window.axios.delete(route('admin.users.destroy', { user: props.user.id }))
        .then(() => emit('success'))
        .catch(() => deleteFailed.value = true);
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">
            You are about to delete
            <span class="px-1 py-0.5 bg-neutral-100 dark:bg-neutral-700 rounded">{{ user.email }}</span>.
            You can not delete users with linked records.
        </p>

        <div class="flex items-center justify-end space-x-3">
            <span v-if="deleteFailed" class="text-red-600 text-sm">Failed to delete user.</span>
            <DangerButton @click="submit">Delete</DangerButton>
        </div>
    </div>
</template>
