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
import Filter from "@/Pages/Products/Filter.vue";
import TableTag from "@/Components/Table/TableTag.vue";
import {
    faBoxArchive,
    faCheck,
    faChevronDown,
    faPencil, faXmark,
} from "@fortawesome/free-solid-svg-icons";
import TableButton from "@/Components/Table/TableButton.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

defineProps({
    filters: Object,
    products: {
        type: Object,
        required: true,
    },
});

const statusColors = {
    available: 'text-green-500',
    unavailable: 'text-red-500',
    archived: 'text-cyan-500',
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

        <div class="mt-12 flex flex-col space-y-3">
            <Filter />

            <CustomTable :rows="products.data" :columns="columns">
                <template #image="{ rowData }">
                    <img :src="`/storage/images/${rowData.image ?? 'placeholder.png'}`" class="w-24 mx-auto" />
                </template>

                <template #product="{ rowData }">
                    <div class="flex flex-col">
                        <p>{{ rowData.name }}</p>
                        <p class="text-sm text-gray-600">{{ rowData.description }}</p>
                    </div>
                </template>

                <template #category="{ rowData }">
                    <p class="flex flex-col items-center space-x-1 text-sm">
                        <span>{{ rowData.category }}</span>
                        <span v-if="rowData.sub_category">&darr;</span>
                        <span v-if="rowData.sub_category">{{ rowData.sub_category }}</span>
                    </p>
                </template>

                <template #status="{ rowData }">
                    <div class="flex justify-center items-center space-x-1">
                        <TableTag class="mx-auto" :class="statusColors[rowData.status]">{{ rowData.status }}</TableTag>
                        <Menu as="div" class="relative">
                            <MenuButton class="text-sm z-0">
                                <FontAwesomeIcon :icon="faChevronDown" class="w-3 h-3" />
                            </MenuButton>

                            <Transition
                                enter-active-class="transition duration-100 ease-out"
                                enter-from-class="transform scale-95 opacity-0"
                                enter-to-class="transform scale-100 opacity-100"
                                leave-active-class="transition duration-75 ease-in"
                                leave-from-class="transform scale-100 opacity-100"
                                leave-to-class="transform scale-95 opacity-0"
                            >
                                <MenuItems as="div" class="absolute top-6 -left-24 p-4 bg-gray-50 shadow rounded z-10 w-max flex flex-col space-y-3">
                                    <MenuItem as="div">
                                        <TableButton @click="showModal('product-form', rowData)" class="block text-blue-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faPencil" />
                                                <span>Edit</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="rowData.status !== 'available'"  s="div">
                                        <TableButton @click="updateStatus(rowData.id, 'available')" class="block text-green-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faCheck" />
                                                <span>Mark as available</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem  v-if="rowData.status !== 'unavailable'" as="div">
                                        <TableButton @click="updateStatus(rowData.id, 'unavailable')" class="block text-red-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faXmark" />
                                                <span>Mark as unavailable</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem  v-if="rowData.status !== 'archived'" as="div">
                                        <TableButton @click="updateStatus(rowData.id, 'archived')" class="block text-cyan-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faBoxArchive" />
                                                <span>Archive</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                </MenuItems>
                            </Transition>
                        </Menu>
                    </div>
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
                <span v-else-if="modalOptions.component === 'delete-dialog'">Delete Product</span>
            </template>

            <ProductForm v-if="modalOptions.component === 'product-form'" :data="modalOptions.data" @close="closeModal" />
            <DeleteProduct v-else-if="modalOptions.component === 'delete-dialog'" :data="modalOptions.data" @close="closeModal" @success="updateProducts" />
        </Modal>
    </AuthenticatedLayout>
</template>
