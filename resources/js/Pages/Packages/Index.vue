<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { Head, useForm, router } from '@inertiajs/vue3';

// accept from backend (Consistent with Members Page design)
const props = defineProps({
    packages: Array,
    filters: Object
});

// reactive variables
const search = ref(props.filters.search || '');
const duration = ref(props.filters.duration || '');

// filter logic with debounce 
const filteredData = debounce(() => {
    router.get(route('packages.index'), {
        search: search.value,
        duration: duration.value
    }, {
        preserveState: true,
        replace: true
    });
}, 300);

watch(
    search,
    () => {
        filteredData()
    }
);

watch(
    duration,
    () => {
        filteredData()
    }
);

// form helper 
const form = useForm({
    id: null,
    name: '',
    price: '',
    duration_days: '',
    description: ''
});

const isEditing = ref(false);

// submit form
const submitForm = () => {
    if (isEditing.value) {
        form.put(route('packages.update', form.id), {
            onSuccess: () => resetForm()
        });
    } else {
        form.post(route('packages.store'), {
            onSuccess: () => resetForm()
        });
    }
};

// Add edit package data into form
const editPackage = (pkg) => {
    isEditing.value = true;
    form.id = pkg.id;
    form.name = pkg.name;
    form.price = Math.round(pkg.price); // Clean decimal representations if any
    form.duration_days = pkg.duration_days;
    form.description = pkg.description || '';
};

// delete form
const deletePackage = (id) => {
    if (confirm('Are you sure you want to remove this package permanently from the system?')) {
        form.delete(route('packages.destroy', id));
    }
};

// reset
const resetForm = () => {
    isEditing.value = false;
    form.reset();
};

// duration tier badges (Matches status badge design behavior)
const getDurationBadge = (days) => {
    if (days <= 30) return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (days <= 180) return 'bg-amber-50 text-amber-700 border-amber-200';
    return 'bg-purple-50 text-purple-700 border-purple-200';
};
</script>

<template>
    <Head title="Admin - Manage Packages" />
    <AuthenticatedLayout>
        
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" v-model="search" placeholder="Quick Search by package name or details..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500" />
            </div>

            <div class="w-full sm:w-56">
                <select v-model="duration"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                    <option value="">All Plan Durations</option>
                    <option value="short">Short Term (≤ 30 Days)</option>
                    <option value="medium">Medium Term (31 - 180 Days)</option>
                    <option value="long">Long Term (> 180 Days)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ isEditing ? 'Edit Package Rules' : 'Add New Gym Package' }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ isEditing ? 'Modify pricing tier options below.' : 'Create operational plans and baseline rules.' }}
                    </p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Package Name</label>
                        <input type="text" v-model="form.name"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="e.g. 3 Months Normal Plan" />

                        <span v-if="form.errors.name" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Price (MMK)</label>
                        <input type="number" v-model="form.price"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="50000" />

                        <span v-if="form.errors.price" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.price }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Duration (Days)</label>
                        <input type="number" v-model="form.duration_days"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="90" />

                        <span v-if="form.errors.duration_days" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.duration_days }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Description</label>
                        <textarea v-model="form.description" rows="3"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="Optional features or details..."></textarea>
                    </div>

                    <div class="flex space-x-2 pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="flex-1 py-2.5 bg-slate-900 text-amber-400 font-bold text-sm rounded-xl hover:bg-slate-800 transition shadow-sm">
                            {{ isEditing ? 'Update Package' : 'Create Plan' }}
                        </button>
                        <button v-if="isEditing" type="button" @click="resetForm"
                            class="px-4 py-2.5 bg-slate-100 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-200 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Active Pricing Tiers</h3>
                    <p class="text-xs text-slate-400 mt-1">Total filtered records: {{ packages.length }} plans</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="pb-3">Plan Info</th>
                                <th class="pb-3">Duration Tiers</th>
                                <th class="pb-3">Investment Price</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="pkg in packages" :key="pkg.id"
                                class="text-sm text-slate-600 hover:bg-slate-50/50 transition">
                                <td class="py-4">
                                    <div class="font-bold text-slate-900">{{ pkg.name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 max-w-xs truncate">
                                        {{ pkg.description || 'No description provided.' }}
                                    </div>
                                </td>
                                <td class="py-4 text-xs font-semibold text-slate-700">
                                    <div class="flex items-center space-x-2">
                                        <span>{{ pkg.duration_days }} Days</span>
                                        <span class="px-2.5 py-0.5 text-[10px] uppercase font-bold rounded-full border"
                                            :class="getDurationBadge(pkg.duration_days)">
                                            {{ Math.round(pkg.duration_days / 30) }} Mos
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 font-bold text-slate-900">
                                    {{ Number(pkg.price).toLocaleString() }} <span class="text-xs text-slate-400 font-medium">MMK</span>
                                </td>
                                <td class="py-4 text-right space-x-1">
                                    <button @click="editPackage(pkg)"
                                        class="text-xs font-bold px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 transition">
                                        Edit
                                    </button>
                                    <button @click="deletePackage(pkg.id)"
                                        class="text-xs font-bold px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-100 transition">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="packages.length === 0">
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    No operational packages found matching the selected filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>