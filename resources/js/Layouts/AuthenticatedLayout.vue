<script setup>
import { ref } from 'vue';
import { router, Link, usePage } from '@inertiajs/vue3';

const isLogoutModalOpen = ref(false);
const isMobileSidebarOpen = ref(false); // Mobile Drawer State
const page = usePage();

const openLogoutModal = () => { isLogoutModalOpen.value = true; };
const closeLogoutModal = () => { isLogoutModalOpen.value = false; };

const toggleMobileSidebar = () => {
    isMobileSidebarOpen.value = !isMobileSidebarOpen.value;
};

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
    <div class="flex h-screen bg-slate-50/50 antialiased font-sans overflow-hidden">

        <div v-if="isMobileSidebarOpen" @click="toggleMobileSidebar"
            class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300">
        </div>

        <aside :class="isMobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 w-64 bg-slate-950 text-slate-200 flex flex-col z-50 border-r border-slate-900 shadow-2xl lg:shadow-none transition-transform duration-300 ease-out">

            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-900">
                <div class="flex items-center space-x-3">
                    <div
                        class="h-9 w-9 bg-gradient-to-tr from-amber-500 to-yellow-400 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <span class="text-slate-950 font-black text-base tracking-wider">GC</span>
                    </div>
                    <span
                        class="text-lg font-black tracking-wider bg-gradient-to-r from-white to-slate-400 bg-clip-text text-transparent">
                        GYM CITY
                    </span>
                </div>

                <button @click="toggleMobileSidebar"
                    class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:bg-slate-900 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar">
                <span class="px-3 text-[10px] font-bold tracking-widest text-slate-500 uppercase block mb-3">
                    Core Dashboard
                </span>

                <Link :href="route('dashboard')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 group"
                    :class="isRouteActive('dashboard')
                        ? 'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-400 font-bold border-l-2 border-amber-400 shadow-sm'
                        : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200 hover:translate-x-1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-300"
                        :class="isRouteActive('dashboard') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <span class="px-3 text-[10px] font-bold tracking-widest text-slate-500 uppercase block pt-4 mb-3">
                    Management
                </span>

                <Link :href="route('users.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 group"
                    :class="isRouteActive('users.*')
                        ? 'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-400 font-bold border-l-2 border-amber-400 shadow-sm'
                        : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200 hover:translate-x-1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-300"
                        :class="isRouteActive('users.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0-6a3.99 3.99 0 00-2.035-.104 4 4 0 11-.707-3.793A3.996 3.996 0 0015 15z" />
                    </svg>
                    <span>Members Directory</span>
                </Link>

                <Link :href="route('packages.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 group"
                    :class="isRouteActive('packages.*')
                        ? 'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-400 font-bold border-l-2 border-amber-400 shadow-sm'
                        : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200 hover:translate-x-1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-300"
                        :class="isRouteActive('packages.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span>Pricing Packages</span>
                </Link>

                <Link :href="route('memberships.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 group"
                    :class="isRouteActive('memberships.*')
                        ? 'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-400 font-bold border-l-2 border-amber-400 shadow-sm'
                        : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200 hover:translate-x-1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-300"
                        :class="isRouteActive('memberships.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Subscriptions</span>
                </Link>

                <Link :href="route('payments.index')"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 group"
                    :class="isRouteActive('payments.*')
                        ? 'bg-gradient-to-r from-amber-500/10 to-transparent text-amber-400 font-bold border-l-2 border-amber-400 shadow-sm'
                        : 'text-slate-400 hover:bg-slate-900/60 hover:text-slate-200 hover:translate-x-1'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-colors duration-300"
                        :class="isRouteActive('payments.*') ? 'text-amber-400' : 'text-slate-500 group-hover:text-slate-300'"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Revenue & Payments</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-slate-900 bg-slate-950/60">
                <button @click="openLogoutModal"
                    class="w-full flex items-center justify-center space-x-2 p-3 rounded-xl bg-rose-500/10 hover:bg-rose-600 text-rose-400 hover:text-white text-sm font-semibold transition-all duration-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Secure Sign Out</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <header
                class="h-20 bg-white border-b border-slate-100 flex justify-between items-center px-4 sm:px-8 shrink-0">
                <div class="flex items-center space-x-3">
                    <button @click="toggleMobileSidebar"
                        class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="hidden sm:block">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Operational Panel</p>
                        <h2 class="text-sm font-bold text-slate-700 mt-0.5">Gym Management Workspace</h2>
                    </div>
                </div>

                <div class="flex items-center space-x-3 pl-4 border-l border-slate-100 h-8">
                    <div
                        class="h-8 w-8 bg-slate-100 rounded-full flex items-center justify-center border border-slate-200 text-slate-600 font-bold text-xs uppercase shadow-inner">
                        {{ page.props.auth.user.name.substring(0, 2) }}
                    </div>
                    <span class="text-sm font-semibold text-slate-700 hidden md:inline">
                        {{ page.props.auth.user.name }}
                    </span>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 sm:p-8 bg-slate-50/60 custom-scrollbar">
                <slot />
            </div>
        </main>

        <div v-if="isLogoutModalOpen"
            class="fixed inset-0 bg-slate-950/40 backdrop-blur-md flex items-center justify-center z-50 p-4 transition-all animate-fade-in">
            <div
                class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl border border-slate-100/80 transform scale-100 transition-transform">
                <div class="h-12 w-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-base font-bold text-slate-900 mb-1">Confirm Session Logout</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-6">Are you sure you want to end your current
                    administrative session and sign out of Gym City?</p>

                <div class="flex justify-end space-x-2.5">
                    <button @click="closeLogoutModal"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-200 transition">
                        Keep Session
                    </button>
                    <button @click="confirmLogout"
                        class="px-4 py-2.5 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-600/10">
                        Sign Out
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/* 🛠️ Global Clean Performance Scrollbar (Optional) */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(100, 116, 139, 0.2);
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(100, 116, 139, 0.4);
}
</style>