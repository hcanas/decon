<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/Form/InputLabel.vue';
import InputError from '@/Components/Form/InputError.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';
import SuggestionInput from "@/Components/Form/SuggestionInput.vue";

const props = defineProps({
    data: Object,
});

defineEmits(['close']);

const form = useForm(props.data ?? {
    brand: '',
    name: '',
    description: '',
    price: 0,
    stock: 0,
    measurement_unit: '',
    product_category: '',
});

const submit = () => {
    if (form.id) {
        form.put(route('products.update', { product: form.id }));
    } else {
        form.post(route('products.store'), {
            onSuccess: () => form.reset(),
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
            <InputLabel for="brand" value="Brand" />

            <SuggestionInput v-model="form.brand" :src="route('brands.search')" />

            <InputError class="mt-2" :message="form.errors.brand" />
        </div>

        <div>
            <InputLabel for="name" value="Name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
            <InputLabel for="description" value="Description" />

            <TextInput
                id="description"
                type="text"
                class="mt-1 block w-full"
                v-model="form.description"
            />

            <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <div>
            <InputLabel for="price" value="Price" />

            <NumberInput
                id="price"
                type="number"
                class="mt-1 block w-full"
                v-model="form.price"
            />

            <InputError class="mt-2" :message="form.errors.price" />
        </div>

        <div>
            <InputLabel for="stock" value="Stock" />

            <NumberInput
                id="stock"
                type="number"
                class="mt-1 block w-full"
                v-model="form.stock"
            />

            <InputError class="mt-2" :message="form.errors.stock" />
        </div>

        <div>
            <InputLabel for="measurement_unit" value="Measurement Unit" />

            <SuggestionInput v-model="form.measurement_unit" :src="route('measurement_units.search')" />

            <InputError class="mt-2" :message="form.errors.measurement_unit" />
        </div>

        <div>
            <InputLabel for="product_category" value="Product Category" />

            <SuggestionInput v-model="form.product_category" :src="route('product_categories.search')" />

            <InputError class="mt-2" :message="form.errors.product_category" />
        </div>


        <p v-if="form.wasSuccessful" class="text-green-600">Product has been {{ form.id ? 'updated' : 'created' }}.</p>

        <div class="flex items-center justify-between">
            <SecondaryButton type="button" @click="$emit('close')">Close</SecondaryButton>
            <PrimaryButton type="submit">Register</PrimaryButton>
        </div>
    </form>
</template>
