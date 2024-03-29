<script setup>
import { ref, computed } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, usePage } from '@inertiajs/vue3';
import NavLink from "@/Components/Nav/NavLink.vue";
import DarkModeToggle from "@/Components/DarkModeToggle.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faBars,
    faSuitcaseMedical,
    faArrowRightToBracket,
    faXmark,
    faHome,
    faChartSimple,
    faArrowRight
} from "@fortawesome/free-solid-svg-icons";
import { faClipboard } from "@fortawesome/free-regular-svg-icons";
import HorizontalNavLink from "@/Components/Nav/HorizontalNavLink.vue";

const isLoggedIn = computed(() => Boolean(usePage().props.auth?.user));

const showMenu = ref(false);

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};
</script>

<template>
    <div class="w-screen h-screen flex flex-col overflow-y-auto">
        <nav class="h-16 md:h-auto relative z-50 md:z-0 dark:bg-gray-950 md:transition md:ease-in-out ">
            <div class="absolute md:relative w-screen md:max-w-7xl h-full md:h-auto bg-white dark:bg-gray-950 md:mx-auto flex items-center md:space-x-24 px-3 md:px-0 md:py-12 z-10 md:z-0 transition ease-in-out ">
                <div class="flex-grow">
                    <Link :href="route('home')">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-300"
                        />
                    </Link>
                </div>

                <div class="flex items-center space-x-6">
                    <HorizontalNavLink :href="route('home')" :active="route().current('home')" class="hidden md:inline">
                        Home
                    </HorizontalNavLink>

                    <HorizontalNavLink :href="route('products.index')" :active="route().current('products.index')" class="hidden md:inline">
                        Products
                    </HorizontalNavLink>

                    <button class="group" title="Quotation">
                        <div class="relative">
                            <span class="absolute -top-2 -right-3 text-xs text-white px-1.5 py-0.5 bg-red-500 rounded-full">0</span>
                            <FontAwesomeIcon :icon="faClipboard" class="relative text-gray-600 dark:text-gray-300 group-hover:text-primary transition ease-in-out" />
                        </div>
                    </button>
                </div>

                <div class="flex items-center space-x-6">
                    <DarkModeToggle class="hidden md:inline" />

                    <Link :href="route(isLoggedIn ? 'admin.dashboard' : 'login')" class="hidden md:inline text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:text-white hover:bg-primary dark:hover:text-white dark:hover:bg-primary px-3 py-1 rounded shadow-sm transition ease-in-out">
                        <div class="flex items-center">
                            <span class="text-sm">{{ isLoggedIn ? 'Dashboard' : 'Login' }}</span>
                            <FontAwesomeIcon :icon="faArrowRight" class="w-6 text-center text-xs" />
                        </div>
                    </Link>

                    <button @click="toggleMenu" class="md:hidden">
                        <FontAwesomeIcon :icon="showMenu ? faXmark : faBars" class="w-4 h-4 dark:text-gray-300" />
                    </button>
                </div>
            </div>

            <div :class="showMenu ? 'translate-y-16 h-screen' : ''" class="md:hidden w-full flex flex-col space-y-3 md:px-12 py-3 bg-white dark:bg-gray-950 overflow-hidden transform -translate-y-full md:transform-none transition ease-in-out ">
                <div class="flex flex-col space-y-1">
                    <div class="mx-auto">
                        <DarkModeToggle />
                    </div>

                    <NavLink :href="route('home')" :active="route().current('home')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faHome" class="w-6 text-center" />
                            <span>Home</span>
                        </div>
                    </NavLink>

                    <NavLink :href="route('products.index')" :active="route().current('products.index')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faSuitcaseMedical" class="w-6 text-center" />
                            <span>Products</span>
                        </div>
                    </NavLink>

                    <NavLink v-if="$page.props.auth?.user" :href="route('login')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faChartSimple" class="w-6 text-center" />
                            <span>Dashboard</span>
                        </div>
                    </NavLink>

                    <NavLink v-else :href="route('login')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faArrowRightToBracket" class="w-6 text-center" />
                            <span>Login</span>
                        </div>
                    </NavLink>
                </div>
            </div>
        </nav>

        <main class="w-full flex-grow bg-white dark:bg-gray-950 transition ease-in-out ">
            <slot />
        </main>
    </div>
</template>
