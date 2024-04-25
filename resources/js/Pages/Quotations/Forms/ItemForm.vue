<script setup>
import {computed, onMounted, ref} from "vue";
import {forEach, reduce} from "lodash";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import NumberInput from "@/Components/Form/NumberInput.vue";
import TableButton from "@/Components/Table/TableButton.vue";
import {faCheck, faTrashCan, faXmark} from "@fortawesome/free-solid-svg-icons";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {useFormatter} from "@/Composables/formatter.js";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/Form/InputError.vue";

const props = defineProps({
    data: Object,
});

const { formatCurrency } = useFormatter();

const form = useForm({
    items: [],
});

const initialTotal = computed(() => reduce(form.items, (sum, x) => sum + (x.qty * x.price), 0));

const deductions = computed(() => reduce(form.items, (sum, x) => x.status === 'unavailable'
    ? sum + (x.qty * x.price)
    : sum, 0
));

const markAvailable = item => {
    forEach(form.items, (x, i) => {
        if (x.id === item.id) form.items[i].status = 'available';
    });
};

const markUnavailable = item => {
    forEach(form.items, (x, i) => {
        if (x.id === item.id) form.items[i].status = 'unavailable';
    });
};

const saveChanges = () => {
    form.patch(route('admin.quotation_items.update'));
};

onMounted(() => {
    form.items = JSON.parse(JSON.stringify(props.data.items));
});
</script>

<template>
    <div class="flex flex-col space-y-6">
        <table class="w-full border-collapsed">
            <tr class="border-b">
                <th class="p-2">Product</th>
                <th class="w-1 text-right p-2">Qty</th>
                <th class="w-72 p-2">Measurement Unit</th>
                <th class="text-right w-1 p-2">Price</th>
                <th class="text-right w-1 p-2">Total</th>
            </tr>
            <tr v-for="(item, key) in form.items" :key="item.id" class="relative border-b hover:bg-gray-50">
                <td class="group flex flex-col p-2">
                    <p class="relative flex flex-col">
                        <span :class="item.status">
                            {{ item.product.name }} ({{ item.product.brand?.value ?? 'No Brand' }})
                        </span>
                        <span class="w-full text-gray-600 text-sm italic truncate" :class="item.status">
                            {{ item.product.description }}
                        </span>
                    </p>
                    <TableButton @click="markUnavailable(item)" v-if="item.status === 'available'" class="text-red-500 w-fit">
                        <div class="flex items-center space-x-1">
                            <FontAwesomeIcon :icon="faXmark" />
                            <span>Mark as unavailable</span>
                        </div>
                    </TableButton>
                    <TableButton @click="markAvailable(item)" v-else class="text-green-500 w-fit">
                        <div class="flex items-center space-x-1">
                            <FontAwesomeIcon :icon="faCheck" />
                            <span>Mark as available</span>
                        </div>
                    </TableButton>
                </td>
                <td class="text-right p-2">
                    <div class="flex flex-col space-y-1 ">
                        <NumberInput v-if="item.status === 'available'" v-model="item.qty" class="text-sm text-right" />
                        <span v-else :class="item.status">{{ item.qty }}</span>
                        <InputError :message="form.errors[`items.${key}.qty`]" />
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex flex-col space-y-1 ">
                        <TextInput v-if="item.status === 'available'" v-model="item.measurement_unit" class="text-sm" />
                        <span v-else :class="item.status">{{ item.measurement_unit }}</span>
                        <InputError :message="form.errors[`items.${key}.measurement_unit`]" />
                    </div>
                </td>
                <td class="text-right p-2">
                    <div class="flex flex-col space-y-1 ">
                        <NumberInput v-if="item.status === 'available'" v-model="item.price" class="text-sm text-right" />
                        <span v-else :class="item.status">{{ item.price }}</span>
                        <InputError :message="form.errors[`items.${key}.price`]" />
                    </div>
                </td>
                <td class="text-right p-2" :class="item.status">{{ formatCurrency(item.price * item.qty) }}</td>
            </tr>
        </table>

        <div class="flex flex-col items-end">
            <span class="font-medium">{{ formatCurrency(initialTotal - deductions) }}</span>
        </div>

        <div class="flex items-center justify-end space-x-6">
            <span v-if="form.wasSuccessful" class="text-sm text-gray-500 font-medium">Changes have been saved.</span>
            <PrimaryButton @click="saveChanges()">Save Changes</PrimaryButton>
        </div>
    </div>
</template>

<style scoped>
.unavailable {
    @apply text-gray-400 line-through
}
</style>
