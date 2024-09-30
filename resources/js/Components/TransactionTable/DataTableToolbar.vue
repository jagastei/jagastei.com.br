<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import { computed } from 'vue';
import type { Transaction } from './columns';

// import { priorities, statuses } from './columns'
import DataTableFacetedFilter from './DataTableFacetedFilter.vue';
import DataTableViewOptions from './DataTableViewOptions.vue';
import { Icon } from '@iconify/vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Category, categorySchema } from '../CategoryTable/columns';

interface DataTableToolbarProps {
	table: Table<Transaction>;
	categories: Category[];
}

const props = defineProps<DataTableToolbarProps>();

const isFiltered = computed(
	() => props.table.getState().columnFilters.length > 0
);
</script>

<template>
	<div class="flex items-center justify-between">
		<div class="flex flex-1 items-center space-x-2">
			<Input
				placeholder="Pesquisar..."
				:model-value="(table.getColumn('title')?.getFilterValue() as string) ?? ''"
				class="h-8 w-[150px] lg:w-[250px]"
				@input="table.getColumn('title')?.setFilterValue($event.target.value)"
			/>

			<DataTableFacetedFilter
				v-if="table.getColumn('category')"
				:column="table.getColumn('category')"
				title="Categoria"
				:options="
					categories.map((category) => ({
						value: `${category.id}`,
						label: category.name,
					}))
				"
			/>

			<!-- <DataTableFacetedFilter
				v-if="table.getColumn('priority')"
				:column="table.getColumn('priority')"
				title="Priority"
				:options="priorities"
			/> -->

			<Button
				v-if="isFiltered"
				variant="ghost"
				class="h-8 px-2 lg:px-3"
				@click="table.resetColumnFilters()"
			>
				Limpar
				<Icon icon="radix-icons:cross-2" class="ml-2 h-4 w-4" />
			</Button>
		</div>
		<DataTableViewOptions :table="table" />
	</div>
</template>
