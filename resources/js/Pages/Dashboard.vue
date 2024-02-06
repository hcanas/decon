<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Head from "@/Components/Head.vue";
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard'" />

        <div class="px-3 md:px-12">
            <div class="text-gray-600">You're logged in!</div>
        </div>
    </AuthenticatedLayout>
</template>
