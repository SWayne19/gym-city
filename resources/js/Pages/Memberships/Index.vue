<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { Head, useForm, router } from '@inertiajs/vue3';

// Accept properties from MembershipController
const props = defineProps({
    memberships: Array,
    users: Array,
    packages: Array,
    filters: Object
});

// Reactive dynamic search and filter variables
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

// High-performance server-side filtering with debounce
const filteredData = debounce(() => {
    router.get(route('memberships.index'), {
        search: search.value,
        status: status.value
    }, {
        preserveState: true,
        replace: true
    });
}, 300);

watch(search, () => { filteredData() });
watch(status, () => { filteredData() });

// Form Helper for issuing new plan
const form = useForm({
    user_id: '',
    package_id: ''
});

// Submit form to store a pending membership
const submitForm = () => {
    form.post(route('memberships.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

// Quick update trigger for changing status (active, pending, expired)
const updateStatus = (membershipId, newStatus) => {
    router.put(route('memberships.update', membershipId), {
        status: newStatus
    }, {
        preserveScroll: true
    });
};

// Permanent removal block
const deleteMembership = (id) => {
    if (confirm('Are you sure you want to cancel and permanently remove this membership subscription?')) {
        router.delete(route('memberships.destroy', id), {
            preserveScroll: true
        });
    }
};

// Consistent badge design colors matching your system guidelines
const getStatusBadge = (status) => {
    if (status === 'active') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (status === 'expired') return 'bg-rose-50 text-rose-700 border-rose-200';
    return 'bg-amber-50 text-amber-700 border-amber-200'; // Default: pending
};
</script>

<template>
    <Head title="Admin - Manage Memberships" />
    <AuthenticatedLayout>
        
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" v-model="search" placeholder="Search subscriptions by member name or phone..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500" />
            </div>

            <div class="w-full sm:w-56">
                <select v-model="status" class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                    <option value="">All Subscription Status</option>
                    <option value="pending">Pending Plan</option>
                    <option value="active">Active Plan</option>
                    <option value="expired">Expired Plan</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Issue New Membership</h3>
                    <p class="text-xs text-slate-400 mt-1">Assign an operational pricing package tier to a gym member.</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Select Member</label>
                        <select v-model="form.user_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                            <option value="">-- Choose Gym Member --</option>
                            <option v-for="user in users" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.phone }})
                            </option>
                        </select>
                        <span v-if="form.errors.user_id" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.user_id }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Select Package</label>
                        <select v-model="form.package_id" class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                            <option value="">-- Choose Pricing Plan --</option>
                            <option v-for="pkg in packages" :key="pkg.id" :value="pkg.id">
                                {{ pkg.name }} - {{ Number(pkg.price).toLocaleString() }} MMK ({{ pkg.duration_days }} Days)
                            </option>
                        </select>
                        <span v-if="form.errors.package_id" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.package_id }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-2.5 bg-slate-900 text-amber-400 font-bold text-sm rounded-xl hover:bg-slate-800 transition shadow-sm">
                            Generate Subscription
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Subscription History Logs</h3>
                    <p class="text-xs text-slate-400 mt-1">Total filtered records: {{ memberships.length }} plans assigned</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="pb-3">Member Details</th>
                                <th class="pb-3">Assigned Plan</th>
                                <th class="pb-3">Timeline Validity</th>
                                <th class="pb-3 text-right">Actions Matrix</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="ms in memberships" :key="ms.id" class="text-sm text-slate-600 hover:bg-slate-50/50 transition">
                                <td class="py-4">
                                    <div class="font-bold text-slate-900">{{ ms.user?.name || 'Unknown' }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ ms.user?.phone || 'N/A' }}</div>
                                </td>
                                <td class="py-4 text-xs">
                                    <div class="font-bold text-slate-800">{{ ms.package?.name || 'N/A' }}</div>
                                    <div class="text-amber-600 font-bold mt-0.5">{{ Number(ms.package?.price).toLocaleString() }} MMK</div>
                                </td>
                                <td class="py-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-0.5 text-[10px] uppercase font-bold rounded-full border" :class="getStatusBadge(ms.status)">
                                            {{ ms.status }}
                                        </span>
                                    </div>
                                    <div v-if="ms.start_date && ms.end_date" class="text-[11px] text-slate-400 mt-1 font-medium">
                                        {{ ms.start_date }} ~ {{ ms.end_date }}
                                    </div>
                                    <div v-else class="text-[11px] text-amber-500 italic mt-1">
                                        Awaiting activation payment
                                    </div>
                                </td>
                                <td class="py-4 text-right space-y-1 sm:space-y-0 sm:space-x-1">
                                    <button v-if="ms.status === 'pending'" @click="updateStatus(ms.id, 'active')"
                                        class="text-xs font-bold px-2.5 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl hover:bg-emerald-100 transition">
                                        Approve Plan
                                    </button>
                                    <button v-if="ms.status === 'active'" @click="updateStatus(ms.id, 'expired')"
                                        class="text-xs font-bold px-2.5 py-1.5 bg-slate-100 text-slate-600 border border-slate-200 rounded-xl hover:bg-slate-200 transition">
                                        Expire
                                    </button>
                                    <button @click="deleteMembership(ms.id)"
                                        class="text-xs font-bold px-2.5 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-100 transition">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="memberships.length === 0">
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    No membership log data matches the selected filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>