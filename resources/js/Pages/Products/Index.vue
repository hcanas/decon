<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Head from "@/Components/Head.vue";
import Filter from "@/Components/Filter/Filter.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const toCurrency = number => new Intl.NumberFormat('en-us', { style: 'currency', currency: 'PHP'}).format(number);
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head :title="'Products'" />

            <Filter />

            <div class="flex-grow flex flex-col space-y-6">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                    <a href="#" v-for="product in products.data" class="shadow-lg rounded dark:bg-gray-950 hover:bg-gray-50 dark:hover:bg-gray-900 transition ease-in-out">
                        <div class="w-full p-1 rounded-t">
                            <img class="w-24 h-24 mx-auto rounded-t" />
                        </div>
                        <div class="flex flex-col items-center px-4 py-2">
                            <p class="w-full text-center dark:text-gray-300 truncate">{{ product.name }}</p>
                            <p class="text-sm text-gray-500">{{ product.brand }}</p>
                            <p class="dark:text-gray-300">{{ toCurrency(product.price) }}</p>
                            <p v-if="product.stock > 0" class="text-xs text-green-600">In Stock</p>
                            <p v-else class="text-xs text-red-600">Out Of Stock</p>
                        </div>
                    </a>
                </div>
            </div>

            <Pagination
                :nextPageUrl="products.next_page_url"
                :prevPageUrl="products.prev_page_url"
                :totalRecords="products.total"
                :to="products.to"
                :from="products.from"
                :perPage="products.per_page"
                :currentPage="products.current_page"
            />
        </section>
    </MainLayout>
</template>
