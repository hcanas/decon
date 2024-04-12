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
    center: 'text-center',
    right: 'text-right',
};

const propertyName = path => reduce(path.split('.'), (_, k) => k, '');
const nestedSearch = (obj, path) => reduce(path.split('.'), (o, k) => o[k], obj);
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
            <tr>
                <th v-for="column in columns" :class="(alignment[column.align ?? 'center']).concat(' ', column.class)">
                    {{ column.title }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="row.id">
                <td v-for="column in columns" :class="alignment[column.align ?? 'center']">
                    <slot :name="camelCase(column.field)" :rowData="row">
                        {{ formatValue(column.format, nestedSearch(row, column.field)) }}
                    </slot>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<style scoped>
tr {
    @apply border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-900
}

th,
td {
    @apply md:p-2 dark:text-gray-200
}
</style>
