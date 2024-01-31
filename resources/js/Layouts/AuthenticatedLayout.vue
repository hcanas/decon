<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Nav/Dropdown.vue';
import DropdownLink from '@/Components/Nav/DropdownLink.vue';
import NavLink from '@/Components/Nav/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import {
    PresentationChartLineIcon,
    UserGroupIcon,
    UserCircleIcon,
    LockClosedIcon
} from '@heroicons/vue/24/outline';
</script>

<template>
    <div>
        <div class="min-h-screen bg-white">
            <nav>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16 sm:h-32">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                </Link>
                            </div>
                        </div>

                        <div class="flex items-center space-x-6">
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('users.index')" :active="route().current('users.index')">
                                    Users
                                </NavLink>
                                <NavLink :href="route('products.index')" :active="route().current('products.index')">
                                    Products
                                </NavLink>
                            </div>

                            <Dropdown>
                                <DropdownLink :href="route('dashboard')" :active="route().current('dashboard')" :class="'sm:hidden'">
                                    <div class="flex items-center space-x-2">
                                        <PresentationChartLineIcon class="w-4 h-4" />
                                        <span>Dashboard</span>
                                    </div>
                                </DropdownLink>
                                <DropdownLink :href="route('users.index')" :active="route().current('users.index')" :class="'sm:hidden'">
                                    <div class="flex items-center space-x-2">
                                        <UserGroupIcon class="w-4 h-4" />
                                        <span>Users</span>
                                    </div>
                                </DropdownLink>
                                <DropdownLink :href="route('products.index')" :active="route().current('products.index')" :class="'sm:hidden'">
                                    <div class="flex items-center space-x-2">
                                        <UserGroupIcon class="w-4 h-4" />
                                        <span>Products</span>
                                    </div>
                                </DropdownLink>
                                <DropdownLink :href="route('profile.edit')" :active="route().current('profile.edit')">
                                    <div class="flex items-center space-x-2">
                                        <UserCircleIcon class="w-4 h-4" />
                                        <span>Profile</span>
                                    </div>
                                </DropdownLink>
                                <!-- Add confirm logout dialog -->
                                <DropdownLink :href="route('logout')" method="post">
                                    <div class="flex items-center space-x-2">
                                        <LockClosedIcon class="w-4 h-4" />
                                        <span>Logout</span>
                                    </div>
                                </DropdownLink>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
