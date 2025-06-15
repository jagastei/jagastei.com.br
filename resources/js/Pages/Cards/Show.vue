<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { Card } from '@/Components/CardTable/columns';

const props = defineProps<{
    card: Card;
}>();
</script>

<template>
	<Head title="Cartão" />

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 h-full">
			<!-- <div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Cartões</h2>
					<p class="text-muted-foreground">
						Limite total: {{ useCurrency(t, totalLimit) }}
					</p>
				</div>
				<div v-if="cards.length > 0" class="flex items-center space-x-2">
					<Button @click="createCardDialogOpen = true">Adicionar cartão</Button>
				</div>
			</div> -->

			<div
				class="p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
			>
				<div class="flex flex-col items-center gap-1 text-center">
					<h3 class="text-2xl font-bold tracking-tight">
						Você ainda não adicionou um cartão.
					</h3>
					<p class="text-sm text-muted-foreground">
						Você pode começar a acompanhar sua evolução financeira após adicionar seu
						primeiro cartão.
						<!-- Adiciona seu primeiro cartão para começar sua evolução financeira. -->
					</p>
				</div>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
