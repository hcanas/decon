<script setup>
import {useFormatter} from "@/Composables/formatter.js";
import {computed} from "vue";
import {filter} from 'lodash';

const { formatCurrency, formatDateTime } = useFormatter();

const props = defineProps({
    data: Object,
});

const available = computed(() => filter(props.data?.quotation.items, x => x.status === 'available'));
const unavailable = computed(() => filter(props.data?.quotation.items, x => x.status === 'unavailable'));
</script>

<template>
    <div class="flex flex-col space-y-3">
        <div class="flex flex-col space-y-1 border-b dark:border-gray-500">
            <div class="flex flex-col divide-y">
                <div v-for="item in data.quotation.items" class="flex items-end py-2">
                    <p class="flex-grow flex flex-col">
                        <span :class="item.status">{{ item.product.name }}</span>
                        <span :class="item.status" class="md:w-96 truncate text-sm italic">{{ item.product.description }}</span>
                        <span class="text-xs" :class="item.status">{{ item.product.brand?.value ?? 'No Brand' }}</span>
                        <span :class="item.status" class="text-sm">{{ `${item.qty} ${item.measurement_unit} x ${formatCurrency(item.price)}` }}</span>
                    </p>
                    <p :class="item.status" class="flex-shrink-0 text-sm">{{ formatCurrency(item.qty * item.price) }}</p>
                </div>
                <p class="flex justify-between font-bold py-2">
                    <span>Amount</span>
                    <span>{{ formatCurrency(data.amount) }}</span>
                </p>
            </div>
        </div>
        <p class="text-sm text-right">Confirmed on {{ formatDateTime(data.quotation.updated_at) }}</p>
    </div>
</template>

<style scoped>
.unavailable {
    @apply line-through text-neutral-300 dark:text-neutral-600
}
</style>
