<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import UserForm from '@/Pages/Users/Partials/Form.vue';
import UpdateStatus from '@/Pages/Users/Partials/UpdateStatus.vue';
import UpdateAccessLevel from '@/Pages/Users/Partials/UpdateAccessLevel.vue';
import Modal from '@/Components/Modal.vue';
import Head from '@/Components/Head.vue';
import CustomTable from "@/Components/Table/CustomTable.vue";
import Pagination from "@/Components/Pagination.vue";
import TableTag from "@/Components/Table/TableTag.vue";
import Filter from "@/Pages/Users/Filter.vue";
import TableMenu from "@/Components/Table/TableMenu.vue";
import TableMenuItem from "@/Components/Table/TableMenuItem.vue";
import Delete from "@/Pages/Users/Partials/Delete.vue";

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

const viewActivityLog = param => {
    router.get(route('admin.user_activity_log', { user: param }));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Users'">
            <template #actions>
                <PrimaryButton @click="showModal('user-form')">New User</PrimaryButton>
            </template>
        </Head>

        <div class="mt-6 md:mt-12 flex flex-col space-y-3">
            <Filter />

            <CustomTable
                :rows="users.data"
                :filters="filters"
                :columns="columns"
                class="mt-6 md:mt-12"
            >
                <template #accessLevel="{ rowData }">
                    <div class="flex flex-col md:items-center space-y-1">
                        <TableTag :class="rowData.access_level === 'admin' ? 'text-indigo-500 dark:text-indigo-500' : 'text-sky-500 dark:text-sky-500'">
                            {{ rowData.access_level }}
                        </TableTag>
                    </div>
                </template>

                <template #status="{ rowData }">
                    <div class="flex flex-col md:items-center space-y-1">
                        <TableTag :class="rowData.status === 'blocked' ? 'text-red-500 dark:text-red-500' : 'text-green-500 dark:text-green-500'">
                            {{ rowData.status }}
                        </TableTag>
                    </div>
                </template>

                <template #actions="{ rowData }">
                    <TableMenu v-if="rowData.id !== $page.props.auth.user.id">
                        <TableMenuItem @click="viewActivityLog(rowData.id)">
                            View Activity Log
                        </TableMenuItem>
                        <TableMenuItem @click="showModal('update-access-level', rowData)">
                            Change Access Level
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.status === 'active'" @click="showModal('update-status', rowData)">
                            Block User
                        </TableMenuItem>
                        <TableMenuItem v-if="rowData.status === 'blocked'" @click="showModal('update-status', rowData)">
                            Unblock User
                        </TableMenuItem>
                        <TableMenuItem @click="showModal('delete-user', rowData)" class="text-red-600 dark:text-red-500">
                            Delete User
                        </TableMenuItem>
                    </TableMenu>
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
                <span v-else-if="modalOptions.component === 'delete-user'">Delete User</span>
            </template>

            <UserForm v-if="modalOptions.component === 'user-form'" @close="closeModal" />
            <UpdateStatus v-else-if="modalOptions.component === 'update-status'" :user="modalOptions.data" @close="closeModal" @success="updateUsers" />
            <UpdateAccessLevel v-else-if="modalOptions.component === 'update-access-level'" :user="modalOptions.data" @close="closeModal" @success="updateUsers" />
            <Delete v-else-if="modalOptions.component === 'delete-user'" :user="modalOptions.data" @success="updateUsers" />
        </Modal>
    </AuthenticatedLayout>
</template>
