<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import Head from "@/Components/Head.vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import ProductDetails from "@/Pages/Products/Partials/ProductDetails.vue";
import {useFormatter} from "@/Composables/formatter.js";
import {useCart} from "@/Composables/cart.js";
import Filter from "@/Pages/Products/Filter.vue";

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

const { formatCurrency } = useFormatter();
const cart = useCart();

const modalOptions = ref({
    show: false,
    data: null,
});

const showDetails = product => {
    modalOptions.value.show = true;
    modalOptions.value.data = product;
};

const closeModal = () => {
    modalOptions.value.show = false;
    modalOptions.value.data = null;
};
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head :title="'Products'" />

            <Filter />

            <div class="grid grid-cols-2 md:grid-cols-6 gap-6">
                <a href="#" @click.prevent="showDetails(product)" v-for="product in products.data" class="shadow-lg rounded dark:bg-gray-950 hover:bg-gray-50 dark:hover:bg-gray-900 transition ease-in-out">
                    <div class="w-full p-1 rounded-t">
                        <img :src="`/storage/images/${product.image ?? 'placeholder.png'}`" class="mx-auto rounded-t" />
                    </div>
                    <div class="flex flex-col items-center px-4 py-2">
                        <p class="w-full text-center dark:text-gray-300 truncate" :title="product.name">{{ product.name }}</p>
                        <p class="text-sm text-gray-500">{{ product.brand }}</p>
                        <p class="dark:text-gray-300">{{ formatCurrency(product.price) }}</p>
                    </div>
                </a>
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

    <Modal :show="modalOptions.show" @close="closeModal()">
        <template #title>
            Product Details
        </template>

        <Transition
            leave-active-class="opacity duration-200 ease-in-out"
            leave-to-class="opacity-0"
        >
            <ProductDetails v-if="modalOptions.data" :product="modalOptions.data" />
        </Transition>
    </Modal>
</template>
