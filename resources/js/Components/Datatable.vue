<script setup>
import { ref, computed, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import TextInput from './Form/TextInput.vue';
import { camelCase, pickBy, debounce } from 'lodash';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faMagnifyingGlass, faAngleLeft, faAngleRight, faSortUp, faSortDown } from "@fortawesome/free-solid-svg-icons";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    filters: Object,
    columns: {
        type: Array,
        required: true,
    },
});

const filters = ref({
    per_page: props.data?.per_page ?? 10,
    sort_field: props.filters?.sort_field ?? '',
    sort_order: props.filters?.sort_order ?? '',
    query: props.filters?.query ?? '',
});

const sort = computed(() => {
    if (props.filters?.sort_field) {
        return `${props.filters.sort_field}:${props.filters.sort_order}`;
    }

    return '';
});

watch(() => filters.value.sort_field, () => filters.value.sort_order = 'asc');

watch(
    () => filters.value,
    debounce(() => {
        router.get(props.data.path, pickBy(filters.value), { preserveScroll: true });
    }, 300),
    { deep: true },
);
</script>

<template>
    <div class="flex flex-col space-y-6 md:space-y-3 pb-6">
        <div class="flex flex-col md:flex-row md:justify-between space-y-1 md:space-y-0 md:space-x-1">
            <form>
                <div class="relative z-0">
                    <FontAwesomeIcon :icon="faMagnifyingGlass" class="absolute top-3 left-3 text-gray-600" />
                    <TextInput
                        type="search"
                        v-model="filters.query"
                        placeholder="Start searching..."
                        :class="'md:w-96 pl-9 w-full'"
                        autofocus
                    />
                </div>
            </form>

            <div class="flex items-center space-x-1">
                <select v-model="filters.sort_field" class="flex-grow rounded outline-none border-gray-300 focus:border-primary-200 focus:ring-primary-200">
                    <option value="">Relevance</option>
                    <template v-for="column in columns">
                        <option v-if="column.sortable" :value="column.field" :key="column.field">{{ column.title }}</option>
                    </template>
                </select>
                <select v-if="filters.sort_field" v-model="filters.sort_order" class="flex-shrink-0 rounded outline-none border-gray-300 focus:border-primary-200 focus:ring-primary-200">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <table class="table-auto border-separate border-spacing-0">
            <thead>
                <tr>
                    <th class="hidden md:table-cell border-b dark:border-gray-800"><!-- Actions --></th>
                    <th v-for="column in columns" :key="column.field" :class="column.class" class="hidden md:table-cell text-left dark:text-gray-300 border-b dark:border-gray-800 p-3">
                        {{ column.title }}
                    </th>
                </tr>
            </thead>
            <tbody class="flex flex-col space-y-6 md:table-row-group">
                <tr v-for="row in data.data" :key="row.id" class="flex flex-col space-y-1 md:table-row pb-3 border-b md:border-0 md:hover:bg-gray-50 dark:md:hover:bg-gray-800">
                    <td class="md:w-[1px] md:px-3 md:py-2 md:border-b dark:border-gray-800">
                        <slot name="actions" :data="row" />
                    </td>
                    <td v-for="column in columns" class="flex flex-col md:table-cell md:px-3 md:py-2 md:border-b dark:border-gray-800">
                        <span class="text-xs text-gray-400 uppercase font-bold md:hidden">{{ column.title }}</span>
                        <slot :name="`${camelCase(column.field)}Data`" :data="row">
                            <span class="dark:text-gray-300">{{ row[column.field] }}</span>
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex flex-col md:flex-row items-center md:justify-between space-y-3 md:space-y-0">
            <div v-if="data.total > data.per_page" class="w-full md:w-auto flex items-center justify-between md:space-x-3 md:order-last">
                <div>
                    <select v-model="filters.per_page" class="text-sm py-0.5 rounded outline-none border-gray-300 focus:border-primary-200 focus:ring-primary-200">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <Link v-if="data.prev_page_url" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="data.prev_page_url">
                        <FontAwesomeIcon :icon="faAngleLeft" />
                    </Link>

                    <span class="text-sm text-gray-400">Page {{ data.current_page }} of {{ Math.ceil(data.total / data.per_page) }}</span>

                    <Link v-if="data.next_page_url" class="text-sm dark:text-gray-300 hover:text-primary hover:bg-primary-100 px-3 py-1 rounded transition ease-in-out" :href="data.next_page_url">
                        <FontAwesomeIcon :icon="faAngleRight" />
                    </Link>
                </div>
            </div>

            <span v-if="data.total > 1" class="text-sm text-gray-400 md:order-first">Showing {{ data.from }} to {{ data.to }} of {{ data.total }} results</span>
        </div>
    </div>
</template>
