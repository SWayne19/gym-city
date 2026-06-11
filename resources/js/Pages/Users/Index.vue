<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { Head, useForm, router } from '@inertiajs/vue3';

// accept from backend
const props = defineProps({
    users: Array,
    filters: Object
});

// reactive variables
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

// filter logic with debounce 
const filteredData = debounce(() => {
    router.get(route('users.index'), {
        search: search.value,
        status: status.value
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
    status,
    () => {
        filteredData()
    }
);

// form helper 
const form = useForm({
    id: null,
    name: '',
    email: '',
    phone: ''
});

const isEditing = ref(false);

// submit form
const submitForm = () => {
    if (isEditing.value) {
        form.put(route('users.update', form.id), {
            onSuccess: () => resetForm()
        });
    } else {
        form.post(route('users.store'), {
            onSuccess: () => resetForm()
        });
    }
};

// Add edit member data into form
const editUser = (user) => {
    isEditing.value = true;
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.phone = user.phone;
};

// delete form
const deleteUser = (id) => {
    if (confirm('Are you sure you want to remove this member permanently from the system?')) {
        form.delete(route('users.destroy', id));
    }
};

// reset
const resetForm = () => {
    isEditing.value = false;
    form.reset();
};

// status colors
const getStatusBadge = (status) => {
    if (status === 'active') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (status === 'expired') return 'bg-rose-50 text-rose-700 border-rose-200';
    return 'bg-amber-50 text-amber-700 border-amber-200';
};

</script>
<template>

    <Head title="Admin - Manage Members" />
    <AuthenticatedLayout>
        <div
            class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" v-model="search" placeholder="Quick Search by member name, email or phone..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500" />
            </div>

            <div class="w-full sm:w-56">
                <select v-model="status"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                    <option value="">All Membership Status</option>
                    <option value="active">Active Plan</option>
                    <option value="expired">Expired Plan</option>
                    <option value="pending">Pending Payment</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ isEditing ? 'Edit Member Profile' : 'Add New Gym Member' }}
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ isEditing ? 'Modify user information below.' : 'Create an account for new walker.' }}
                    </p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Full Name</label>
                        <input type="text" v-model="form.name"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="e.g. Kyaw Kyaw" />

                        <span v-if="form.errors.name" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Email Address</label>
                        <input type="email" v-model="form.email"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="kyaw@gmail.com" />

                        <span v-if="form.errors.email" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Phone Number</label>
                        <input type="text" v-model="form.phone"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="09xxxxxxxxx" />

                        <span v-if="form.errors.phone" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.phone }}
                        </span>
                    </div>

                    <div class="flex space-x-2 pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="flex-1 py-2.5 bg-slate-900 text-amber-400 font-bold text-sm rounded-xl hover:bg-slate-800 transition shadow-sm">
                            {{ isEditing ? 'Update Profile' : 'Register Account' }}
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
                    <h3 class="text-base font-bold text-slate-900">Members Directory</h3>
                    <p class="text-xs text-slate-400 mt-1">Total filtered records: {{ users.length }} members</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="pb-3">Member Details</th>
                                <th class="pb-3">Contact</th>
                                <th class="pb-3">Plan Status</th>
                                <th class="pb-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="user in users" :key="user.id"
                                class="text-sm text-slate-600 hover:bg-slate-50/50 transition">
                                <td class="py-4">
                                    <div class="font-bold text-slate-900">{{ user.name }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Joined: {{ new
                                        Date(user.created_at).toLocaleDateString() }}</div>
                                </td>
                                <td class="py-4 text-xs">
                                    <div class="font-medium text-slate-700">{{ user.email }}</div>
                                    <div class="text-slate-400 mt-0.5">{{ user.phone }}</div>
                                </td>
                                <td class="py-4">
                                    <div v-if="user.memberships && user.memberships.length > 0"
                                        class="flex items-center space-x-2">
                                        <span class="font-semibold text-slate-800">{{ user.memberships[0].package.name
                                        }}</span>
                                        <span class="px-2.5 py-0.5 text-[10px] uppercase font-bold rounded-full border"
                                            :class="getStatusBadge(user.memberships[0].status)">
                                            {{ user.memberships[0].status }}
                                        </span>
                                    </div>
                                    <span v-else
                                        class="text-xs text-slate-400 italic bg-slate-50 border border-slate-100 px-2 py-0.5 rounded-md">No
                                        Package</span>
                                </td>
                                <td class="py-4 text-right space-x-1">
                                    <button @click="editUser(user)"
                                        class="text-xs font-bold px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl hover:bg-amber-100 transition">
                                        Edit
                                    </button>
                                    <button @click="deleteUser(user.id)"
                                        class="text-xs font-bold px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-100 transition">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    No gym members found matching the selected filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>