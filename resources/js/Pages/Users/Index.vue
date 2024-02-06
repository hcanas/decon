<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import Datatable from '@/Components/Datatable.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import UserForm from '@/Pages/Users/Partials/Form.vue';
import UpdateStatus from '@/Pages/Users/Partials/UpdateStatus.vue';
import UpdateAccessLevel from '@/Pages/Users/Partials/UpdateAccessLevel.vue';
import Modal from '@/Components/Modal.vue';
import Tag from '@/Components/Tag.vue';
import Head from '@/Components/Head.vue';
import IconButton from "@/Components/Button/IconButton.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faPencil, faShieldHalved } from "@fortawesome/free-solid-svg-icons";

defineProps({
    filters: Object,
    users: {
        type: Object,
        required: true,
    },
});

const columns = [
    { field: 'name', title: 'Name', sortable: true },
    { field: 'email', title: 'Email', sortable: true, class: 'w-96' },
    { field: 'access_level', title: 'Access Level', sortable: true, class: 'w-40' },
    { field: 'status', title: 'Status', class: 'w-32' },
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
    <AuthenticatedLayout>
        <Head :title="'Users'">
            <template #actions>
                <PrimaryButton @click="showModal('user-form')">New User</PrimaryButton>
            </template>
        </Head>

        <div class="px-3 md:px-12">
            <Datatable
                :data="users"
                :filters="filters"
                :columns="columns"
            >
                <template #accessLevelData="{ data }">
                    <Tag class="w-min text-white uppercase" :class="data.access_level === 'admin' ? 'bg-indigo-500' : 'bg-sky-500'">
                        {{ data.access_level }}
                    </Tag>
                </template>

                <template #statusData="{ data }">
                    <Tag class="w-min text-white uppercase" :class="data.status === 'blocked' ? 'bg-red-500' : 'bg-green-500'">
                        {{ data.status }}
                    </Tag>
                </template>

                <template #actions="{ data }">
                    <div class="flex items-center space-x-2">
                        <IconButton @click="showModal('update-access-level', data)" class="hover:text-sky-500 focus:text-sky-500 active:text-sky-500 dark:hover:text-sky-500 dark:focus:text-sky-500 dark:active:text-sky-500">
                            <FontAwesomeIcon :icon="faPencil" />
                        </IconButton>
                        <IconButton @click="showModal('update-status', data)" class="hover:text-sky-500 focus:text-sky-500 active:text-sky-500 dark:hover:text-sky-500 dark:focus:text-sky-500 dark:active:text-sky-500">
                            <FontAwesomeIcon :icon="faShieldHalved" />
                        </IconButton>
                    </div>
                </template>
            </Datatable>
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
