<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DataTable from '@/Components/TransactionTable/DataTable.vue';
import { columns, Transaction } from '@/Components/TransactionTable/columns';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Pagination } from '@/types/pagination';
import { Category } from '@/Components/CategoryTable/columns';
import { Button } from '@/Components/ui/button';

defineProps<{
	filter: any;
	categories: Category[];
	transactions: Pagination<Transaction>;
}>();
</script>

<template>
	<Head title="Dashboard" />
	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col p-4 lg:p-6 h-full gap-4 lg:gap-6">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Movimentações</h2>
					<!-- <p class="text-muted-foreground">
						Here&apos;s a list of your tasks for this month!
					</p> -->
				</div>

				<div
					v-if="transactions.data.length > 0"
					class="flex items-center space-x-2"
				>
					<Button>Adicionar movimentação</Button>
				</div>
			</div>

			<div
				v-if="transactions.data.length === 0"
				class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
			>
				<div class="flex flex-col items-center gap-1 text-center">
					<h3 class="text-2xl font-bold tracking-tight">
						Você ainda realizou uma movimentação.
					</h3>
					<p class="text-sm text-muted-foreground">
						Você pode começar a acompanhar sua saúde financeira registrando suas
						movimentações.
						<!-- Adiciona seu primeiro orçamento para começar sua evolução financeira. -->
					</p>

					<Button class="mt-4"> Adicionar movimentação </Button>
				</div>
			</div>

			<DataTable
				v-else
				:data="transactions"
				:columns="columns"
				:filter="filter"
				:categories="categories"
			/>
		</div>
	</AuthenticatedLayout>
</template>
