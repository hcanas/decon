<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import {onMounted, ref} from "vue";
import {useFormatter} from "@/Composables/formatter.js";
import SecondaryButton from "@/Components/Button/SecondaryButton.vue";

const { formatNumber } = useFormatter();

const bestSellers = ref([]);

function redirectToProducts() {
    router.get(route('products.index'));
}

onMounted(() => {
    axios.get(route('best_sellers'))
        .then(response => bestSellers.value = response.data);
});
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="Home" />

            <div class="flex flex-col md:flex-row items-center md:justify-center md:space-x-12">
                <div>
                    <img src="/images/hero2.png" class="h-80 md:h-[650px]" />
                </div>
                <div class="flex flex-col items-center md:items-start">
                    <div class="flex flex-col items-center md:items-start">
                        <h1 class="uppercase text-4xl md:text-6xl font-bold leading-3">Making</h1>
                        <h1 class="uppercase text-5xl md:text-7xl font-bold leading-tight">Health Care</h1>
                        <h1 class="uppercase text-3xl md:text-5xl font-medium leading-3">Better Together!</h1>
                    </div>

                    <p class="text-lg font-medium mt-12">Great opportunities don't wait!</p>
                    <PrimaryButton @click="redirectToProducts" class="w-fit mt-3">Request Quotation Now</PrimaryButton>
                </div>
            </div>

            <div class="flex flex-col items-center space-y-3 mt-12 md:mt-0">
                <h3 class="text-2xl font-bold">Best Sellers</h3>
                <div class="grid grid-cols-2 md:grid-cols-8 gap-6 justify-center">
                    <div href="#" v-for="product in bestSellers">
                        <div class="w-full p-1 rounded-t">
                            <img :src="`/storage/images/${product.image ?? 'placeholder.png'}`" class="mx-auto rounded" />
                        </div>
                        <div class="flex flex-col items-center space-y-1 px-4 py-2">
                            <p class="w-full text-center text-xs truncate" :title="product.name">{{ product.name }}</p>
                            <p class="text-xs">{{ product.brand ?? 'No Brand' }}</p>
                            <p>{{ formatNumber(product.qty) }} sold</p>
                        </div>
                    </div>
                </div>
                <SecondaryButton @click="redirectToProducts" class="w-fit">View More Products</SecondaryButton>
            </div>
        </section>
    </MainLayout>
</template>
