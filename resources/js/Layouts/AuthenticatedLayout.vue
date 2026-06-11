<script setup>
import { ref } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';

const isLogoutModalOpen = ref(false);
const page = usePage();

const openLogoutModal = () => { isLogoutModalOpen.value = true; };
const closeLogoutModal = () => { isLogoutModalOpen.value = false; };

const confirmLogout = () => {
    router.post(route('logout'), {}, {
        onSuccess: () => closeLogoutModal()
    });
};

const isRouteActive = (routeName) => {
    return route().current(routeName);
};
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-4 text-xl font-bold border-b border-slate-800 text-center text-yellow-400">
                GYM CITY
            </div>

            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto mt-4">
                <span class="px-3 text-[10px] font-bold tracking-wider text-slate-500 uppercase block mb-2">
                    Main Menu
                </span>

                <Link :href="route('dashboard')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group"
                    :class="isRouteActive('dashboard')
                        ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-400 shadow-inner'
                        : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <Link :href="route('users.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group"
                    :class="isRouteActive('users.*')
                        ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-400 shadow-inner'
                        : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Members</span>
                </Link>

                <Link :href="route('packages.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group"
                    :class="isRouteActive('packages.*')
                        ? 'bg-slate-800 text-amber-400 font-semibold border-l-4 border-amber-400 shadow-inner'
                        : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-100'">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Packages</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-slate-800">
                <button @click="openLogoutModal"
                    class="w-full text-left p-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-medium transition">
                    Logout
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow-sm p-4 flex justify-between items-center px-8">
                <h1 class="text-xl font-semibold text-gray-800">
                    Welcome, {{ page.props.auth.user.name }}
                </h1>
            </header>
            <div class="p-8">
                <slot />
            </div>
        </main>

        <div v-if="isLogoutModalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl transform transition-all">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Logout</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to log out of Gym City System?</p>

                <div class="flex justify-end space-x-3">
                    <button @click="closeLogoutModal"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button @click="confirmLogout"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>