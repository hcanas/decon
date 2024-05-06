<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/Nav/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import Logout from './Partials/Logout.vue';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faChartSimple,
    faUsers,
    faSuitcaseMedical,
    faPersonRunning,
    faXmark,
    faBars,
    faHome, faTruckFast, faFileInvoice, faUserLock, faDoorOpen, faUserPen
} from "@fortawesome/free-solid-svg-icons";
import DarkModeToggle from "@/Components/DarkModeToggle.vue";

const showMenu = ref(false);

const showModal = ref(false);

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};

const confirmLogout = () => showModal.value = true;
const closeModal = () => showModal.value = false;
</script>

<template>
    <div class="w-screen h-screen flex flex-col md:flex-row overflow-hidden">
        <nav class="md:w-96 h-16 md:h-full relative z-20 md:z-0 dark:bg-neutral-800 transition ease-in-out">
            <div class="absolute md:relative w-screen md:w-full h-full md:h-auto flex items-center justify-between md:justify-center px-3 md:px-6 md:py-12 z-10 md:z-0 transition ease-in-out">
                <Link :href="route('home')">
                    <ApplicationLogo
                        class="block h-9 w-auto fill-current dark:text-white"
                    />
                </Link>

                <button @click="toggleMenu" class="md:hidden">
                    <FontAwesomeIcon :icon="showMenu ? faXmark : faBars" class="text-neutral-800 dark:text-white" />
                </button>
            </div>

            <div :class="showMenu ? 'translate-y-16 h-screen' : ''" class="w-full flex flex-col space-y-3 md:px-12 py-3 bg-white md:bg-transparent dark:bg-neutral-800 dark:md:bg-transparent overflow-hidden transform -translate-y-full md:transform-none transition ease-in-out">
                <div class="mx-auto">
                    <DarkModeToggle></DarkModeToggle>
                </div>
                <div class="flex flex-col space-y-1">
                    <NavLink :href="route('home')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faHome" class="w-6 text-center" />
                            <span>Home</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('admin.dashboard', { year: new Date().toLocaleString('en-PH', { timeZone: 'Asia/Manila', year: 'numeric' }) })" :active="route().current('admin.dashboard')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faChartSimple" class="w-6 text-center" />
                            <span>Dashboard</span>
                        </div>
                    </NavLink>
                    <NavLink v-if="$page.props.auth.user.access_level === 'admin'" :href="route('admin.users.index')" :active="route().current('admin.users.index')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faUsers" class="w-6 text-center" />
                            <span>Users</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('admin.products.index')" :active="route().current('admin.products.index')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faSuitcaseMedical" class="w-6 text-center" />
                            <span>Products</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('admin.quotations.index')" :active="route().current('admin.quotations.index')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faFileInvoice" class="w-6 text-center" />
                            <span>Quotations</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('admin.purchase_orders.index')" :active="route().current('admin.purchase_orders.index')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faTruckFast" class="w-6 text-center" />
                            <span>Purchase Orders</span>
                        </div>
                    </NavLink>
                </div>

                <div class="flex flex-col space-y-1">
                    <span class="px-6 text-sm text-primary font-medium">{{ $page.props.auth.user.name }}</span>
                    <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faUserPen" class="w-6 text-center" />
                            <span>Account Information</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('profile.change_password')" :active="route().current('profile.change_password')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faUserLock" class="w-6 text-center" />
                            <span>Change Password</span>
                        </div>
                    </NavLink>
                    <NavLink :href="route('profile.activity_log')" :active="route().current('profile.activity_log')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faPersonRunning" class="w-6 text-center" />
                            <span>Activity Log</span>
                        </div>
                    </NavLink>
                    <button type="button" @click.prevent="confirmLogout" class="text-sm text-neutral-800 dark:text-white hover:text-primary dark:hover:text-primary font-medium hover:bg-primary-100 px-6 py-3 md:rounded transition duration-150 ease-in-out">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faDoorOpen" class="w-6 text-center" />
                            <span>Logout</span>
                        </div>
                    </button>
                </div>
            </div>
        </nav>

        <main class="w-full flex-grow md:p-12 bg-white dark:bg-neutral-800 overflow-y-auto md:transition md:ease-in-out">
            <slot />
        </main>
    </div>

    <Modal :show="showModal">
        <Logout @close="closeModal()" />
    </Modal>
</template>
