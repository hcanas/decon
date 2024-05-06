<script setup>
import Head from "@/Components/Head.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import {useFormatter} from "@/Composables/formatter.js";

defineProps({
    user: Object,
    activities: Object,
});

const { formatDateTime } = useFormatter();
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="user.email + ' Activity Log'" />

        <div class="flex flex-col space-y-3 mt-12">
            <p v-for="activity in activities.data" class="flex items-center space-x-3">
                <span>&ndash;</span>
                <span>{{ activity.description }}</span>
                <span class="text-xs">{{ formatDateTime(activity.created_at) }}</span>
            </p>

            <Pagination
                :next-page-url="activities.next_page_url"
                :prev-page-url="activities.prev_page_url"
                :first-page-url="activities.first_page_url"
                :last-page-url="activities.last_page_url"
                :total-records="activities.total"
                :to="activities.to"
                :from="activities.from"
                :per-page="activities.per_page"
                :current-page="activities.current_page"
            />
        </div>
    </AuthenticatedLayout>
</template>
