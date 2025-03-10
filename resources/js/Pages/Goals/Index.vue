<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { ref, onMounted, watch } from 'vue';
import CreateDialog from './CreateDialog.vue';
import DataTable from '@/Components/GoalTable/DataTable.vue';
import type { Goal } from '@/Components/GoalTable/columns';

const props = defineProps<{
	goals: Goal[];
}>();

const createGoalDialogOpen = ref(false);

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
	() => props.goals,
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
		:open="createGoalDialogOpen"
		@close="createGoalDialogOpen = false"
	/>

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col p-4 lg:p-6 h-full gap-4 lg:gap-6">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Metas</h2>
					<!-- <p class="text-muted-foreground">
                        Todas os seus metas ficam aqui.
                    </p> -->
				</div>
				<div v-if="goals.length > 0" class="flex items-center space-x-2">
					<Button @click="createGoalDialogOpen = true">Adicionar meta</Button>
				</div>
			</div>

			<div
				v-if="goals.length === 0"
				class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
			>
				<div class="flex flex-col items-center gap-1 text-center">
					<h3 class="text-2xl font-bold tracking-tight">
						Você ainda não adicionou uma meta.
					</h3>
					<p class="text-sm text-muted-foreground">
						Você pode começar a acompanhar seus objetos de vida adicionando metas.
						<!-- Adiciona seu primeiro orçamento para começar sua evolução financeira. -->
					</p>

					<Button @click="createGoalDialogOpen = true" class="mt-4">
						Adicionar meta
					</Button>
				</div>
			</div>

			<div v-else>
				<DataTable :data="goals" />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
