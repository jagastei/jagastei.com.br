<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Overview from '@/Components/Overview.vue'
import DateRangePicker from '@/Components/DateRangePicker.vue'
import RecentSales from '@/Components/RecentSales.vue'
import { Button } from '@/Components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card'
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/Components/ui/tabs'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>

    <Head title="Contas" />
    <AuthenticatedLayout>
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 h-full">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">
                        Contas
                    </h2>
                    <!-- <p class="text-muted-foreground">
                        Todas as suas contas ficam aqui.
                    </p> -->
                </div>
                <!-- <div class="flex items-center space-x-2">
                    <Button>Adicionar conta</Button>
                </div> -->
            </div>

            <div class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm">
                <div class="flex flex-col items-center gap-1 text-center">
                    <h3 class="text-2xl font-bold tracking-tight">
                        Você ainda não adicionou uma conta.
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        Você pode começar a acompanhar sua evolução financeira após adicionar sua primeira conta.
                        <!-- Adiciona sua primeira conta para começar sua evolução financeira. -->
                    </p>
                    <Button class="mt-4">
                        Adicionar conta
                    </Button>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
