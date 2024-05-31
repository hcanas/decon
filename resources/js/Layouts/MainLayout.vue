<script setup>
import {ref, computed, onMounted} from 'vue';
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
    faArrowRight,
    faShoppingCart, faCircleQuestion, faPhone, faEnvelope
} from "@fortawesome/free-solid-svg-icons";
import HorizontalNavLink from "@/Components/Nav/HorizontalNavLink.vue";
import {useCart} from "@/Composables/cart.js";
import {faFacebook, faInstagram, faTwitter} from "@fortawesome/free-brands-svg-icons";

const cart = useCart();

const isLoggedIn = computed(() => Boolean(usePage().props.auth?.user));

const showMenu = ref(false);

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};

const cartItemCount = ref(0);

window.addEventListener('storage', event => {
    if (event.key === 'cart' && event.newValue) {
        cartItemCount.value = Array.isArray(JSON.parse(event.newValue))
            ? JSON.parse(event.newValue).length
            : 0;
    }
});

onMounted(() => {
    cartItemCount.value = cart.items.length;
});
</script>

<template>
    <div class="w-screen h-screen flex flex-col overflow-y-auto">
        <nav class="h-16 md:h-auto relative z-50 md:z-0 dark:bg-neutral-950 md:transition md:ease-in-out ">
            <div class="absolute md:relative w-screen md:max-w-7xl h-full md:h-auto bg-white dark:bg-neutral-950 md:mx-auto flex items-center md:space-x-24 px-3 md:px-0 md:py-12 z-10 md:z-0 transition ease-in-out ">
                <div class="flex-grow">
                    <Link :href="route('home')" class="flex items-center space-x-3">
                        <ApplicationLogo
                            class="block h-9 w-auto fill-current"
                        />
                        <p class="flex flex-col text-lg font-bold leading-none">
                            <span class="text-gray-500">EC PRIME</span>
                            <span>CORPORATION</span>
                        </p>
                    </Link>
                </div>

                <div class="flex items-center space-x-6">
                    <HorizontalNavLink :href="route('home')" :active="route().current('home')" class="hidden md:inline">
                        Home
                    </HorizontalNavLink>

                    <HorizontalNavLink :href="route('products.index')" :active="route().current('products.index')" class="hidden md:inline">
                        Products
                    </HorizontalNavLink>

                    <HorizontalNavLink :href="route('about_us')" :active="route().current('about_us')" class="hidden md:inline">
                        About Us
                    </HorizontalNavLink>

                    <HorizontalNavLink :href="route('contact_us')" :active="route().current('contact_us')" class="hidden md:inline">
                        Contact Us
                    </HorizontalNavLink>

                    <HorizontalNavLink :href="route('cart')" class="group">
                        <div class="relative">
                            <span class="absolute -top-2 -right-3 text-xs px-1.5 py-0.5 bg-red-500 rounded-full text-white">{{ cartItemCount }}</span>
                            <FontAwesomeIcon :icon="faShoppingCart" class="relative group-hover:text-primary transition ease-in-out" />
                        </div>
                    </HorizontalNavLink>
                </div>

                <div class="flex items-center space-x-6">
                    <DarkModeToggle class="hidden md:inline" />

                    <Link :href="isLoggedIn ? route('admin.dashboard', { year: new Date().toLocaleString('en-PH', { timeZone: 'Asia/Manila', year: 'numeric' }) }) : route('login')" class="hidden md:inline bg-neutral-100 dark:bg-neutral-800 hover:text-white hover:bg-primary dark:hover: dark:hover:bg-primary px-3 py-1 rounded shadow-sm transition ease-in-out">
                        <div class="flex items-center">
                            <span class="text-sm">{{ isLoggedIn ? 'Dashboard' : 'Login' }}</span>
                            <FontAwesomeIcon :icon="faArrowRight" class="w-6 text-center text-xs" />
                        </div>
                    </Link>

                    <button @click="toggleMenu" class="md:hidden">
                        <FontAwesomeIcon :icon="showMenu ? faXmark : faBars" class="w-4 h-4" />
                    </button>
                </div>
            </div>

            <div :class="showMenu ? 'translate-y-16 h-screen' : ''" class="md:hidden w-full flex flex-col space-y-3 md:px-12 py-3 bg-white dark:bg-neutral-950 overflow-hidden transform -translate-y-full md:transform-none transition ease-in-out ">
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

                    <NavLink :href="route('about_us')" :active="route().current('about_us')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faCircleQuestion" class="w-6 text-center" />
                            <span>About Us</span>
                        </div>
                    </NavLink>

                    <NavLink :href="route('contact_us')" :active="route().current('contact_us')">
                        <div class="flex items-center space-x-2">
                            <FontAwesomeIcon :icon="faCircleQuestion" class="w-6 text-center" />
                            <span>Contact Us</span>
                        </div>
                    </NavLink>

                    <NavLink v-if="$page.props.auth?.user" :href="route('admin.dashboard', { year: new Date().toLocaleString('en-PH', { timeZone: 'Asia/Manila', year: 'numeric' }) })">
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

        <main class="w-full flex-grow bg-white dark:bg-neutral-950 transition ease-in-out ">
            <slot />
        </main>

        <div class="flex flex-col space-y-6 bg-neutral-900 px-6 md:px-0 py-12">
            <div class="w-full md:max-w-7xl mx-auto flex items-center space-x-3">
                <ApplicationLogo class="h-9 w-fit" />
                <p class="flex flex-col text-white leading-tight">
                    <span>EC Prime</span>
                    <span>Corporation</span>
                </p>
            </div>

            <div class="w-full md:max-w-7xl mx-auto flex-shrink-0 grid gap-6 md:grid-cols-3">
                <div class="flex flex-col space-y-2">
                    <h6 class="text-white font-medium">Contact</h6>
                    <p class="flex items-center space-x-2 text-sm text-white">
                        <FontAwesomeIcon :icon="faPhone" />
                        <span>+639 3872 9999</span>
                    </p>
                    <p class="flex items-center space-x-2 text-sm text-white">
                        <FontAwesomeIcon :icon="faEnvelope" />
                        <span>ecprimecorp@gmail.com</span>
                    </p>

                    <h6 class="text-white font-medium">Follow us on</h6>

                    <div class="flex items-center space-x-3">
                        <a href="#" class="text-white hover:underline" title="Facebook">
                            <FontAwesomeIcon :icon="faFacebook" />
                        </a>
                        <a href="#" class="text-white hover:underline" title="Facebook">
                            <FontAwesomeIcon :icon="faInstagram" />
                        </a>
                        <a href="#" class="text-white hover:underline" title="Facebook">
                            <FontAwesomeIcon :icon="faTwitter" />
                        </a>
                    </div>
                </div>

                <div class="flex flex-col space-y-2">
                    <h6 class="text-white font-medium">Departments</h6>
                    <p class="text-sm text-white">Sales Team</p>
                    <p class="text-sm text-white">Procurement Team</p>
                    <p class="text-sm text-white">Legal Team</p>
                    <p class="text-sm text-white">Logistics Team</p>
                </div>

                <div class="flex flex-col space-y-2">
                    <h6 class="text-white font-medium">Operating Hours</h6>
                    <p class="text-sm text-white grid grid-cols-2">
                        <span>Monday - Friday</span>
                        <span>9am - 5pm</span>
                    </p>
                    <p class="text-sm text-white grid grid-cols-2">
                        <span>Saturday</span>
                        <span>10am - 5pm</span>
                    </p>
                    <p class="text-sm text-white grid grid-cols-2">
                        <span>Sunday</span>
                        <span>Closed</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
