<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/Form/InputLabel.vue';
import InputError from '@/Components/Form/InputError.vue';
import NumberInput from '@/Components/Form/NumberInput.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import SuggestionInput from "@/Components/Form/SuggestionInput.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faCamera} from "@fortawesome/free-solid-svg-icons";
import {ref} from "vue";

const props = defineProps({
    data: Object,
});

defineEmits(['close']);

const form = useForm(props.data
    ? {
        _method: 'put',
        ...props.data,
        image: '',
        product_category: props.data.sub_category
            ? props.data.category + ':' + props.data.sub_category
            : props.data.category,
    }
    : {
        image: '',
        name: '',
        description: '',
        brand: '',
        price: 0,
        stock: 0,
        measurement_unit: '',
        product_category: '',
    }
);

const imageFile = ref(null);
const imagePreview = ref(props.data?.image
    ? '/storage/images/' + props.data.image
    : null);

const uploadImage = e => {
    form.image = e.target.files[0];
    imagePreview.value = URL.createObjectURL(e.target.files[0]);
};

const submit = () => {
    if (form.id) {
        form.post(route('admin.products.update', { product: form.id }));
    } else {
        form.post(route('admin.products.store'), {
            onSuccess: () => {
                form.reset();
                imagePreview.value = null;
            }
        });
    }
};
</script>

<template>
    <div class="flex flex-col">
        <div class="flex flex-col items-center">
            <div>
                <img :src="imagePreview ?? '/storage/images/placeholder.png'" class="max-w-[250px] max-h-[250px]" />
            </div>
            <InputError class="mt-2" :message="form.errors.image" />
            <button @click="$refs.imageFile.click()" class="mt-2 hover:underline text-sm text-blue-600 dark:text-blue-500">
                <div class="flex items-center space-x-1">
                    <FontAwesomeIcon :icon="faCamera" />
                    <span>Upload Image</span>
                </div>
            </button>
        </div>
        <form @submit.prevent="submit">
            <input type="file" @input="uploadImage($event)" accept="image/png, image/jpg, image/jpeg" ref="imageFile" class="hidden" />
            <div class="flex flex-col space-y-3">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="description" value="Description" />
                    <TextInput id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div>
                    <InputLabel for="brand" value="Brand" />
                    <SuggestionInput v-model="form.brand" :src="route('admin.brands.search')" />
                    <InputError class="mt-2" :message="form.errors.brand" />
                </div>

                <div>
                    <InputLabel for="category" value="Product Category" />
                    <SuggestionInput v-model="form.product_category" :src="route('admin.product_categories.search')" />
                    <InputError class="mt-2" :message="form.errors.product_category" />
                </div>

                <div>
                    <InputLabel for="measurement_unit" value="Measurement Unit" />
                    <SuggestionInput v-model="form.measurement_unit" :src="route('admin.measurement_units.search')" />
                    <InputError class="mt-2" :message="form.errors.measurement_unit" />
                </div>

                <div>
                    <InputLabel for="price" value="Price" />
                    <NumberInput id="price" type="number" step="0.01" class="mt-1 block w-full" v-model="form.price" />
                    <InputError class="mt-2" :message="form.errors.price" />
                </div>

                <div class="flex flex-col md:flex-row items-center md:space-x-3 space-y-1 md:space-y-0 md:justify-end">
                    <p v-if="form.wasSuccessful" class="text-green-600">Product has been {{ form.id ? 'updated' : 'created' }}.</p>
                    <PrimaryButton type="submit">Save</PrimaryButton>
                </div>
            </div>
        </form>
    </div>
</template>
