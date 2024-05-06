<script setup>
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';

defineEmits(['close']);

const submit = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">Are you sure you want to logout?</p>

        <div class="flex items-center justify-between">
            <SecondaryButton @click="$emit('close')">Close</SecondaryButton>
            <PrimaryButton @click="submit">Confirm</PrimaryButton>
        </div>
    </div>
</template>
