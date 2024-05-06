<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import ProductForm from "./Partials/Form.vue";
import {router, useForm} from "@inertiajs/vue3";
import { ref } from "vue";
import Head from "@/Components/Head.vue";
import DeleteProduct from "@/Pages/Products/Partials/DeleteProduct.vue";
import Pagination from "@/Components/Pagination.vue";
import CustomTable from "@/Components/Table/CustomTable.vue";
import TableTag from "@/Components/Table/TableTag.vue";
import TableMenu from "@/Components/Table/TableMenu.vue";
import TableMenuItem from "@/Components/Table/TableMenuItem.vue";
import AdminFilter from "@/Pages/Products/AdminFilter.vue";

defineProps({
    filters: Object,
    products: {
        type: Object,
        required: true,
    },
});

const statusColors = {
    available: 'text-green-600 dark:text-green-500',
    unavailable: 'text-red-600 dark:text-red-500',
    archived: 'text-cyan-600 dark:text-cyan-500',
};

const columns = [
    { field: 'image', title: 'Image', class: 'w-32' },
    { field: 'product', title: 'Product', align: 'left' },
    { field: 'brand', title: 'Brand' },
    { field: 'category', title: 'Category' },
    { field: 'price', title: 'Price', format: 'currency' },
    { field: 'measurement_unit', title: 'Unit' },
    { field: 'status', title: 'Status', class: 'w-1' },
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

const updateStatus = (id, status) => {
    useForm({ status }).put(route('admin.products.update', { product: id }))
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Products">
            <template #actions>
                <PrimaryButton @click="showModal('product-form')">New Product</PrimaryButton>
            </template>
        </Head>

        <div class="mt-6 md:mt-12 flex flex-col space-y-3">
            <AdminFilter />

            <CustomTable :rows="products.data" :columns="columns">
                <template #image="{ rowData }">
                    <img :src="`/storage/images/${rowData.image ?? 'placeholder.png'}`" class="w-24 md:mx-auto" />
                </template>

                <template #product="{ rowData }">
                    <div class="flex flex-col">
                        <p>{{ rowData.name }}</p>
                        <p class="text-sm">{{ rowData.description }}</p>
                    </div>
                </template>

                <template #brand="{ rowData }">
                    <p>{{ rowData.brand ?? 'No Brand' }}</p>
                </template>

                <template #category="{ rowData }">
                    <p class="flex md:flex-col items-center space-x-1 text-sm">
                        <span>{{ rowData.category }}</span>
                        <span v-if="rowData.sub_category" class="hidden md:block">&darr;</span>
                        <span v-if="rowData.sub_category" class="md:hidden">&rarr;</span>
                        <span v-if="rowData.sub_category">{{ rowData.sub_category }}</span>
                    </p>
                </template>

                <template #status="{ rowData }">
                    <TableTag class="md:mx-auto" :class="statusColors[rowData.status]">{{ rowData.status }}</TableTag>
                </template>

                <template #actions="{ rowData }">
                    <TableMenu>
                        <TableMenuItem @click="showModal('product-form', rowData)">
                            Edit
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.status !== 'available'" @click="updateStatus(rowData.id, 'available')">
                            Mark as available
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.status !== 'unavailable'" @click="updateStatus(rowData.id, 'unavailable')">
                            Mark as unavailable
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.status !== 'archived'" @click="updateStatus(rowData.id, 'archived')">
                            Archive
                        </TableMenuItem>
                        <TableMenuItem @click="showModal('delete-product', rowData)" class="text-red-600 dark:text-red-500">
                            Delete
                        </TableMenuItem>
                    </TableMenu>
                </template>
            </CustomTable>

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

        <Modal :show="modalOptions.show" @close="closeModal()">
            <template #title>
                <span v-if="modalOptions.component === 'product-form'">Product Information</span>
                <span v-else-if="modalOptions.component === 'delete-product'">Delete Product</span>
            </template>

            <ProductForm v-if="modalOptions.component === 'product-form'" :data="modalOptions.data" @close="closeModal" />
            <DeleteProduct v-else-if="modalOptions.component === 'delete-product'" :data="modalOptions.data" @close="closeModal" @success="updateProducts" />
        </Modal>
    </AuthenticatedLayout>
</template>
