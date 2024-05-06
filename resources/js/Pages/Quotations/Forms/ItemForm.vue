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
            <tr class="hidden md:table-row border-b">
                <th class="p-2">Product</th>
                <th class="w-1 text-right p-2">Qty</th>
                <th class="w-72 p-2">Measurement Unit</th>
                <th class="text-right w-1 p-2">Price</th>
                <th class="text-right w-1 p-2">Total</th>
                <th class="w-1"></th>
            </tr>
            <tr v-for="(item, key) in form.items" :key="item.id" class="flex flex-col md:table-row border-b hover:bg-neutral-50 dark:hover:bg-neutral-950 transition ease-in-out">
                <td class="group flex flex-col p-2">
                    <span class="text-xs uppercase md:hidden border-b w-fit">Product</span>
                    <p class="flex flex-col">
                        <span :class="item.status" class="font-medium">
                            {{ item.product.name }}
                        </span>
                        <span class="w-full text-sm italic truncate" :class="item.status">
                            {{ item.product.description }}
                        </span>
                        <span class="text-xs" :class="item.status">
                            {{ item.product.brand?.value ?? 'No Brand' }}
                        </span>
                    </p>
                </td>
                <td class="p-2">
                    <div class="flex flex-col space-y-1 ">
                        <span class="text-xs uppercase md:hidden border-b w-fit">Qty</span>
                        <NumberInput v-if="item.status === 'available'" v-model="item.qty" class="text-sm md:text-right" />
                        <span v-else :class="item.status" class="md:text-right">{{ item.qty }}</span>
                        <InputError :message="form.errors[`items.${key}.qty`]" />
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex flex-col space-y-1 ">
                        <span class="text-xs uppercase md:hidden border-b w-fit">Measurement Unit</span>
                        <TextInput v-if="item.status === 'available'" v-model="item.measurement_unit" class="text-sm" />
                        <span v-else :class="item.status">{{ item.measurement_unit }}</span>
                        <InputError :message="form.errors[`items.${key}.measurement_unit`]" />
                    </div>
                </td>
                <td class="p-2">
                    <div class="flex flex-col space-y-1 ">
                        <span class="text-xs uppercase md:hidden border-b w-fit">Price</span>
                        <NumberInput v-if="item.status === 'available'" v-model="item.price" class="text-sm md:text-right" />
                        <span v-else :class="item.status" class="md:text-right">{{ item.price }}</span>
                        <InputError :message="form.errors[`items.${key}.price`]" />
                    </div>
                </td>
                <td class="p-2" :class="item.status">
                    <p class="flex flex-col">
                        <span class="text-xs uppercase md:hidden border-b w-fit">Total</span>
                        <span :class="item.status">{{ formatCurrency(item.price * item.qty) }}</span>
                    </p>
                </td>
                <td class="p-2">
                    <button v-if="item.status === 'available'" @click="markUnavailable(item)" title="Mark as unavailable" class="text-red-600 dark:text-red-500">
                        <FontAwesomeIcon :icon="faTrashCan" :class="'hidden md:inline'" />
                        <span class="text-xs md:hidden">Mark as Unavailable</span>
                    </button>
                    <button v-else @click="markAvailable(item)" title="Mark as available" class="text-green-600 dark:text-green-500">
                        <FontAwesomeIcon :icon="faCheck" :class="'hidden md:inline'" />
                        <span class="text-xs md:hidden">Mark as available</span>
                    </button>
                </td>
            </tr>
        </table>

        <div class="flex flex-col items-end">
            <p class="font-medium">{{ formatCurrency(initialTotal - deductions) }}</p>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-end md:space-x-6 space-y-3 md:space-y-0">
            <p v-if="form.wasSuccessful" class="text-sm font-medium">Changes have been saved.</p>
            <PrimaryButton @click="saveChanges()">Save Changes</PrimaryButton>
        </div>
    </div>
</template>

<style scoped>
.unavailable {
    @apply line-through text-neutral-300 dark:text-neutral-600
}
</style>
