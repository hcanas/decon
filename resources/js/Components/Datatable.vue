<script setup>
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import TextInput from './Form/TextInput.vue';
import { camelCase, find, pickBy, debounce } from 'lodash';
import { 
    ChevronUpDownIcon,
    ChevronUpIcon,
    ChevronDownIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    totalRows: Number,
    filters: Object,
    columns: {
        type: Array,
        required: true,
    },
    links: {
        type: Array,
        required: true,
    },
    baseUrl: {
        type: String,
        required: true,
    },
});

const filters = ref({
    sortField: props.filters?.sortField ?? null,
    sortOrder: props.filters?.sortOrder ?? null,
    keyword: props.filters?.keyword ?? '',
});

const sort = column => {
    if (!column.sortable) return;

    if (filters.value.sortField !== column.field) {
        filters.value.sortField = column.field;
        filters.value.sortOrder = null;
    }

    if (filters.value.sortOrder === null || filters.value.sortOrder === 'desc') {
        filters.value.sortOrder = 'asc';
    } else {
        filters.value.sortOrder = 'desc';
    }
};

const resetSort = () => {
    filters.value.sortField = null;
    filters.value.sortOrder = null;
};

const colClass = column => {
    let baseClass = 'dark:text-gray-200 border px-3 py-3';

    if (column.sortable) baseClass = baseClass.concat(' cursor-pointer');

    return column.class
        ? baseClass.concat(' ', column.class)
        : baseClass;
};

watch(
    () => filters.value,
    debounce(() => {
        router.get(props.baseUrl, pickBy(filters.value), { preserveScroll: true });
    }, 300),
    { deep: true },
);
</script>

<template>
    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <form @submit.prevent="search">
                    <TextInput
                        type="search"
                        v-model="filters.keyword"
                        placeholder="Search keyword..."
                        :class="'py-1'"
                        autofocus
                    />
                </form>

                <div class="flex items-center space-x-3">
                    <p v-if="filters.sortField" class="flex items-center space-x-1 text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">
                        <span>{{ `sort-${filters.sortOrder}: ${find(columns, ['field', filters.sortField]).title}` }}</span>
                        <button @click="resetSort" title="Remove" class="hover:text-primary-light focus:text-primary-light active:text-primary-light outline-none"><XMarkIcon class="w-4 h-4" /></button>
                    </p>
                    <p v-if="totalRows" class="text-sm text-gray-500">
                        {{ `${totalRows} result${totalRows > 1 ? 's' : ''}` }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-6">
                <slot name="controls" />
            </div>
        </div>
        <table class="table-auto">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column.field" @click="sort(column)" :class="colClass(column)">
                        <span class="inline me-1">{{ column.title }}</span>
                        <ChevronUpDownIcon class="inline w-4 h-4" v-if="column.sortable && filters.sortField !== column.field" />
                        <ChevronUpIcon class="inline w-4 h-4" v-else-if="filters.sortField === column.field && filters.sortOrder === 'asc'" />
                        <ChevronDownIcon class="inline w-4 h-4" v-else-if="filters.sortField === column.field && filters.sortOrder === 'desc'" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in data" :key="row.id">
                    <td v-for="column in columns" class="dark:text-gray-200 border p-3">
                        <slot :name="`${camelCase(column.field)}Data`" :data="row">
                            {{ row[column.field] }}
                        </slot>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td v-if="links.length > 3" :colspan="columns.length">
                        <div class="flex justify-center mt-4">
                            <template v-for="(link, key) in links">
                                <div v-if="link.url === null" :key="key" class="mb-1 mr-1 px-4 py-3 text-gray-400 text-sm leading-4 border rounded" v-html="link.label" />
                                <Link v-else :key="`link-${key}`" class="mb-1 mr-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:text-white hover:bg-primary border focus:border-indigo-500 rounded transition ease-in-out" :class="{ 'bg-primary text-white': link.active }" :href="link.url" v-html="link.label" />
                            </template>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>