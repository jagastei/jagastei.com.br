<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { ref, watch } from 'vue';
import CreateDialog from './CreateDialog.vue';
import DataTable from '@/Components/CardTable/DataTable.vue';
import type { Brand, Card } from '@/Components/CardTable/columns';
import { Account } from '@/Components/AccountTable/columns';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';
import EditDrawer from './EditDrawer.vue';
import type { Bank } from '@/Components/AccountTable/columns';

const props = defineProps<{
	brands: Brand[];
	accounts: Account[];
	banks: Bank[];
	cards: Card[];
	totalLimit: number;
	card?: Card | undefined;
}>();

const { t } = useTranslation();

const createCardDialogOpen = ref(false);
const editDrawerOpen = ref(false);

watch(
	() => props.card,
	(newCard) => {
		if (newCard) {
			editDrawerOpen.value = true;
		}
	},
	{ immediate: true }
);

const closeEditDrawer = () => {
	editDrawerOpen.value = false;

	setTimeout(() => {
		router.visit(route('cards.index'), {
			preserveState: true,
			preserveScroll: true,
		});
	}, 250);
};
</script>

<template>
	<Head :title="$t('Cards')" />

	<CreateDialog
		:open="createCardDialogOpen"
		:brands="brands"
		:accounts="accounts"
		:banks="banks"
		@close="createCardDialogOpen = false"
	/>

	<EditDrawer
		v-if="!!card"
		:open="editDrawerOpen"
		:card="card"
		:brands="brands"
		@close="closeEditDrawer"
	/>

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 h-full">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Cartões</h2>
					<p class="text-muted-foreground">
						Limite total: {{ useCurrency(t, totalLimit) }}
					</p>
				</div>
				<div v-if="cards.length > 0" class="flex items-center space-x-2">
					<Button @click="createCardDialogOpen = true">Adicionar cartão</Button>
				</div>
			</div>

			<div
				v-if="cards.length === 0"
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

					<Button @click="createCardDialogOpen = true" class="mt-4">
						Adicionar cartão
					</Button>
				</div>
			</div>

			<div v-else>
				<DataTable :data="cards" />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
