<script setup>
import { useForm } from '@inertiajs/vue3';
import SuccessButton from '@/Components/Button/SuccessButton.vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faCheckCircle } from "@fortawesome/free-regular-svg-icons";
import {
    RadioGroup,
    RadioGroupLabel,
    RadioGroupDescription,
    RadioGroupOption,
} from '@headlessui/vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['success', 'close']);

const form = useForm({
    access_level: props.user.access_level,
});

const submit = () => {
    form.patch(route('admin.users.update', { user: props.user.id }), {
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="">Change access level of <span class="px-1 py-0.5 bg-neutral-100 dark:bg-neutral-700 rounded">{{ user.email }}</span>.</p>

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

        <div class="flex items-center justify-end">
            <SuccessButton @click="submit">Update</SuccessButton>
        </div>
    </div>
</template>
