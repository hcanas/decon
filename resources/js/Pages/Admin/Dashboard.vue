<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Head from "@/Components/Head.vue";
</script>

<template>
    <AuthenticatedLayout>
        <div class="px-3 md:px-12 py-6 md:py-12">
            <Head :title="'Dashboard'" />

            <div class="text-gray-600">You're logged in!</div>
        </div>
    </AuthenticatedLayout>
</template>
