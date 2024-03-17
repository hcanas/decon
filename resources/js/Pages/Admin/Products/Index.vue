<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import ProductForm from "./Partials/Form.vue";
import Datatable from "@/Components/Datatable.vue";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Head from "@/Components/Head.vue";
import DeleteProduct from "@/Pages/Admin/Products/Partials/DeleteProduct.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faPencil, faTrashCan } from "@fortawesome/free-solid-svg-icons";
import IconButton from "@/Components/Button/IconButton.vue";
import Pagination from "@/Components/Pagination.vue";
import Filter from "@/Components/Filter/Filter.vue";

defineProps({
    filters: Object,
    products: {
        type: Object,
        required: true,
    },
});

const columns = [
    { field: 'name', title: 'Name', sortable: true, class: 'w-96' },
    { field: 'description', title: 'Description' },
    { field: 'brand', title: 'Brand', sortable: true, class: 'w-48' },
    { field: 'product_category', title: 'Category', sortable: true, class: 'w-64' },
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
    <AuthenticatedLayout>
        <div class="px-3 md:px-12 py-6 md:py-12">
            <Head title="Products">
                <template #actions>
                    <PrimaryButton @click="showModal('product-form')">New Product</PrimaryButton>
                </template>
            </Head>

            <Filter />

            <Datatable
                    :data="products"
                    :filters="filters"
                    :columns="columns"
                >
                    <template #priceData="{ data }">
                        <span class="block md:text-right dark:text-gray-300">{{ new Intl.NumberFormat('en-US', { style: 'currency', currency: 'PHP' }).format(data.price) }}</span>
                    </template>

                    <template #stockData="{ data }">
                        <p class="flex items-end space-x-1 md:flex-col md:items-end">
                            <span class="dark:text-gray-300">{{ new Intl.NumberFormat('en-US').format(data.stock) }}</span>
                            <span class="text-sm text-gray-400">{{ data.measurement_unit }}</span>
                        </p>
                    </template>
                    <template #actions="{ data }">
                        <div class="flex items-center space-x-2">
                            <IconButton @click="showModal('product-form', data)" class="hover:text-sky-500 focus:text-sky-500 active:text-sky-500 dark:hover:text-sky-500 dark:focus:text-sky-500 dark:active:text-sky-500">
                                <FontAwesomeIcon :icon="faPencil" />
                            </IconButton>
                            <IconButton @click="showModal('delete-dialog', data)" class="hover:text-red-500 focus:text-red-500 active:text-red-500 dark:hover:text-red-500 dark:focus:text-red-500 dark:active:text-red-500">
                                <FontAwesomeIcon :icon="faTrashCan" />
                            </IconButton>
                        </div>
                    </template>
                </Datatable>

            <Pagination
                :nextPageUrl="products.next_page_url"
                :prevPageUrl="products.prev_page_url"
                :totalRecords="products.total"
                :to="products.to"
                :from="products.from"
                :perPage="products.per_page"
                :currentPage="products.current_page"
            />
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
