<script setup>
import CustomFilter from "@/Components/Filter/CustomFilter.vue";
import DropdownFilter from "@/Components/Filter/DropdownFilter.vue";

const options = [
    { value: '', label: 'All Users' },
    { value: 'admin', label: 'Admin Users' },
    { value: 'regular', label: 'Regular Users' },
];
</script>

<template>
    <CustomFilter>
        <DropdownFilter :options="options" field="only" />
    </CustomFilter>
</template>
