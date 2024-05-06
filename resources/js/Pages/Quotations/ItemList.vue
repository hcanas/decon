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
            <p class="flex-grow flex flex-col">
                <span :class="item.status">{{ item.product.name }}</span>
                <span :class="item.status" class="md:w-96 truncate text-sm italic">{{ item.product.description }}</span>
                <span class="text-xs" :class="item.status">{{ item.product.brand?.value ?? 'No Brand' }}</span>
                <span :class="item.status" class="text-sm">{{ `${item.qty} ${item.measurement_unit} x ${formatCurrency(item.price)}` }}</span>
            </p>
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
    @apply line-through text-neutral-300 dark:text-neutral-600
}
</style>
