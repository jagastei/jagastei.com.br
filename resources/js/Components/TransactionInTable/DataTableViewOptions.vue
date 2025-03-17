<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import { computed } from 'vue';
import type { Transaction } from './columns';
import { Icon } from '@iconify/vue';

import { Button } from '@/Components/ui/button';
import {
	DropdownMenu,
	DropdownMenuCheckboxItem,
	DropdownMenuContent,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { AcceptableValue } from 'reka-ui';

interface DataTableViewOptionsProps {
	table: Table<Transaction>;
}

const props = defineProps<DataTableViewOptionsProps>();

const columns = computed(() =>
	props.table
		.getAllColumns()
		.filter(
			(column: any) =>
				typeof column.accessorFn !== 'undefined' && column.getCanHide()
		)
);
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button variant="outline" size="sm" class="ml-auto hidden h-8 lg:flex">
				<Icon icon="radix-icons:mixer-horizontal" class="mr-2 h-4 w-4" />
				Visualização
			</Button>
		</DropdownMenuTrigger>
		<DropdownMenuContent align="end">
			<DropdownMenuLabel>Exibir colunas</DropdownMenuLabel>
			<DropdownMenuSeparator />

			<DropdownMenuCheckboxItem
				v-for="column in columns"
				:key="column.id"
				class="capitalize"
				:checked="column.getIsVisible()"
				@update:checked="(value: AcceptableValue) => column.toggleVisibility(!!value)"
				@select="(ev) => ev.preventDefault()"
			>
				{{ column.columnDef.meta?.title }}
			</DropdownMenuCheckboxItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
