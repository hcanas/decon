<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Head from "@/Components/Head.vue";
import CustomTable from "@/Components/Table/CustomTable.vue";
import TableButton from "@/Components/Table/TableButton.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faCalendarDays,
    faCheck,
    faCreditCard,
    faEye,
    faPencil,
    faTag,
    faTrashCan,
    faPhoneFlip, faPhoneVolume,
} from "@fortawesome/free-solid-svg-icons";
import TableTag from "@/Components/Table/TableTag.vue";
import Modal from "@/Components/Modal.vue";
import Pagination from "@/Components/Pagination.vue";
import {ref} from "vue";
import PaymentForm from "@/Pages/PurchaseOrders/Forms/PaymentForm.vue";
import DeliveryForm from "@/Pages/PurchaseOrders/Forms/DeliveryForm.vue";
import ConfirmDelivery from "@/Pages/PurchaseOrders/Confirmations/ConfirmDelivery.vue";
import ConfirmCancellation from "@/Pages/PurchaseOrders/Confirmations/ConfirmCancellation.vue";
import {includes, filter} from "lodash";
import QuotationDetails from "@/Pages/PurchaseOrders/QuotationDetails.vue";
import Filter from "@/Pages/PurchaseOrders/Filter.vue";
import {useFormatter} from "@/Composables/formatter.js";
import ItemForm from "@/Pages/Quotations/Forms/ItemForm.vue";
import TableMenu from "@/Components/Table/TableMenu.vue";
import TableMenuItem from "@/Components/Table/TableMenuItem.vue";

defineProps({
    filters: Object,
    data: Object,
});

const { formatDate } = useFormatter();

const columns = [
    { field: 'reference_number', title: 'Ref #', align: 'left' },
    { field: 'quotation.customer', title: 'Customer', align: 'left' },
    { field: 'quotation', title: 'Quotation' },
    { field: 'amount', title: 'Amount', format: 'currency', align: 'right' },
    { field: 'payment_details', title: 'Payment Details', class: 'w-48'},
    { field: 'delivery_date', title: 'Delivery Date' },
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

const viewQuotationDetails = param => {
    modalOptions.value = {
        show: true,
        component: 'quotation-details',
        data: param,
    };
};

const setPaymentDetails = param => {
    modalOptions.value = {
        show: true,
        component: 'payment-form',
        data: param,
    };
};

const setDeliveryDate = param => {
    modalOptions.value = {
        show: true,
        component: 'delivery-form',
        data: param,
    };
};

const confirmDelivery = param => {
    modalOptions.value = {
        show: true,
        component: 'confirm-delivery',
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

const recordNotLocked = status => {
    return !includes(['delivered', 'cancelled'], status);
};

const statusColors = {
    unpaid: 'text-yellow-600 dark:text-yellow-500',
    paid: 'text-cyan-600 dark:text-cyan-500',
    'for delivery': 'text-indigo-600 dark:text-indigo-500',
    delivered: 'text-green-600 dark:text-green-500',
    cancelled: 'text-red-600 dark:text-red-500',
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Purchase Orders" />

        <div class="mt-12 flex flex-col space-y-3">
            <Filter />

            <CustomTable :rows="data.data" :columns="columns">
                <template #quotationCustomer="{ rowData }">
                    <div class="flex flex-col space-y-1">
                        <p class="font-medium">{{ rowData.quotation.customer.email }}</p>
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-3">
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faTag" class="text-center" />
                                <span>{{ rowData.quotation.customer.code }}</span>
                            </p>
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneFlip" class="text-center" />
                                <span class="italic">{{ rowData.quotation.customer.contact_number }}</span>
                            </p>
                            <p class="text-sm flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPhoneVolume" class="text-center" />
                                <span class="italic">{{ rowData.quotation.customer.viber_id ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>
                </template>

                <template #quotation="{ rowData }">
                    <p class="text-sm">{{ `${filter(rowData.quotation.items, x => x.status === 'available').length} items` }}</p>
                </template>

                <template #paymentDetails="{ rowData }">
                    <p class="block text-sm">{{ rowData.payment_details }}</p>
                </template>

                <template #deliveryDate="{ rowData }">
                    <p class="text-sm">{{ formatDate(rowData.delivery_date) }}</p>
                </template>

                <template #status="{ rowData }">
                    <div class="flex flex-col md:items-center">
                        <TableTag :class="statusColors[rowData.status]">{{ rowData.status }}</TableTag>
                        <span v-if="!recordNotLocked(rowData.status)" class=" text-sm">
                            {{ formatDate(rowData.updated_at) }}
                        </span>
                    </div>
                </template>

                <template #actions="{ rowData }">
                    <TableMenu>
                        <TableMenuItem @click="includes(['delivered', 'cancelled'], rowData.status) ? viewQuotationDetails(rowData) : editItems(rowData)">
                            {{ includes(['delivered', 'cancelled'], rowData.status) ? 'View Items' : 'Edit Items' }}
                        </TableMenuItem>
                        <TableMenuItem v-if="!rowData.delivery_date && recordNotLocked(rowData.status)" @click="setPaymentDetails(rowData)">
                            {{ rowData.payment_details ? 'Edit Payment' : 'Set Payment' }}
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.delivery_date && recordNotLocked(rowData.status)" @click="setDeliveryDate(rowData)">
                            Edit Delivery Date
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.delivery_date && recordNotLocked(rowData.status)" @click="confirmDelivery(rowData)">
                            Confirm Delivery
                        </TableMenuItem>
                        <TableMenuItem v-else-if="rowData.payment_details && recordNotLocked(rowData.status)" @click="setDeliveryDate(rowData)">
                            Schedule Delivery
                        </TableMenuItem>
                        <TableMenuItem v-if="recordNotLocked(rowData.status)" @click="confirmCancellation(rowData)" class="text-red-600 dark:text-red-500">
                            Cancel Order
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
                <div v-if="modalOptions.data" class="flex items-center space-x-1">
                    <p class="font-medium">{{ modalOptions.data.reference_number }}</p>
                    <p class="">{{ modalOptions.data.quotation.customer.email }}</p>
                </div>
            </Transition>
        </template>

        <Transition
            leave-active-class="opacity duration-200 ease-in-out"
            leave-to-class="opacity-0"
        >
            <ItemForm v-if="modalOptions.component === 'item-form'" :data="modalOptions.data.quotation" @saved="closeModal()" />
            <QuotationDetails v-else-if="modalOptions.component === 'quotation-details'" :data="modalOptions.data" />
            <PaymentForm v-else-if="modalOptions.component === 'payment-form'" :data="modalOptions.data" @saved="closeModal()" />
            <DeliveryForm v-else-if="modalOptions.component === 'delivery-form'" :data="modalOptions.data" @saved="closeModal()" />
            <ConfirmDelivery v-else-if="modalOptions.component === 'confirm-delivery'" :data="modalOptions.data" @confirmed="closeModal()" />
            <ConfirmCancellation v-else-if="modalOptions.component === 'confirm-cancellation'" :data="modalOptions.data" @confirmed="closeModal()" />
        </Transition>
    </Modal>
</template>
