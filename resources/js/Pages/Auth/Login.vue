<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Gym City - Log in" />

        <!-- 🏢 Clean Header Segment -->
        <div class="flex flex-col items-center justify-center mb-10 text-center">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">GYM CITY</h2>
            <p class="text-[11px] font-black text-amber-500 uppercase tracking-[0.2em] mt-1.5">Management Core</p>
            <p class="text-xs text-slate-400 mt-2">Sign in to access your administrative asset matrix.</p>
        </div>

        <div v-if="status" class="mb-5 text-sm font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
            {{ status }}
        </div>

        <!-- 🔐 Ultra-Clean Form Wrapper -->
        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Corporate Email" class="text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-1.5" />

                <TextInput
                    id="email"
                    type="email"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-1 focus:ring-amber-500 focus:border-amber-500 py-3 px-4 shadow-sm bg-slate-50/50"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="admin@gymcity.com"
                    autocomplete="username"
                />

                <InputError class="mt-2 text-xs font-semibold text-rose-600" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Security Password" class="text-[10px] font-bold uppercase text-slate-400 tracking-wider mb-1.5" />

                <TextInput
                    id="password"
                    type="password"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-1 focus:ring-amber-500 focus:border-amber-500 py-3 px-4 shadow-sm bg-slate-50/50"
                    v-model="form.password"
                    required
                    placeholder="••••••••"
                    autocomplete="current-password"
                />

                <InputError class="mt-2 text-xs font-semibold text-rose-600" :message="form.errors.password" />
            </div>

            <!-- 🚀 Submit Action -->
            <div class="pt-2">
                <PrimaryButton
                    class="w-full py-3.5 bg-slate-900 text-amber-400 font-bold text-xs rounded-xl hover:bg-slate-800 transition justify-center shadow-md active:bg-slate-950 tracking-widest uppercase"
                    :class="{ 'opacity-50 pointer-events-none': form.processing }"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Verifying Credentials...' : 'Secure Authorization' }}
                </PrimaryButton>
            </div>
        </form>

        <!-- 🏁 Footer Status Tag -->
        <div class="mt-10 pt-4 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-medium">
            <span>Gym City SPA Node</span>
            <span class="flex items-center space-x-1 font-bold text-emerald-500">
                <span class="h-1 w-1 bg-emerald-500 rounded-full animate-pulse"></span>
                <span>Protected</span>
            </span>
        </div>
    </GuestLayout>
</template>