<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { computed } from 'vue';
import { categorySchema } from './columns';
import type { Category } from './columns';
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
	row: Row<Category>;
}
const props = defineProps<DataTableRowActionsProps>();

const category = computed(() => categorySchema.parse(props.row.original));

const destroy = () => {
	router.delete(route('categories.destroy', category.value.id));
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
				<!-- <DropdownMenuItem>Favoritar</DropdownMenuItem> -->
				<DropdownMenuSeparator />

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
					Você está prestes a categoria <b>{{ category.name }}</b
					>. Não será possível desfazer essa ação.
				</AlertDialogDescription>
			</AlertDialogHeader>
			<AlertDialogFooter>
				<AlertDialogCancel>Voltar</AlertDialogCancel>
				<AlertDialogAction @click="destroy" class="bg-destructive text-destructive-foreground hover:bg-destructive/90">Confirmar</AlertDialogAction>
			</AlertDialogFooter>
		</AlertDialogContent>
	</AlertDialog>
</template>
