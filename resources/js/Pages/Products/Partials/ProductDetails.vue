<script setup>
import {onMounted} from "vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";
import {useFormatter} from "@/Composables/formatter.js";
import {useCart} from "@/Composables/cart.js";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const { formatCurrency } = useFormatter();
const cart = useCart();

onMounted(() => {
    if (localStorage.getItem('cart')) {
        cart.value = Array.isArray(JSON.parse(localStorage.getItem('cart')))
            ? JSON.parse(localStorage.getItem('cart'))
            : [];
    }
});
</script>

<template>
    <div class="flex items-stretch">
        <div class="flex-shrink-0">
            <img src="/storage/images/placeholder.png" class="w-48" />
        </div>
        <div class="flex-grow flex flex-col justify-between">
            <div class="flex flex-col space-y-1">
                <p class="text-xl font-medium">{{ product.name }}</p>
                <p class="text-sm text-gray-600">Brand: {{ product.brand }}</p>
                <p class="text-gray-600 text-wrap">{{ product.description }}</p>
                <p>{{ formatCurrency(product.price) }} / {{ product.measurement_unit }}</p>
            </div>
            <SecondaryButton v-if="cart.exists(product.id)" @click="cart.remove(product.id)">Remove From Cart</SecondaryButton>
            <PrimaryButton v-else @click="cart.add(product.id)">Add To Cart</PrimaryButton>
        </div>
    </div>
</template>
