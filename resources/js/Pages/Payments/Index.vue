<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { Head, useForm, router } from '@inertiajs/vue3';

// Accept properties from PaymentController
const props = defineProps({
    payments: Array,
    pendingMemberships: Array,
    filters: Object
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');

// Server-side database filtering with debounce 
const filteredData = debounce(() => {
    router.get(route('payments.index'), {
        search: search.value,
        status: status.value
    }, {
        preserveState: true,
        replace: true
    });
}, 300);

watch(search, () => { filteredData() });
watch(status, () => { filteredData() });

// Form structure setup
const form = useForm({
    membership_id: '',
    amount: '',
    payment_method: 'Cash'
});

// UX Helper: Dropdown ရွေးလိုက်သည်နှင့် စနစ်မှ သက်ဆိုင်ရာ Package ကြေးကို Amount ထဲ အလိုအလျောက် ဖြည့်ပေးရန်
const handleInvoiceSelection = (e) => {
    const selectedId = e.target.value;
    const matchedMembership = props.pendingMemberships.find(m => m.id == selectedId);
    if (matchedMembership && matchedMembership.package) {
        form.amount = Math.round(matchedMembership.package.price);
    } else {
        form.amount = '';
    }
};

const submitForm = () => {
    form.post(route('payments.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const removePayment = (id) => {
    if (confirm('Are you sure you want to delete this payment statement invoice permanently?')) {
        router.delete(route('payments.destroy', id), {
            preserveScroll: true
        });
    }
};

const getStatusBadge = (status) => {
    if (status === 'paid') return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    if (status === 'failed') return 'bg-rose-50 text-rose-700 border-rose-200';
    return 'bg-amber-50 text-amber-700 border-amber-200';
};
</script>

<template>

    <Head title="Admin - Collect Payments" />
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
                <input type="text" v-model="search" placeholder="Quick Search statement invoices by member name..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500" />
            </div>

            <div class="w-full sm:w-56">
                <select v-model="status"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                    <option value="">All Payment Status</option>
                    <option value="paid">Paid Statements</option>
                    <option value="pending">Pending Logs</option>
                    <option value="failed">Failed Logs</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-fit">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Record New Payment</h3>
                    <p class="text-xs text-slate-400 mt-1">Process pending membership subscription invoices into paid
                        assets.</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Pending Membership
                            Invoice</label>
                        <select v-model="form.membership_id" @change="handleInvoiceSelection"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                            <option value="">-- Choose Pending Invoice --</option>
                            <option v-for="ms in pendingMemberships" :key="ms.id" :value="ms.id">
                                {{ ms.user?.name }} — {{ ms.package?.name }}
                            </option>
                        </select>
                        <span v-if="form.errors.membership_id" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.membership_id }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Collection Amount
                            (MMK)</label>
                        <input type="number" v-model="form.amount"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500"
                            placeholder="0" />
                        <span v-if="form.errors.amount" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.amount }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1">Payment Channel</label>
                        <select v-model="form.payment_method"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-amber-500 focus:border-amber-500 py-2.5">
                            <option value="Cash">Cash Ledger</option>
                            <option value="KPay">KBZPay Transfer</option>
                            <option value="WaveMoney">WaveMoney Transfer</option>
                            <option value="CBPay">CBPay Transfer</option>
                        </select>
                        <span v-if="form.errors.payment_method" class="text-xs text-rose-600 font-medium mt-1 block">
                            {{ form.errors.payment_method }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-2.5 bg-slate-900 text-amber-400 font-bold text-sm rounded-xl hover:bg-slate-800 transition shadow-sm">
                            {{ form.processing ? 'Processing Asset...' : 'Collect Asset Payment' }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Financial Revenue Statements</h3>
                    <p class="text-xs text-slate-400 mt-1">Total operational records: {{ payments.length }} transactions
                        capture</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-bold uppercase text-slate-400">
                                <th class="pb-3">Payer Member</th>
                                <th class="pb-3">Subscription Package</th>
                                <th class="pb-3">Investment Settled</th>
                                <th class="pb-3 text-right">Actions Matrix</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="pay in payments" :key="pay.id"
                                class="text-sm text-slate-600 hover:bg-slate-50/50 transition">
                                <td class="py-4">
                                    <div class="font-bold text-slate-900">{{ pay.membership?.user?.name || 'Purged Account' }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5" v-if="pay.paid_at">
                                        Timestamp: {{ new Date(pay.paid_at).toLocaleString() }}
                                    </div>
                                </td>
                                <td class="py-4 text-xs">
                                    <div class="font-bold text-slate-800">{{ pay.membership?.package?.name || 'N/A' }}
                                    </div>
                                    <div class="text-slate-400 mt-0.5 text-[11px]">Method: {{ pay.payment_method }}
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="font-black text-slate-900">{{ Number(pay.amount).toLocaleString() }} MMK
                                    </div>
                                    <span
                                        class="px-2.5 py-0.5 text-[9px] uppercase font-bold rounded-full border inline-block mt-1"
                                        :class="getStatusBadge(pay.status)">
                                        {{ pay.status }}
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <button @click="removePayment(pay.id)"
                                        class="text-xs font-bold px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-100 transition">
                                        Delete Record
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="payments.length === 0">
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    No transaction audit trails found matching the criteria.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
