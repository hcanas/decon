<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import TextInput from './Form/TextInput.vue';
import { camelCase, pickBy, debounce } from 'lodash';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";

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
</template>
