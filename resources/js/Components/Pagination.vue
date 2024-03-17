<script setup>
import {Link} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faAngleLeft, faAngleRight} from "@fortawesome/free-solid-svg-icons";

defineProps({
    currentPage: {
        type: Number,
        required: true,
    },
    perPage: {
        type: Number,
        required: true,
    },
    from: {
        type: Number,
        required: true,
    },
    to: {
        type: Number,
        required: true,
    },
    totalRecords: {
        type: Number,
        required: true,
    },
    prevPageUrl: {
        type: String,
        required: true,
    },
    nextPageUrl: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div v-if="totalRecords > perPage" class="w-full md:w-auto flex flex-col md:flex-row items-center justify-between md:space-x-3 md:order-last mt-6">
        <div v-if="totalRecords > 1" class="order-2 text-sm text-gray-400 md:order-first">Showing {{ from }} to {{ to }} of {{ totalRecords }} results</div>

        <div class="flex items-center space-x-2">
            <Link v-if="prevPageUrl" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="prevPageUrl">
                <FontAwesomeIcon :icon="faAngleLeft" />
            </Link>

            <span class="text-sm text-gray-400">Page {{ currentPage }} of {{ Math.ceil(totalRecords / perPage) }}</span>

            <Link v-if="nextPageUrl" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="nextPageUrl">
                <FontAwesomeIcon :icon="faAngleRight" />
            </Link>
        </div>
    </div>
</template>
