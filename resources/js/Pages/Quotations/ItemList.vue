<script setup>
import {useFormatter} from "@/Composables/formatter.js";

defineProps({
    data: Object,
});

const { formatCurrency } = useFormatter();
</script>

<template>
    <div class="flex flex-col divide-y">
        <div v-for="item in data.items" class="flex items-end py-2">
            <div class="flex-grow flex flex-col">
                <p :class="item.status">{{ item.product.name }} ({{ item.product.brand?.value }})</p>
                <p :class="item.status" class="w-96 truncate text-sm text-gray-600 italic">{{ item.product.description }}</p>
                <p :class="item.status" class="text-sm">{{ `${item.qty} ${item.measurement_unit} x ${formatCurrency(item.price)}` }}</p>
            </div>
            <p :class="item.status" class="flex-shrink-0 text-sm">{{ formatCurrency(item.qty * item.price) }}</p>
        </div>
        <p class="flex justify-between font-bold">
            <span>Total</span>
            <span>{{ formatCurrency(data.total) }}</span>
        </p>
    </div>
</template>

<style scoped>
.unavailable {
    @apply text-gray-400 line-through
}
</style>
