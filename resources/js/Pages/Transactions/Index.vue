<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DataTable from '@/Components/TransactionTable/DataTable.vue'
import { columns, Transaction } from '@/Components/TransactionTable/columns'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps<{
    transactions: Transaction[],
}>()
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="flex-1 flex-col space-y-8 md:flex p-4">
            <div class="flex items-center justify-between space-y-2">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">
                        Welcome back!
                    </h2>
                    <p class="text-muted-foreground">
                        Here&apos;s a list of your tasks for this month!
                    </p>
                </div>
            </div>
            <DataTable :data="transactions" :columns="columns" />
        </div>
    </AuthenticatedLayout>
</template>