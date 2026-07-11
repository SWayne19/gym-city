<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// Backend မှ ပို့လိုက်သော Dynamic Props များကို လက်ခံခြင်း
defineProps({
    stats: Object,
    recentPayments: Array
});
</script>

<template>
    <Head title="Dashboard Overview" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-amber-200 transition-all duration-300">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Members</div>
                    <div class="mt-2 text-3xl font-black text-slate-900 tracking-tight">
                        {{ stats.active_members.toLocaleString() }}
                    </div>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-amber-200 transition-all duration-300">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Expiring This Week</div>
                    <div class="mt-2 text-3xl font-black text-slate-900 tracking-tight">
                        {{ stats.expiring_this_week.toLocaleString() }}
                    </div>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-amber-200 transition-all duration-300">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Packages</div>
                    <div class="mt-2 text-3xl font-black text-slate-900 tracking-tight">
                        {{ stats.total_packages.toLocaleString() }}
                    </div>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between group hover:border-amber-200 transition-all duration-300">
                <div>
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Revenue</div>
                    <div class="mt-2 text-xl font-black text-slate-900 tracking-tight sm:text-2xl">
                        {{ Number(stats.total_revenue).toLocaleString() }} <span class="text-xs font-normal text-slate-400">MMK</span>
                    </div>
                </div>
                <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:bg-rose-500 group-hover:text-white transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
            
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Recent Revenue Streams</h3>
                    <p class="text-xs text-slate-400 mt-1">Real-time audit trail of the last 5 settled gym cash invoices.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="pb-3">Member</th>
                                <th class="pb-3">Package / Method</th>
                                <th class="pb-3 text-right">Amount Received</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="payment in recentPayments" :key="payment.id" class="text-sm text-slate-600">
                                <td class="py-3.5 font-semibold text-slate-900">
                                    {{ payment.membership?.user?.name || 'Purged Member' }}
                                </td>
                                <td class="py-3.5 text-xs text-slate-500">
                                    <span class="font-bold text-slate-700 block">
                                        {{ payment.membership?.package?.name || 'N/A' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 mt-0.5 block">Via {{ payment.payment_method }}</span>
                                </td>
                                <td class="py-3.5 text-right font-black text-slate-800">
                                    {{ Number(payment.amount).toLocaleString() }} MMK
                                </td>
                            </tr>
                            <tr v-if="recentPayments.length === 0">
                                <td colspan="3" class="text-center py-6 text-slate-400 text-xs italic">
                                    No dynamic payment statements captured yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-900 to-slate-950 p-6 rounded-2xl shadow-sm text-slate-200 flex flex-col justify-between">
                <div>
                    <div class="h-10 w-10 bg-amber-500/10 text-amber-400 rounded-xl flex items-center justify-center border border-amber-500/20 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">System Framework Secured</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mt-2">
                        Your **Gym City Tracking Core Layout** is successfully optimized under the high-performance VILT architecture stack. Relational data cascading and real-time state synchronizations are live.
                    </p>
                </div>
                
                <div class="pt-6 border-t border-slate-800/60 flex items-center justify-between text-[11px] text-slate-500">
                    <span>Engine Engine V3 SPA</span>
                    <span class="flex items-center space-x-1.5 font-bold text-emerald-400">
                        <span class="h-2 w-2 bg-emerald-400 rounded-full animate-ping"></span>
                        <span>Operational</span>
                    </span>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>