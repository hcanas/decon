<script setup>
import { useForm } from '@inertiajs/vue3';
import SuccessButton from '@/Components/Button/SuccessButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';
import { CheckCircleIcon } from '@heroicons/vue/24/solid';
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
    form.patch(route('users.update', { user: props.user.id }));
    emit('success');
};
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p>Change access level of <span class="px-1 py-0.5 bg-gray-100 rounded">{{ user.email }}</span>.</p>

        <RadioGroup v-model="form.access_level">
            <div class="space-y-2">
                <RadioGroupOption :value="'regular'" as="template" v-slot="{ checked }">
                    <div :class="checked ? 'bg-gray-600 shadow' : 'bg-gray-100'" class="px-4 py-3 rounded-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <RadioGroupLabel as="p" :class="checked ? 'text-white' : 'text-gray-600'" class="font-medium">Regular</RadioGroupLabel>
                                <RadioGroupDescription as="p" :class="checked ? 'text-white' : 'text-gray-600'" class="text-sm">
                                    Summary of regular user roles and responsibilities.
                                </RadioGroupDescription>
                            </div>
                            <CheckCircleIcon v-if="checked" :class="checked ? 'text-white' : 'text-gray-600'" class="h-6 w-6" />
                        </div>
                    </div>
                </RadioGroupOption>
                <RadioGroupOption :value="'admin'" as="template" v-slot="{ checked }">
                    
                    <div :class="checked ? 'bg-gray-600' : 'bg-gray-100'" class="px-4 py-3 rounded-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <RadioGroupLabel as="p" :class="checked ? 'text-white' : 'text-gray-600'" class="font-medium">Admin</RadioGroupLabel>
                                <RadioGroupDescription as="p" :class="checked ? 'text-white' : 'text-gray-600'" class="text-sm">
                                    Summary of admin user roles and responsibilities.
                                </RadioGroupDescription>
                            </div>
                            <CheckCircleIcon v-if="checked" :class="checked ? 'text-white' : 'text-gray-600'" class="h-6 w-6" />
                        </div>
                    </div>
                </RadioGroupOption>
            </div>
        </RadioGroup>

        <div class="flex items-center justify-between">
            <SecondaryButton @click="$emit('close')">Close</SecondaryButton>
            <SuccessButton @click="submit">Update</SuccessButton>
        </div>
    </div>
</template>