<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/solid/index.js";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import ProductForm from "./Partials/Form.vue";
import Datatable from "@/Components/Datatable.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref } from "vue";
import DeleteProduct from "@/Pages/Products/Partials/DeleteProduct.vue";

defineProps({
    filters: Object,
    products: {
        type: Object,
        required: true,
    },
});

const columns = [
    { field: 'name', title: 'Name', sortable: true },
    { field: 'brand', title: 'Brand', sortable: true, class: 'w-48' },
    { field: 'product_category', title: 'Category', sortable: true, class: 'w-72' },
    { field: 'price', title: 'Price', sortable: true, class: 'w-28' },
    { field: 'stock', title: 'Stock', sortable: true, class: 'w-28' },
];

const modalOptions = ref({
    show: false,
    data: null,
    component: '',
});

const showModal = (component, data) => {
    modalOptions.value.show = true;
    modalOptions.value.data = data;
    modalOptions.value.component = component;
};

const closeModal = () => {
    modalOptions.value.show = false;

    const handler = setTimeout(() => {
        modalOptions.value.component = '';
        modalOptions.value.data = null;
        clearTimeout(handler);
    }, 200);
};

const updateProducts = () => {
    closeModal();
    router.reload();
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Products</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Datatable
                    :data="products.data"
                    :totalRows="products.total"
                    :filters="filters"
                    :columns="columns"
                    :links="products.links"
                    :baseUrl="'/products'"
                >
                    <template #controls>
                        <PrimaryButton @click="showModal('product-form')">New Product</PrimaryButton>
                    </template>

                    <template #nameData="{ data }">
                        <div class="flex items-center justify-between">
                            <p class="flex flex-col">
                                <span>{{ data.name }}</span>
                                <span class="text-sm text-gray-500">{{ data.description }}</span>
                            </p>
                            <div class="flex items-center space-x-2">
                                <button @click="showModal('product-form', data)" title="Edit Product" class="p-1 text-gray-600 hover:text-sky-600 hover:bg-gray-200 rounded shadow transition ease-in-out">
                                    <PencilSquareIcon class="w-4 h-4" />
                                </button>
                                <button @click="showModal('delete-dialog', data)" title="Block" class="p-1 text-gray-600 hover:text-white hover:bg-red-500 rounded shadow transition ease-in-out">
                                    <TrashIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </template>

                    <template #priceData="{ data }">
                        <span class="block text-right">{{ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'PHP' }).format(data.price) }}</span>
                    </template>

                    <template #stockData="{ data }">
                        <p class="flex flex-col items-end">
                            <span>{{ new Intl.NumberFormat('en-US').format(data.stock) }}</span>
                            <span class="text-sm text-gray-500">{{ data.measurement_unit }}</span>
                        </p>
                    </template>
                </Datatable>
            </div>
        </div>

        <Modal :show="modalOptions.show">
            <template #title>
                <span v-if="modalOptions.component === 'product-form'">Product Information</span>
                <span v-else-if="modalOptions.component === 'delete-dialog'">Delete Product</span>
            </template>

            <ProductForm v-if="modalOptions.component === 'product-form'" :data="modalOptions.data" @close="closeModal" />
            <DeleteProduct v-else-if="modalOptions.component === 'delete-dialog'" :data="modalOptions.data" @close="closeModal" @success="updateProducts" />
        </Modal>
    </AuthenticatedLayout>
</template>
