<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button'
import { ref } from 'vue'
import CreateDialog from './CreateDialog.vue'
import DataTable from '@/Components/CategoryTable/DataTable.vue'
import { Category } from '@/Components/CategoryTable/columns';

defineProps<{
    categories: Category[],
}>()

const createAccountDialogOpen = ref(false)
</script>

<template>
    <Head title="Contas" />

    <!-- <CreateDialog :banks="banks" :open="createAccountDialogOpen" @close="createAccountDialogOpen = false"/> -->

    <AuthenticatedLayout>
        <div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 h-full">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight">
                        Contas
                    </h2>
                    <!-- <p class="text-muted-foreground">
                        Todas as suas categoria ficam aqui.
                    </p> -->
                </div>
                <div class="flex items-center space-x-2">
                    <Button @click="createAccountDialogOpen = true">Adicionar categoria</Button>
                </div>
            </div>

            <div v-if="categories.length === 0" class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm">
                <div class="flex flex-col items-center gap-1 text-center">
                    <h3 class="text-2xl font-bold tracking-tight">
                        Você ainda não adicionou uma categoria.
                    </h3>
                    <p class="text-sm text-muted-foreground">
                        Você pode começar a acompanhar sua evolução financeira após adicionar sua primeira categoria.
                        <!-- Adiciona sua primeira categoria para começar sua evolução financeira. -->
                    </p>

                    <Button @click="createAccountDialogOpen = true" class="mt-4">
                        Adicionar categoria
                    </Button>
                </div>
            </div>

            <div v-else>
                <DataTable :data="categories" />
            </div>

        </div>
    </AuthenticatedLayout>
</template>
