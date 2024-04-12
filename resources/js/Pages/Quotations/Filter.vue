<script setup>
import CustomFilter from "@/Components/Filter/CustomFilter.vue";
import DropdownFilter from "@/Components/Filter/DropdownFilter.vue";

const options = [
    { value: '', label: 'All Quotations' },
    { value: 'pending', label: 'Pending Quotations' },
    { value: 'sent', label: 'Sent Quotations' },
    { value: 'confirmed', label: 'Confirmed Quotations' },
    { value: 'cancelled', label: 'Cancelled Quotations' },
];
</script>

<template>
    <CustomFilter>
        <DropdownFilter :options="options" field="only" />
    </CustomFilter>
</template>
