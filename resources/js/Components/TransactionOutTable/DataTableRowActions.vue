<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { computed } from 'vue';
import { transactionSchema } from './columns';
import type { Transaction } from './columns';
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
import { Ellipsis } from 'lucide-vue-next';

interface DataTableRowActionsProps {
	row: Row<Transaction>;
}
const props = defineProps<DataTableRowActionsProps>();

const transaction = computed(() => transactionSchema.parse(props.row.original));

const destroy = () => {
	router.delete(route('transactions.out.destroy', transaction.value.id));
};
</script>

<template>
	<AlertDialog>
		<DropdownMenu>
			<DropdownMenuTrigger as-child>
				<Button variant="ghost" class="flex h-8 w-8 p-0 data-[state=open]:bg-muted">
					<Ellipsis class="h-4 w-4" />
					<span class="sr-only">Open menu</span>
				</Button>
			</DropdownMenuTrigger>
			<DropdownMenuContent align="end" class="w-[160px]">
				<DropdownMenuItem>Editar</DropdownMenuItem>
				<DropdownMenuSeparator />
				<!--
			<DropdownMenuSub>
				<DropdownMenuSubTrigger>Labels</DropdownMenuSubTrigger>
					<DropdownMenuSubContent>
					<DropdownMenuRadioGroup :value="task.label">
						<DropdownMenuRadioItem v-for="label in labels" :key="label.value" :value="label.value">
						{{ label.label }}
						</DropdownMenuRadioItem>
					</DropdownMenuRadioGroup>
					</DropdownMenuSubContent>
				</DropdownMenuSub>
			<DropdownMenuSeparator />
			-->
				<DropdownMenuItem class="p-0">
					<AlertDialogTrigger class="px-2 py-1.5 w-full text-left"
						>Excluir</AlertDialogTrigger
					>
				</DropdownMenuItem>
			</DropdownMenuContent>
		</DropdownMenu>

		<AlertDialogContent>
			<AlertDialogHeader>
				<AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
				<AlertDialogDescription>
					<!-- Você está prestes a remover a movimentação
					<b>{{ transaction.title }}</b> com o valor de
					<b>{{ transaction.formatted_value }}</b
					>. Não será possível desfazer essa ação. -->
					Você está prestes a remover a movimentação
					<b>{{ transaction.title }}</b
					>. Não será possível desfazer essa ação.
				</AlertDialogDescription>
			</AlertDialogHeader>
			<AlertDialogFooter>
				<AlertDialogCancel>Voltar</AlertDialogCancel>
				<AlertDialogAction @click="destroy">Confirmar</AlertDialogAction>
			</AlertDialogFooter>
		</AlertDialogContent>
	</AlertDialog>
</template>
