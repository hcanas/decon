<script setup>
import { useForm } from '@inertiajs/vue3';
import SuccessButton from '@/Components/Button/SuccessButton.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';
import { onMounted, ref } from "vue";
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
    form.patch(route('users.update', { user: props.user.id }));
    emit('success');
};

const canUpdate = ref(false);
const timer = ref(5);

onMounted(() => {
    const intervalHandler = setInterval(() => {
        timer.value--;
    }, 1000);

    const timeoutHandler = setTimeout(() => {
        canUpdate.value = true;
        clearInterval(intervalHandler);
        clearTimeout(timeoutHandler);
    }, 5000);
});
</script>

<template>
    <div class="flex flex-col space-y-6">
        <p class="dark:text-gray-300">Change access level of <span class="px-1 py-0.5 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded">{{ user.email }}</span>.</p>

        <RadioGroup v-model="form.access_level">
            <div class="space-y-2">
                <RadioGroupOption :value="'regular'" as="template" v-slot="{ checked }">
                    <div :class="checked ? 'bg-primary-100 shadow' : 'bg-gray-100'" class="px-4 py-3 rounded-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <RadioGroupLabel as="p" class="text-gray-600 font-medium">Regular</RadioGroupLabel>
                                <RadioGroupDescription as="p" class="text-sm text-gray-600">
                                    Summary of regular user roles and responsibilities.
                                </RadioGroupDescription>
                            </div>
                            <FontAwesomeIcon :icon="faCheckCircle" v-if="checked" class="h-6 w-6 text-primary-400" />
                        </div>
                    </div>
                </RadioGroupOption>
                <RadioGroupOption :value="'admin'" as="template" v-slot="{ checked }">

                    <div :class="checked ? 'bg-primary-100' : 'bg-gray-100'" class="px-4 py-3 rounded-lg cursor-pointer">
                        <div class="flex items-center justify-between">
                            <div>
                                <RadioGroupLabel as="p" class="text-gray-600 font-medium">Admin</RadioGroupLabel>
                                <RadioGroupDescription as="p" class="text-sm text-gray-600">
                                    Summary of admin user roles and responsibilities.
                                </RadioGroupDescription>
                            </div>
                            <FontAwesomeIcon :icon="faCheckCircle" v-if="checked" class="h-6 w-6 text-primary-400" />
                        </div>
                    </div>
                </RadioGroupOption>
            </div>
        </RadioGroup>

        <div class="flex items-center justify-between">
            <SecondaryButton @click="$emit('close')">Close</SecondaryButton>
            <SuccessButton v-if="canUpdate" @click="submit">Update</SuccessButton>
            <span v-else class="text-sm text-gray-600 dark:text-gray-300">You can update in {{ timer }}.</span>
        </div>
    </div>
</template>
