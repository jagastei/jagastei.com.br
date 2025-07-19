<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { computed, ref } from 'vue';
import { budgetSchema } from './columns';
import type { Budget } from './columns';
import { router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuItem,
	DropdownMenuRadioGroup,
	DropdownMenuRadioItem,
	DropdownMenuSeparator,
	DropdownMenuShortcut,
	DropdownMenuSub,
	DropdownMenuSubContent,
	DropdownMenuSubTrigger,
	DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

import {
	AlertDialog,
	AlertDialogAction,
	AlertDialogCancel,
	AlertDialogContent,
	AlertDialogDescription,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogTitle,
	AlertDialogTrigger,
} from '@/Components/ui/alert-dialog';
import EditDialog from './EditDialog.vue';
import { Ellipsis } from 'lucide-vue-next';

interface DataTableRowActionsProps {
	row: Row<Budget>;
}
const props = defineProps<DataTableRowActionsProps>();

const budget = computed(() => budgetSchema.parse(props.row.original));

const editDialogOpen = ref(false);
const destroyDialogOpen = ref(false);

const destroy = () => {
	router.delete(route('budgets.destroy', budget.value.id));
};
</script>

<template>
	<div>
		<DropdownMenu>
			<DropdownMenuTrigger as-child>
				<Button variant="ghost" class="flex h-8 w-8 p-0 data-[state=open]:bg-muted">
					<Ellipsis class="h-4 w-4" />
					<span class="sr-only">Open menu</span>
				</Button>
			</DropdownMenuTrigger>
			<DropdownMenuContent align="end" class="w-[160px]">
				<DropdownMenuItem @click="editDialogOpen = true">Editar</DropdownMenuItem>
				<!-- <DropdownMenuItem>Favoritar</DropdownMenuItem> -->
				<DropdownMenuSeparator />

				<DropdownMenuItem @click="destroyDialogOpen = true">
					Excluir
				</DropdownMenuItem>
			</DropdownMenuContent>
		</DropdownMenu>

		<EditDialog
			:open="editDialogOpen"
			:budget="budget"
			@close="editDialogOpen = false"
		/>

		<AlertDialog v-model:open="destroyDialogOpen">
			<AlertDialogContent>
				<AlertDialogHeader>
					<AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
					<AlertDialogDescription>
						Você está prestes a remover o orçamento
						<b>{{ budget.name }}</b
						>. Não será possível desfazer essa ação.
					</AlertDialogDescription>
				</AlertDialogHeader>
				<AlertDialogFooter>
					<AlertDialogCancel>Voltar</AlertDialogCancel>
					<AlertDialogAction @click="destroy" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Confirmar</AlertDialogAction>
				</AlertDialogFooter>
			</AlertDialogContent>
		</AlertDialog>
	</div>
</template>
