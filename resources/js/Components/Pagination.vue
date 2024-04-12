<script setup>
import {Link} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faAngleLeft, faAngleRight, faAnglesLeft, faAnglesRight} from "@fortawesome/free-solid-svg-icons";

defineProps({
    currentPage: Number,
    perPage: Number,
    from: Number,
    to: Number,
    totalRecords: Number,
    prevPageUrl: String,
    nextPageUrl: String,
    firstPageUrl: String,
    lastPageUrl: String,
});
</script>

<template>
    <div v-if="totalRecords > perPage" class="w-full md:w-auto flex flex-col md:flex-row items-center justify-between md:space-x-3 md:order-last mt-6">
        <div v-if="totalRecords > 1" class="order-2 text-sm text-gray-400 md:order-first">Showing {{ from }} to {{ to }} of {{ totalRecords }} results</div>

        <div class="flex items-center space-x-2">
            <Link v-if="firstPageUrl && currentPage > 1" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="firstPageUrl">
                <FontAwesomeIcon :icon="faAnglesLeft" />
            </Link>

            <Link v-if="prevPageUrl" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="prevPageUrl">
                <FontAwesomeIcon :icon="faAngleLeft" />
            </Link>

            <span class="text-sm text-gray-400">Page {{ currentPage }} of {{ Math.ceil(totalRecords / perPage) }}</span>

            <Link v-if="nextPageUrl" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="nextPageUrl">
                <FontAwesomeIcon :icon="faAngleRight" />
            </Link>

            <Link v-if="lastPageUrl && nextPageUrl" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="lastPageUrl">
                <FontAwesomeIcon :icon="faAnglesRight" />
            </Link>
        </div>
    </div>
</template>
