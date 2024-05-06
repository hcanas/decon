<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/Form/InputLabel.vue';
import InputError from '@/Components/Form/InputError.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faCheckCircle } from "@fortawesome/free-regular-svg-icons";
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from '@headlessui/vue';

defineEmits(['close']);

const form = useForm({
    name: '',
    email: '',
    access_level: 'regular',
});

const submit = () => {
    form.post(route('register'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="mt-6 space-y-6">
        <div>
            <InputLabel for="name" value="Name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                autofocus
                autocomplete="name"
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email" />

            <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                autocomplete="email"
            />

            <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
            <InputLabel value="Access Level" />
            <RadioGroup v-model="form.access_level">
                <div class="space-y-2">
                    <RadioGroupOption :value="'regular'" as="template" v-slot="{ checked }">
                        <div :class="checked ? 'bg-primary-100 dark:bg-primary' : 'bg-neutral-100 dark:bg-neutral-900'" class="px-4 py-3 rounded-lg cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <RadioGroupLabel as="p" class=" font-medium">Regular</RadioGroupLabel>
                                    <RadioGroupDescription as="p" class="text-sm">
                                        Summary of regular user roles and responsibilities.
                                    </RadioGroupDescription>
                                </div>
                                <FontAwesomeIcon :icon="faCheckCircle" v-if="checked" class="h-6 w-6 text-primary-400" />
                            </div>
                        </div>
                    </RadioGroupOption>
                    <RadioGroupOption :value="'admin'" as="template" v-slot="{ checked }">

                        <div :class="checked ? 'bg-primary-100 dark:bg-primary' : 'bg-neutral-100 dark:bg-neutral-900'" class="px-4 py-3 rounded-lg cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div>
                                    <RadioGroupLabel as="p" class=" font-medium">Admin</RadioGroupLabel>
                                    <RadioGroupDescription as="p" class="text-sm">
                                        Summary of admin user roles and responsibilities.
                                    </RadioGroupDescription>
                                </div>
                                <FontAwesomeIcon :icon="faCheckCircle" v-if="checked" class="h-6 w-6 text-primary-400" />
                            </div>
                        </div>
                    </RadioGroupOption>
                </div>
            </RadioGroup>

            <InputError class="mt-2" :message="form.errors.access_level" />
        </div>


        <div class="flex flex-col md:flex-row items-center md:justify-end md:space-x-3 space-y-3 md:space-y-0">
            <p v-if="form.wasSuccessful" class="text-green-600">Email verification link has been sent.</p>
            <PrimaryButton type="submit">Register</PrimaryButton>
        </div>
    </form>
</template>
