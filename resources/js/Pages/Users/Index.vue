<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Datatable from '@/Components/Datatable.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import UserForm from '@/Pages/Users/Partials/Form.vue';
import UpdateStatus from '@/Pages/Users/Partials/UpdateStatus.vue';
import UpdateAccessLevel from '@/Pages/Users/Partials/UpdateAccessLevel.vue';
import Modal from '@/Components/Modal.vue';
import Tag from '@/Components/Tag.vue';
import { PencilSquareIcon, LockClosedIcon, LockOpenIcon } from '@heroicons/vue/24/solid';

defineProps({
    filters: Object,
    users: {
        type: Object,
        required: true,
    },
});

const columns = [
    { field: 'email', title: 'Email', sortable: true, class: 'w-2/3 text-left' },
    { field: 'name', title: 'Name', sortable: true, class: 'text-left' },
];

const modalOptions = ref({
    show: false,
    data: null,
    component: '',
});

const showModal = (component, data) => {
    modalOptions.value.show = true;
    modalOptions.value.data = data;
    modalOptions.value.component = component;
};

const closeModal = () => {
    modalOptions.value.show = false;
    
    const handler = setTimeout(() => {
        modalOptions.value.component = '';
        modalOptions.value.data = null;
        clearTimeout(handler);
    }, 200);
};

const updateUsers = () => {
    closeModal();
    router.reload();
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Users</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Datatable 
                    :data="users.data"
                    :totalRows="users.total"
                    :filters="filters"
                    :columns="columns"
                    :links="users.links"
                    :baseUrl="'/users'"
                >
                    <template #controls>
                        <PrimaryButton @click="showModal('user-form')">New User</PrimaryButton>
                    </template>

                    <template #emailData="{ data }">
                        <div class="flex items-center justify-between">
                            <p class="flex items-start space-x-2">
                                <span>{{ data.email }}</span>
                                <Tag :class="{ 'text-white bg-green-500': data.email_verified_at }">{{ data.email_verified_at ? 'verified' : 'unverified' }}</Tag>
                                <Tag :class="data.access_level === 'admin' ? 'text-white bg-indigo-500' : 'text-white bg-sky-500'">{{ data.access_level }}</Tag>
                                <Tag :class="data.status === 'blocked' ? 'text-white bg-red-500' : 'text-white bg-green-500'">{{ data.status }}</Tag>
                            </p>
                            <div v-if="$page.props.auth.user.id !== data.id" class="flex items-center space-x-2">
                                <button @click="showModal('update-access-level', data)" title="Edit Access Level" class="p-1 text-gray-600 hover:text-sky-600 hover:bg-gray-200 rounded shadow transition ease-in-out">
                                    <PencilSquareIcon class="w-4 h-4" />
                                </button>
                                <button v-if="data.status !== 'blocked'" @click="showModal('update-status', data)" title="Block" class="p-1 text-gray-600 hover:text-white hover:bg-red-500 rounded shadow transition ease-in-out">
                                    <LockClosedIcon class="w-4 h-4" />
                                </button>
                                <button v-else @click="showModal('update-status', data)" title="Unblock" class="p-1 text-gray-600 hover:text-white hover:bg-green-500 rounded shadow transition ease-in-out">
                                    <LockOpenIcon class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </template>
                </Datatable>
            </div>
        </div>

        <Modal :show="modalOptions.show">
            <template #title>
                <span v-if="modalOptions.component === 'user-form'">User Information</span>
                <span v-else-if="modalOptions.component === 'update-status'">User Status</span>
                <span v-else-if="modalOptions.component === 'update-access-level'">User Access Level</span>
            </template>

            <UserForm v-if="modalOptions.component === 'user-form'" @close="closeModal" />
            <UpdateStatus v-else-if="modalOptions.component === 'update-status'" :user="modalOptions.data" @close="closeModal" @success="updateUsers" />
            <UpdateAccessLevel v-else-if="modalOptions.component === 'update-access-level'" :user="modalOptions.data" @close="closeModal" @success="updateUsers" />
        </Modal>
    </AuthenticatedLayout>
</template>
