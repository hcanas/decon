<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import InputLabel from "@/Components/Form/InputLabel.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import Textarea from "@/Components/Form/Textarea.vue";
import PrimaryButton from "@/Components/Button/PrimaryButton.vue";
import InputError from "@/Components/Form/InputError.vue";

const form = useForm({
    name: '',
    email: '',
    contact_number: '',
    message: '',
});

function submit() {
    form.post(route('send_inquiry'), {
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <MainLayout>
        <section class="w-full md:max-w-7xl md:mx-auto px-3 md:px-0 py-6">
            <Head title="Contact Us" />

            <div class="flex flex-col items-center">
                <div class="flex flex-col space-y-3">
                    <h3 class="uppercase text-center text-xl md:text-3xl font-medium leading-4">We are here to help!</h3>
                    <h6 class="text-center font-medium italic">For questions or concerns about our services, send us a message and we will respond within 24 hours.</h6>
                </div>

                <div class="w-full flex flex-col md:flex-row md:space-x-12 mt-6 md:mt-12">
                    <div class="flex-shrink-0 hidden md:block">
                        <img src="/images/contactUs2.jpg" class="w-[40rem] rounded" />
                    </div>
                    <form @submit.prevent="submit" class="flex-grow">
                        <div class="w-full flex flex-col space-y-3 md:px-6 md:px-0">
                            <p v-if="form.wasSuccessful" class="text-green-600 dark:text-green-500">Your inquiry has been sent.</p>
                            <div class="flex flex-col">
                                <InputLabel value="Name" />
                                <TextInput v-model="form.name" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Email Address" />
                                <TextInput v-model="form.email" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Contact Number" />
                                <TextInput v-model="form.contact_number" />
                                <InputError :message="form.errors.contact_number" />
                            </div>
                            <div class="flex flex-col">
                                <InputLabel value="Message" />
                                <Textarea v-model="form.message" class="min-h-[10rem]" />
                                <InputError :message="form.errors.message" />
                            </div>

                            <PrimaryButton>Send</PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
