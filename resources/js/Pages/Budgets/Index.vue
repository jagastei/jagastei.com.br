<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { ref, onMounted, watch } from 'vue';
import CreateDialog from './CreateDialog.vue';
import DataTable from '@/Components/BudgetTable/DataTable.vue';
import type { Budget } from '@/Components/BudgetTable/columns';

const props = defineProps<{
	budgets: Budget[];
}>();

const createBudgetDialogOpen = ref(false);

const updateNitro = () => {
	setTimeout(() => {
		const nitros: HTMLElement[] = Array.from(
			document.querySelectorAll('[data-nitro]')
		);

		for (let index in nitros) {
			setTimeout(
				() => {
					nitros[index].style.width = `${nitros[index].dataset.nitro}%`;
				},
				parseInt(index) * 200
			);
		}
	}, 0);
};

watch(
	() => props.budgets,
	() => {
		updateNitro();
	}
);

onMounted(() => {
	updateNitro();
});
</script>

<template>
	<Head title="Orçamentos" />

	<CreateDialog
		:open="createBudgetDialogOpen"
		@close="createBudgetDialogOpen = false"
	/>

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col p-4 lg:p-6 h-full gap-4 lg:gap-6">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Orçamentos</h2>
					<!-- <p class="text-muted-foreground">
                        Todas os seus orçamentos ficam aqui.
                    </p> -->
				</div>
				<div v-if="false && budgets.length > 0" class="flex items-center space-x-2">
					<Button @click="createBudgetDialogOpen = true">Adicionar orçamento</Button>
				</div>
			</div>

			<div
				v-if="budgets.length === 0"
				class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
			>
				<div class="flex flex-col items-center gap-1 text-center">
					<h3 class="text-2xl font-bold tracking-tight">
						Você ainda não adicionou um orçamento.
					</h3>
					<p class="text-sm text-muted-foreground">
						Você pode começar a acompanhar seus orçamentos após adicionar seu primeiro
						orçamento.
					</p>

					<Button v-if="false" @click="createBudgetDialogOpen = true" class="mt-4">
						Adicionar orçamento
					</Button>
				</div>
			</div>

			<div v-else>
				<DataTable :data="budgets" />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
