<script setup>
import {camelCase, reduce} from "lodash";
import {useFormatter} from "@/Composables/formatter.js";

defineProps({
    columns: {
        type: Array,
        required: true,
    },
    rows: {
        type: Array,
        required: true,
    },
});

const { formatCurrency, formatDate } = useFormatter();

const alignment = {
    left: 'text-left',
    center: 'md:text-center',
    right: 'md:text-right',
};

const propertyName = path => reduce(path.split('.'), (_, k) => k, '');
const nestedSearch = (obj, path) => reduce(path.split('.'), (o, k) => o[k] ?? '', obj);
const formatValue = (format, value) => {
    if (format === 'currency') {
        return formatCurrency(value);
    } else if (format === 'date') {
        return formatDate(value);
    }

    return value;
};
</script>

<template>
    <table class="border-collapsed">
        <thead>
            <tr class="hidden md:table-row">
                <th v-for="column in columns" :class="(alignment[column.align ?? 'center']).concat(' ', column.class)">
                    {{ column.title }}
                </th>
                <th class="hidden md:table-cell w-1"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="row.id" class="flex flex-col md:table-row rounded bg-neutral-50 md:bg-transparent dark:bg-neutral-800 m-3">
                <td class="md:hidden">
                    <slot name="actions" :rowData="row" />
                </td>
                <td v-for="column in columns" :class="alignment[column.align ?? 'center']" class="flex flex-col md:table-cell p-2">
                    <span class="text-xs uppercase font-medium italic md:hidden border-b border-neutral-400 w-fit">{{ column.title }}</span>
                    <slot :name="camelCase(column.field)" :rowData="row">
                        {{ formatValue(column.format, nestedSearch(row, column.field)) }}
                    </slot>
                </td>
                <td class="hidden md:table-cell">
                    <slot name="actions" :rowData="row" />
                </td>
            </tr>
        </tbody>
    </table>
</template>

<style scoped>
tr {
    @apply border-x md:border-x-0 border-y md:border-t-0 md:border-b dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-neutral-900
}

th,
td {
    @apply md:p-2
}
</style>
