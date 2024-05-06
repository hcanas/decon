<script setup>
import {router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Head from "@/Components/Head.vue";
import CustomTable from "@/Components/Table/CustomTable.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faTag,
    faPhoneFlip,
    faPhoneVolume
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
import RequestApproval from "@/Pages/Quotations/Confirmations/RequestApproval.vue";
import ConfirmSend from "@/Pages/Quotations/Confirmations/ConfirmSend.vue";
import ConfirmQuotation from "@/Pages/Quotations/Confirmations/ConfirmQuotation.vue";
import TableMenuItem from "@/Components/Table/TableMenuItem.vue";
import TableMenu from "@/Components/Table/TableMenu.vue";

defineProps({
    filters: Object,
    data: Object,
});

const { formatDate } = useFormatter();

const columns = [
    { field: 'created_at', title: 'Date', format: 'date', align: 'left' },
    { field: 'customer', title: 'Customer', align: 'left' },
    { field: 'items', title: 'Items' },
    { field: 'total', title: 'Amount', format: 'currency', align: 'right' },
    { field: 'purchase_order.reference_number', title: 'PO #' },
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

const requestApproval = param => {
    modalOptions.value = {
        show: true,
        component: 'request-approval',
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

const canEdit = param => {
    return !isLocked(param.status) && (
        (param.status === 'pending' && usePage().props.auth.user.access_level !== 'admin')
        || usePage().props.auth.user.access_level === 'admin'
    );
};

const statusColors = {
    pending: 'text-yellow-600 dark:text-yellow-500',
    'for approval': 'text-sky-600 dark:text-sky-500',
    sent: 'text-cyan-600 dark:text-cyan-500',
    confirmed: 'text-green-600 dark:text-green-500',
    rejected: 'text-pink-600 dark:text-pink-500',
    cancelled: 'text-red-600 dark:text-red-500',
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
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-3 space-y-1 md:space-y-0">
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faTag" class="text-center" />
                                <span>{{ rowData.customer.code }}</span>
                            </p>
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneFlip" class="text-center" />
                                <span class="italic">{{ rowData.customer.contact_number }}</span>
                            </p>
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneVolume" class="text-center" />
                                <span class="italic">{{ rowData.customer.viber_id ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </template>

                <template #items="{ rowData }">
                    <p class="text-sm flex w-fit md:mx-auto divide-x">
                        <span class="text-cyan-500 pr-2 md:pl-2">{{ `${filter(rowData.items, x => x.status === 'available').length} available` }}</span>
                        <span class="px-2">{{ `${filter(rowData.items, x => x.status === 'unavailable').length} unavailable` }}</span>
                    </p>
                </template>

                <template #status="{ rowData }">
                    <div class="flex flex-col md:items-center">
                        <TableTag :class="statusColors[rowData.status]">{{ rowData.status }}</TableTag>
                        <span class="text-sm">
                            {{ formatDate(rowData.updated_at) }}
                        </span>
                    </div>
                </template>

                <template #actions="{ rowData }">
                    <TableMenu>
                        <TableMenuItem v-if="canEdit(rowData)" @click="editItems(rowData)">
                            Edit Items
                        </TableMenuItem>
                        <TableMenuItem v-else @click="viewItems(rowData)">
                            View Items
                        </TableMenuItem>

                        <TableMenuItem v-if="rowData.status === 'pending' && $page.props.auth.user.access_level !== 'admin'" @click="requestApproval(rowData)">
                            Request Approval
                        </TableMenuItem>
                        <TableMenuItem v-else-if="includes(['pending', 'for approval'], rowData.status) && $page.props.auth.user.access_level === 'admin'" @click="confirmSend(rowData)">
                            Approve and Send Quotation
                        </TableMenuItem>

                        <TableMenuItem v-if="!isLocked(rowData.status) && rowData.status === 'sent'" @click="confirmSend(rowData)">
                            Resend Quotation
                        </TableMenuItem>

                        <TableMenuItem v-if="!isLocked(rowData.status) && rowData.status === 'sent'" @click="confirmQuotation(rowData)">
                            Confirm Quotation
                        </TableMenuItem>

                        <TableMenuItem v-if="!isLocked(rowData.status)" @click="confirmCancellation(rowData)" class="text-red-600 dark:text-red-500">
                            Cancel Quotation
                        </TableMenuItem>
                    </TableMenu>
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
                    <p class="">{{ formatDate(modalOptions.data.created_at) }}</p>
                </div>
            </Transition>
        </template>

        <Transition
            leave-active-class="opacity duration-200 ease-in-out"
            leave-to-class="opacity-0"
        >
            <ItemList v-if="modalOptions.component === 'item-list'" :data="modalOptions.data" />
            <ItemForm v-else-if="modalOptions.component === 'item-form'" :data="modalOptions.data" @saved="closeModal()" />
            <RequestApproval v-else-if="modalOptions.component === 'request-approval'" :data="modalOptions.data" @success="closeModal()" />
            <ConfirmSend v-else-if="modalOptions.component === 'confirm-send'" :data="modalOptions.data" @confirmed="closeModal()" />
            <ConfirmQuotation v-else-if="modalOptions.component === 'confirm-quotation'" :data="modalOptions.data" @confirmed="closeModal()" />
            <ConfirmCancellation v-else-if="modalOptions.component === 'confirm-cancellation'" :data="modalOptions.data" @confirmed="closeModal()" />
        </Transition>
    </Modal>
</template>
