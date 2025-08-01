<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import DataTable from '@/Components/TransactionInTable/DataTable.vue';
import { columns, Transaction } from '@/Components/TransactionInTable/columns';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Pagination } from '@/types/pagination';
import { Category } from '@/Components/CategoryTable/columns';
import { Button } from '@/Components/ui/button';
import { ref } from 'vue';
import CreateDialog from './CreateDialog.vue';
import { Account } from '@/Components/AccountTable/columns';
import { usePostHog } from '@/composables/usePosthog';

defineProps<{
	sort?: string;
	filter?: object;
	categories: Category[];
	accounts: Account[];
	transactions: Pagination<Transaction>;
}>();

const { posthog } = usePostHog();

const createTransactionDialogOpen = ref(false);

const openTransactionDialog = () => {
	posthog.capture('open-transaction-dialog', {
		type: 'IN',
	});

	createTransactionDialogOpen.value = true;
};

const form = useForm({
	files: [],
});

// const ai = ref({ "empresa": "A. ANGELONI CIA LTA", "data": "07/02/2025", "localizacao": "Jaraguá do Sul, SC", "metodo_pagamento": "Cartão de crédito", "itens": [{ "descricao": "Papel Higiene Supreme F", "quantidade": "1 UN", "valor": "11,99", "total": "11,99" }, { "descricao": "Pão Seven Boys Leite PCT", "quantidade": "1 UN", "valor": "10,99", "total": "10,99" }, { "descricao": "Granola Jasmine INT Melf", "quantidade": "1 UN", "valor": "10,59", "total": "10,59" }, { "descricao": "Cereal Nestlé Nescau CX", "quantidade": "1 UN", "valor": "11,39", "total": "11,39" }, { "descricao": "Mastés Flexíveis Cotonet", "quantidade": "1 UN", "valor": "17,59", "total": "17,59" }, { "descricao": "Desodorante Old Spice Masculino", "quantidade": "1 UN", "valor": "9,99", "total": "9,99" }, { "descricao": "Bebida Energética Monster Mango", "quantidade": "1 UN", "valor": "8,59", "total": "8,59" }, { "descricao": "Bebida Energética Red Bull Maracuja", "quantidade": "1 UN", "valor": "8,49", "total": "8,49" }, { "descricao": "Creme Dental Boni Natura", "quantidade": "1 UN", "valor": "18,59", "total": "18,59" }], "total": "121,19" });
const ai = ref<any>(null);

const handleSubmit = () => {
	form.post(route('transactions.import'), {
		preserveScroll: true,
		preserveState: true,
		forceFormData: true,
		onSuccess: (response) => {
			form.files = [];
			ai.value = response.props.ai;
		},
	});
};

const onUploadDialogOpen = (open: boolean) => {
	if (open) {
		// form.files = [];
		// ai.value = null;
	}
};
</script>

<template>
	<Head title="Entradas" />

	<CreateDialog
		:categories="categories"
		:accounts="accounts"
		:open="createTransactionDialogOpen"
		@close="createTransactionDialogOpen = false"
	/>

	<AuthenticatedLayout>
		<div :class="['p-4 lg:p-6', { 'h-full': transactions.data.length === 0 }]">
			<div class="flex flex-1 flex-col h-full gap-4 lg:gap-6">
				<div class="flex items-center justify-between">
					<div>
						<h2 class="text-3xl font-bold tracking-tight">Entradas</h2>
						<!-- <p class="text-muted-foreground">
							Here&apos;s a list of your tasks for this month!
						</p> -->
					</div>

					<div
						v-if="transactions.data.length > 0"
						class="flex items-center space-x-2"
					>
						<!-- <Dialog @update:open="onUploadDialogOpen">
							<DialogTrigger as-child>
								<Button variant="ghost">
									<CloudUploadIcon class="size-4" />
								</Button>
							</DialogTrigger>
							<DialogContent class="sm:max-w-[425px]">
								<DialogHeader>
									<DialogTitle>Importar entradas</DialogTitle>
									<DialogDescription>
										Faça o upload de uma imagem com suas entradas.
									</DialogDescription>
								</DialogHeader>
								<div class="py-2">
									<AI v-if="ai" :data="ai" />
									<UploadFile
										v-else
										v-model="form.files"
										:preview-max-height="319"
										:loading="form.processing"
									/>
								</div>
								<DialogFooter>
									<Button
										v-if="!ai && !form.processing && form.files.length > 0"
										variant="outline"
										@click="form.files = []"
									>
										Usar outra imagem
									</Button>

									<Button
										v-if="!ai"
										type="submit"
										@click="handleSubmit"
										:disabled="form.files.length === 0 || form.processing"
									>
										<Loader2 v-if="form.processing" class="size-4 mr-2 animate-spin" />
										Enviar
									</Button>

									<Button v-else type="submit">
										<Loader2 class="size-4 mr-2 animate-spin" />
										Confirmar
									</Button>
								</DialogFooter>
							</DialogContent>
						</Dialog> -->

						<Button @click="openTransactionDialog">Adicionar entrada</Button>
					</div>
				</div>

				<div
					v-if="transactions.data.length === 0 && !filter"
					class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
				>
					<div class="flex flex-col items-center gap-1 text-center">
						<h3 class="text-2xl font-bold tracking-tight">
							Você ainda realizou uma entrada.
						</h3>
						<p class="text-sm text-muted-foreground">
							Você pode começar a acompanhar sua saúde financeira registrando suas
							entradas.
							<!-- Adiciona seu primeiro orçamento para começar sua evolução financeira. -->
						</p>

						<Button @click="openTransactionDialog" class="mt-4"
							>Adicionar entrada</Button
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
