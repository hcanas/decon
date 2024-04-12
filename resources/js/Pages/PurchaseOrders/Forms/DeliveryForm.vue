<script setup>
import InputLabel from "@/Components/Form/InputLabel.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/Form/InputError.vue";

const props = defineProps({
    data: Object,
});

const emits = defineEmits(['saved']);

const form = useForm({
    delivery_date: props.data.delivery_date ?? '',
});

const save = () => {
    form.patch(route('admin.purchase_orders.update', { purchase_order: props.data.id }), {
        onSuccess: () => emits('saved'),
    })
};
</script>

<template>
    <form @submit.prevent="save()">
        <div class="flex flex-col space-y-6">
            <div class="flex flex-col space-y-1">
                <InputLabel value="Delivery Date" />
                <TextInput type="date" v-model="form.delivery_date" />
                <InputError :message="form.errors.delivery_date" />
            </div>
            <PrimaryButton type="submit" class="ms-auto">Save</PrimaryButton>
        </div>
    </form>
</template>
