<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                <div class="text-sm font-medium text-gray-500 uppercase">Active Members</div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">124</div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                <div class="text-sm font-medium text-gray-500 uppercase">Expiring This Week</div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">8</div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                <div class="text-sm font-medium text-gray-500 uppercase">Total Packages</div>
                <div class="mt-1 text-3xl font-semibold text-gray-900">5</div>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Gym Activity Status</h2>
            <p class="text-gray-600">Your Gym Membership Tracking system is successfully set up with Vue SPA!</p>
        </div>
    </AuthenticatedLayout>
</template>