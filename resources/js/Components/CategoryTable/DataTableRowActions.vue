<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { computed, ref } from 'vue';
import { categorySchema } from './columns';
import type { Category } from './columns';
import { router, Link } from '@inertiajs/vue3';
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
import { Ellipsis, Eye } from 'lucide-vue-next';
import EditDialog from './EditDialog.vue';

interface DataTableRowActionsProps {
	row: Row<Category>;
}

const props = defineProps<DataTableRowActionsProps>();

const category = computed(() => categorySchema.parse(props.row.original));

const editDialogOpen = ref(false);
const destroyDialogOpen = ref(false);

const destroy = () => {
	router.delete(route('categories.destroy', category.value.id));
};
</script>

<template>
	<div class="flex items-center">
		<Button
			v-if="false"
			:as="Link"
			:href="route('categories.show', category.id)"
			variant="ghost"
			class="flex h-8 w-8 p-0"
		>
			<Eye class="h-4 w-4" />
		</Button>

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
			:category="category"
			@close="editDialogOpen = false"
		/>

		<AlertDialog v-model:open="destroyDialogOpen">
			<AlertDialogContent>
				<AlertDialogHeader>
					<AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
					<AlertDialogDescription>
						Você está prestes a remover a categoria <b>{{ category.name }}</b> e
						<b
							>{{ category.transactions_count }} movimentações ficarão sem categoria</b
						>. Não será possível desfazer essa ação.
					</AlertDialogDescription>
				</AlertDialogHeader>
				<AlertDialogFooter>
					<AlertDialogCancel>Voltar</AlertDialogCancel>
					<AlertDialogAction
						@click="destroy"
						class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
						>Confirmar</AlertDialogAction
					>
				</AlertDialogFooter>
			</AlertDialogContent>
		</AlertDialog>
	</div>
</template>
