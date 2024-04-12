<script setup>
import CustomFilter from "@/Components/Filter/CustomFilter.vue";
import DropdownFilter from "@/Components/Filter/DropdownFilter.vue";

const options = [
    { value: '', label: 'All Orders' },
    { value: 'unpaid', label: 'Unpaid Orders' },
    { value: 'paid', label: 'Paid Orders' },
    { value: 'upcoming_deliveries', label: 'Upcoming Deliveries' },
    { value: 'delivered', label: 'Delivered Orders' },
    { value: 'cancelled', label: 'Cancelled Orders' },
];
</script>

<template>
    <CustomFilter>
        <DropdownFilter :options="options" field="only" />
    </CustomFilter>
</template>
