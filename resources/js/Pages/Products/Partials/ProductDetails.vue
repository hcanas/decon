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
    <div class="flex flex-col md:flex-row md:items-stretch md:space-x-3 space-y-3 md:space-y-0">
        <div class="flex-shrink-0">
            <img :src="`/storage/images/${product.image ?? 'placeholder.png'}`" class="mx-auto w-48 rounded" />
        </div>
        <div class="flex-grow flex flex-col md:justify-between space-y-3 md:space-y-0">
            <div class="flex flex-col items-center space-y-1">
                <p class="text-xl font-medium text-center">{{ product.name }}</p>
                <p class="text-sm">Brand: {{ product.brand ?? 'No Brand' }}</p>
                <p class=" text-wrap">{{ product.description }}</p>
<!--                <p>{{ formatCurrency(product.price) }} / {{ product.measurement_unit }}</p>-->
            </div>
            <SecondaryButton v-if="cart.exists(product.id)" @click="cart.remove(product.id)">Remove From Cart</SecondaryButton>
            <PrimaryButton v-else @click="cart.add(product.id)">Add To Cart</PrimaryButton>
        </div>
    </div>
</template>
