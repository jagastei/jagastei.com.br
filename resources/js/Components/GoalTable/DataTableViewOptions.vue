<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import { computed } from 'vue';
import type { Goal } from './columns';
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
import { SlidersHorizontal } from 'lucide-vue-next';

interface DataTableViewOptionsProps {
	table: Table<Goal>;
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
				<SlidersHorizontal class="mr-2 h-4 w-4" />
				{{ $t('View') }}
			</Button>
		</DropdownMenuTrigger>
		<DropdownMenuContent align="end" class="w-[150px]">
			<DropdownMenuLabel>Toggle columns</DropdownMenuLabel>
			<DropdownMenuSeparator />

			<DropdownMenuCheckboxItem
				v-for="column in columns"
				:key="column.id"
				class="capitalize"
				:checked="column.getIsVisible()"
				@update:checked="
					(value: AcceptableValue) => column.toggleVisibility(!!value)
				"
			>
				{{ column.id }}
			</DropdownMenuCheckboxItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
