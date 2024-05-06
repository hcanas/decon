<script setup>
import CustomFilter from "@/Components/Filter/CustomFilter.vue";
import DropdownFilter from "@/Components/Filter/DropdownFilter.vue";
import CheckboxFilter from "@/Components/Filter/CheckboxFilter.vue";
import {onMounted, ref} from "vue";
import {forEach, map} from "lodash";
import TreeFilter from "@/Components/Filter/TreeFilter.vue";

const dropdownOptions = [
    { value: '', label: 'All Products' },
    { value: 'available', label: 'Available Products' },
    { value: 'unavailable', label: 'Unavailable Products' },
    { value: 'archived', label: 'Archived Products' },
];

const checkboxOptions = ref([]);

const treeOptions = ref([]);

onMounted(() => {
    window.axios.get(route('brands'))
        .then(response => checkboxOptions.value = map(response.data, 'value'));

    window.axios.get(route('categories'))
        .then(response => {
            forEach(response.data, category => {
                treeOptions.value.push({
                    value: category.value,
                    subValues: map(category.sub_categories, 'value'),
                });
            });
        });
});
</script>

<template>
    <CustomFilter>
        <TreeFilter :options="treeOptions" defaultValue="All Categories" field="category" subField="sub_category" />
        <CheckboxFilter :options="checkboxOptions" defaultValue="All Brands" field="brands" />
        <DropdownFilter v-if="$page.props.auth.user" :options="dropdownOptions" field="status" />
    </CustomFilter>
</template>
