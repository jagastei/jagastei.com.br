<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import DataTable from '@/Components/TransactionOutTable/DataTable.vue';
import { columns, Transaction } from '@/Components/TransactionOutTable/columns';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Pagination } from '@/types/pagination';
import { Category } from '@/Components/CategoryTable/columns';
import { Button } from '@/Components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { CloudUploadIcon, Loader2 } from 'lucide-vue-next';
import UploadFile from '@/Components/UploadFile.vue';
import { ref } from 'vue';
import CreateDialog from './CreateDialog.vue';
import { Account } from '@/Components/AccountTable/columns';
import { usePostHog } from '@/composables/usePosthog';
import ImportDialog from './ImportDialog.vue';

const props = defineProps<{
	sort?: string;
	filter?: object;
	categories: Category[];
	accounts: Account[];
	transactions: Pagination<Transaction>;
}>();

const { posthog } = usePostHog();

const createTransactionDialogOpen = ref(false);
const importDialogOpen = ref(false);

const openTransactionDialog = () => {
	posthog.capture('open-transaction-dialog', {
		type: 'OUT',
	});

	createTransactionDialogOpen.value = true;
};

const openImportDialog = () => {
	posthog.capture('open-import-transaction-dialog', {
		type: 'OUT',
	});

	importDialogOpen.value = true;
};
</script>

<template>
	<Head title="Saídas" />

	<CreateDialog
		:categories="categories"
		:accounts="accounts"
		:open="createTransactionDialogOpen"
		@close="createTransactionDialogOpen = false"
	/>

	<ImportDialog
		:categories="categories"
		:accounts="accounts"
		:open="importDialogOpen"
		@close="importDialogOpen = false"
	/>

	<AuthenticatedLayout>
		<div :class="['p-4 lg:p-6', { 'h-full': transactions.data.length === 0 }]">
			<div class="flex flex-1 flex-col h-full gap-4 lg:gap-6">
				<div class="flex items-center justify-between">
					<div>
						<h2 class="text-3xl font-bold tracking-tight">Saídas</h2>
						<!-- <p class="text-muted-foreground">
						Here&apos;s a list of your tasks for this month!
					</p> -->
					</div>

					<div
						v-if="transactions.data.length > 0"
						class="flex items-center space-x-2"
					>
						<Button variant="ghost" @click="openImportDialog">
							<CloudUploadIcon class="size-4" />
						</Button>

						<Button @click="openTransactionDialog">Adicionar saída</Button>
					</div>
				</div>

				<div
					v-if="transactions.data.length === 0 && !filter"
					class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
				>
					<div class="flex flex-col items-center gap-1 text-center">
						<h3 class="text-2xl font-bold tracking-tight">
							Você ainda não realizou uma saída.
						</h3>
						<p class="text-sm text-muted-foreground">
							Você pode começar a acompanhar sua saúde financeira registrando suas
							saídas.
							<!-- Adiciona seu primeiro orçamento para começar sua evolução financeira. -->
						</p>

						<Button @click="openTransactionDialog" class="mt-4"
							>Adicionar saída</Button
						>
					</div>
				</div>

				<DataTable
					v-else
					:data="transactions"
					:columns="columns"
					:sort="sort"
					:filter="filter"
					:categories="categories"
				/>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
