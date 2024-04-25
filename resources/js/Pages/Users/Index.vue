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
import {faCancel, faCheck, faPencil, faShieldHalved, faXmark} from "@fortawesome/free-solid-svg-icons";
import CustomTable from "@/Components/Table/CustomTable.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import TableTag from "@/Components/Table/TableTag.vue";
import TableButton from "@/Components/Table/TableButton.vue";
import Filter from "@/Pages/Users/Filter.vue";

defineProps({
    filters: Object,
    users: {
        type: Object,
        required: true,
    },
});

const columns = [
    { field: 'email', title: 'Email', align: 'left' },
    { field: 'name', title: 'Name', align: 'left' },
    { field: 'access_level', title: 'Access Level' },
    { field: 'status', title: 'Status' },
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

        <div class="mt-12 flex flex-col space-y-3">
            <Filter />

            <CustomTable
                :rows="users.data"
                :filters="filters"
                :columns="columns"
                class="mt-6 md:mt-12"
            >
                <template #accessLevel="{ rowData }">
                    <div class="flex flex-col items-center space-y-1">
                        <TableTag :class="rowData.access_level === 'admin' ? 'text-indigo-500' : 'text-sky-500'">
                            {{ rowData.access_level }}
                        </TableTag>
                        <TableButton @click="showModal('update-access-level', rowData)" class="block text-blue-500">
                            <div class="flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faPencil" />
                                <span>Change</span>
                            </div>
                        </TableButton>
                    </div>
                </template>

                <template #status="{ rowData }">
                    <div class="flex flex-col items-center space-y-1">
                        <TableTag :class="rowData.status === 'blocked' ? 'text-red-500' : 'text-green-500'">
                            {{ rowData.status }}
                        </TableTag>
                        <TableButton v-if="rowData.status === 'active'" @click="showModal('update-status', rowData)" class="block text-red-500">
                            <div class="flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faCancel" />
                                <span>Block</span>
                            </div>
                        </TableButton>
                        <TableButton v-if="rowData.status === 'blocked'" @click="showModal('update-status', rowData)" class="block text-green-500">
                            <div class="flex items-center space-x-1">
                                <FontAwesomeIcon :icon="faCheck" />
                                <span>Unblock</span>
                            </div>
                        </TableButton>
                    </div>
                </template>
            </CustomTable>

            <Pagination
                :nextPageUrl="users.next_page_url"
                :prevPageUrl="users.prev_page_url"
                :totalRecords="users.total"
                :to="users.to"
                :from="users.from"
                :perPage="users.per_page"
                :currentPage="users.current_page"
            />
        </div>

        <Modal :show="modalOptions.show" @close="closeModal()">
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
