<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onMounted, ref} from "vue";
import Head from "@/Components/Head.vue";
import InputLabel from "@/Components/Form/InputLabel.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import CustomTable from "@/Components/Table/CustomTable.vue";
import NumberInput from "@/Components/Form/NumberInput.vue";
import {reduce, map, find, forEach, remove} from "lodash";
import {useFormatter} from "@/Composables/formatter.js";
import {useCart} from "@/Composables/cart.js";
import TableButton from "@/Components/Table/TableButton.vue";
import {faTrashCan} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/Form/InputError.vue";

const { formatCurrency } = useFormatter();

const form = useForm({
    code: '',
    email: '',
    contact_number: '',
    viber_id: '',
    items: [],
});

const items = ref([]);
const cart = useCart();

const columns = [
    { field: 'product', title: 'Product', align: 'left' },
    // { field: 'price', title: 'Price', align: 'right', format: 'currency' },
    { field: 'qty', title: 'Qty', align: 'right' },
    // { field: 'total', title: 'Total', align: 'right', format: 'currency' },
];

const total = computed(() => reduce(items.value, (sum, o) => sum + (o.qty * o.price), 0));

const updateQty = (id, qty) => {
    cart.update(id, qty);

    forEach(items.value, (item, key) => {
        if (item.id === id) {
            items.value[key].qty = qty;
            return false;
        }
    });
};

const removeItem = id => {
    cart.remove(id);
    remove(items.value, item => item.id === id);
};

const findItemIndex = param => {
    let index = 0;

    forEach(items.value, (val, key) => {
        if (val.id === param.id) {
            index = key;
            return false;
        }
    });

    return index;
};

const requestQuotation = () => {
    form.items = items.value;

    form.post(route('quotations.store'), {
        onSuccess: () => {
            form.reset();
            items.value = [];
            cart.clear();
        },
    });
};

onMounted(() => {
    const ids = reduce(cart.items, (o, v) => o ? o + ',' + v.id : v.id, '');

    window.axios.get(route('cart_items.index', { ids }))
        .then(response => {
            items.value = map(response.data, item => {
                return {
                    ...item,
                    qty: find(cart.items, x => x.id === item.id).qty
                };
            });
        });
});
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="Cart" />

            <p v-if="form.wasSuccessful" class="p-3 bg-neutral-50 dark:bg-neutral-900 text-green-600 dark:text-green-500 rounded mt-6">Your quotation request is under review. You will receive an email when the review is done.</p>

            <div v-if="items.length" class="flex flex-col space-y-6 mt-6">
                <CustomTable :columns="columns" :rows="items">
                    <template #product="{ rowData }">
                        <div class="flex items-center space-x-3">
                            <img src="/storage/images/placeholder.png" class="w-24 rounded" />
                            <div class="flex flex-col">
                                <p>{{ rowData.brand }} ({{ rowData.name }})</p>
                                <p class="text-sm block truncate">{{ rowData.description }}</p>
                                <TableButton @click="removeItem(rowData.id)" class="text-red-600 dark:text-red-500">
                                    <div class="flex items-center space-x-1">
                                        <FontAwesomeIcon :icon="faTrashCan" />
                                        <span>Remove</span>
                                    </div>
                                </TableButton>
                            </div>
                        </div>
                    </template>

                    <template #qty="{ rowData }">
                        <NumberInput :modelValue="rowData.qty" @update:modelValue="updateQty(rowData.id, $event)" class="md:text-right" />
                        <InputError :message="form.errors[`items.${findItemIndex(rowData)}.qty`]" />
                    </template>

                    <template #total="{ rowData }">
                        {{ formatCurrency(rowData.price * rowData.qty) }}
                    </template>
                </CustomTable>

<!--                <p class="font-bold text-right">{{ formatCurrency(total) }}</p>-->

                <form>
                    <div class="grid md:grid-cols-2 md:divide-x space-y-12 md:space-y-0">
                        <div class="flex flex-col space-y-3 md:pr-12">
                            <h3 class=" italic font-medium border-b">For New Customers</h3>

                            <div class="flex flex-col space-y-6">
                                <div class="flex-grow flex flex-col">
                                    <InputLabel>Email Address</InputLabel>
                                    <TextInput type="email" v-model="form.email" />
                                    <InputError :message="form.errors.email" />
                                </div>
                                <div class="flex-grow flex flex-col">
                                    <InputLabel>Contact #</InputLabel>
                                    <TextInput v-model="form.contact_number" />
                                    <InputError :message="form.errors.contact_number" />
                                </div>
                                <div class="flex-grow flex flex-col">
                                    <InputLabel>Viber ID</InputLabel>
                                    <TextInput v-model="form.viber_id" />
                                    <InputError :message="form.errors.viber_id" />
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-3 md:pl-12">
                            <h3 class=" italic font-medium border-b">For Repeat Customers</h3>

                            <div class="flex flex-col space-y-6">
                                <div class="flex-grow flex flex-col">
                                    <InputLabel>Code</InputLabel>
                                    <TextInput v-model="form.code" />
                                    <span class="text-sm italic">This can be found in your previously requested quotations.</span>
                                    <InputError :message="form.errors.code" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="flex justify-center">
                    <PrimaryButton @click="requestQuotation()" class="w-96 py-3">Request Quotation</PrimaryButton>
                </div>
            </div>

            <p v-else class="p-3 bg-neutral-50 dark:bg-neutral-900 rounded mt-6">Your cart is empty.</p>
        </section>
    </MainLayout>
</template>

<style scoped>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}

input[type='number'] {
    -moz-appearance:textfield;
}
</style>
