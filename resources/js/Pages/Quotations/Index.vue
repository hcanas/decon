<script setup>
import {router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Head from "@/Components/Head.vue";
import CustomTable from "@/Components/Table/CustomTable.vue";
import TableButton from "@/Components/Table/TableButton.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faEye,
    faPencil,
    faTag,
    faTrashCan,
    faPhoneFlip, faPhoneVolume, faPaperPlane, faCheck, faTruckFast, faEllipsis, faChevronDown,
} from "@fortawesome/free-solid-svg-icons";
import TableTag from "@/Components/Table/TableTag.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import {ref} from "vue";
import ConfirmCancellation from "@/Pages/Quotations/Confirmations/ConfirmCancellation.vue";
import {includes, filter} from "lodash";
import ItemList from "@/Pages/Quotations/ItemList.vue";
import Filter from "@/Pages/Quotations/Filter.vue";
import ItemForm from "@/Pages/Quotations/Forms/ItemForm.vue";
import {useFormatter} from "@/Composables/formatter.js";
import ConfirmSend from "@/Pages/Quotations/Confirmations/ConfirmSend.vue";
import ConfirmQuotation from "@/Pages/Quotations/Confirmations/ConfirmQuotation.vue";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";

defineProps({
    filters: Object,
    data: Object,
});

const { formatDate } = useFormatter();

const columns = [
    { field: 'created_at', title: 'Date', format: 'date', align: 'left' },
    { field: 'customer', title: 'Customer', align: 'left' },
    { field: 'items', title: 'Items' },
    { field: 'total', title: 'Total', format: 'currency', align: 'right' },
    { field: 'status', title: 'Status' },
];

const modalOptions = ref({
    show: false,
    component: '',
    data: null,
});

const closeModal = () => {
    modalOptions.value = {
        show: false,
        component: '',
        data: null,
    };
};

const editItems = param => {
    modalOptions.value = {
        show: true,
        component: 'item-form',
        data: param,
    };
};

const viewItems = param => {
    modalOptions.value = {
        show: true,
        component: 'item-list',
        data: param,
    };
};

const confirmSend = param => {
    modalOptions.value = {
        show: true,
        component: 'confirm-send',
        data: param,
    };
};

const confirmQuotation = param => {
    modalOptions.value = {
        show: true,
        component: 'confirm-quotation',
        data: param,
    };
};

const confirmCancellation = param => {
    modalOptions.value = {
        show: true,
        component: 'confirm-cancellation',
        data: param,
    };
};

const goToPurchaseOrder = param => {
    router.get(route('admin.purchase_orders.index', { keyword: param }))
};

const isLocked = status => {
    return includes(['confirmed', 'cancelled'], status);
};

const statusColors = {
    pending: 'text-yellow-500',
    sent: 'text-cyan-500',
    confirmed: 'text-green-500',
    rejected: 'text-pink-500',
    cancelled: 'text-red-500',
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Quotations" />

        <div class="mt-12 flex flex-col space-y-3">
            <Filter />

            <CustomTable :rows="data.data" :columns="columns">
                <template #customer="{ rowData }">
                    <div class="flex flex-col space-y-1">
                        <p class="font-medium">{{ rowData.customer.email }}</p>
                        <div class="flex items-center space-x-3">
                            <p class="text-sm text-gray-500 flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faTag" class="text-center text-gray-500" />
                                <span>{{ rowData.customer.code }}</span>
                            </p>
                            <p class="text-sm text-gray-500 flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneFlip" class="text-center text-gray-500" />
                                <span class="italic">{{ rowData.customer.contact_number }}</span>
                            </p>
                            <p class="text-sm text-gray-500 flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneVolume" class="text-center text-gray-500" />
                                <span class="italic">{{ rowData.customer.viber_id ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </template>

                <template #items="{ rowData }">
                    <div class="flex justify-center items-center space-x-3">
                        <p class="text-sm flex divide-x">
                            <span class="text-cyan-500 px-2">{{ `${filter(rowData.items, x => x.status === 'available').length} available` }}</span>
                            <span class="text-gray-400 px-2">{{ `${filter(rowData.items, x => x.status === 'unavailable').length} unavailable` }}</span>
                        </p>
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
                                <MenuItems as="div" class="absolute top-6 -left-12 p-4 bg-gray-50 shadow rounded z-10 w-max flex flex-col space-y-3">
                                    <MenuItem v-if="!isLocked(rowData.status)" as="div">
                                        <TableButton @click="editItems(rowData)" class="block text-blue-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faPencil" />
                                                <span>Edit</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="isLocked(rowData.status)" as="div">
                                        <TableButton @click="viewItems(rowData)" class="block text-blue-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faEye" />
                                                <span>View</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="!isLocked(rowData.status)" as="div">
                                        <TableButton @click="confirmSend(rowData)" class="block text-blue-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faPaperPlane" />
                                                <span>Send Quotation</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="!isLocked(rowData.status) && rowData.status === 'sent'" as="div">
                                        <TableButton @click="confirmQuotation(rowData)" class="block text-green-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faCheck" />
                                                <span>Confirm Quotation</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="!isLocked(rowData.status)" as="div">
                                        <TableButton @click="confirmCancellation(rowData)" class="text-red-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faTrashCan" />
                                                <span>Cancel Quotation</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                    <MenuItem v-if="rowData.purchase_order" as="div">
                                        <TableButton @click="goToPurchaseOrder(rowData.purchase_order.reference_number)" class="block text-primary-500">
                                            <div class="flex items-center space-x-1">
                                                <FontAwesomeIcon :icon="faTruckFast" />
                                                <span>{{ rowData.purchase_order.reference_number }}</span>
                                            </div>
                                        </TableButton>
                                    </MenuItem>
                                </MenuItems>
                            </Transition>
                        </Menu>
                    </div>
                </template>

                <template #status="{ rowData }">
                    <div class="flex flex-col items-center">
                        <TableTag class="mx-auto" :class="statusColors[rowData.status]">{{ rowData.status }}</TableTag>
                        <span class="text-sm text-gray-400">
                            {{ formatDate(rowData.updated_at) }}
                        </span>
                    </div>
                </template>
            </CustomTable>

            <Pagination
                :next-page-url="data.next_page_url"
                :prev-page-url="data.prev_page_url"
                :first-page-url="data.first_page_url"
                :last-page-url="data.last_page_url"
                :total-records="data.total"
                :to="data.to"
                :from="data.from"
                :per-page="data.per_page"
                :current-page="data.current_page"
            />
        </div>
    </AuthenticatedLayout>

    <Modal :show="modalOptions.show" @close="closeModal()" :width="modalOptions.component === 'item-form' ? 'full' : ''">
        <template #title>
            <Transition
                leave-active-class="opacity duration-200 ease-in-out"
                leave-to-class="opacity-0"
            >
                <div v-if="modalOptions.data" class="flex flex-col">
                    <p class="font-medium">{{ modalOptions.data.customer.email }}</p>
                    <p class="text-gray-400">{{ formatDate(modalOptions.data.created_at) }}</p>
                </div>
            </Transition>
        </template>

        <Transition
            leave-active-class="opacity duration-200 ease-in-out"
            leave-to-class="opacity-0"
        >
            <ItemList v-if="modalOptions.component === 'item-list'" :data="modalOptions.data" />
            <ItemForm v-else-if="modalOptions.component === 'item-form'" :data="modalOptions.data" @saved="closeModal()" />
            <ConfirmSend v-else-if="modalOptions.component === 'confirm-send'" :data="modalOptions.data" @confirmed="closeModal()" />
            <ConfirmQuotation v-else-if="modalOptions.component === 'confirm-quotation'" :data="modalOptions.data" @confirmed="closeModal()" />
            <ConfirmCancellation v-else-if="modalOptions.component === 'confirm-cancellation'" :data="modalOptions.data" @confirmed="closeModal()" />
        </Transition>
    </Modal>
</template>
