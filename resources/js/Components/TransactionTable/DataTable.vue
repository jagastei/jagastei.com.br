<script setup lang="ts">
import type {
	ColumnDef,
	ColumnFiltersState,
	GlobalFilterTableState,
	PaginationState,
	SortingState,
	VisibilityState,
} from '@tanstack/vue-table';
import {
	FlexRender,
	getCoreRowModel,
	getFacetedRowModel,
	getFacetedUniqueValues,
	getFilteredRowModel,
	getPaginationRowModel,
	getSortedRowModel,
	useVueTable,
} from '@tanstack/vue-table';

import { computed, ref, watch } from 'vue';
import type { Transaction } from './columns';
import DataTablePagination from './DataTablePagination.vue';
import DataTableToolbar from './DataTableToolbar.vue';
import { valueUpdater } from '@/utils';
import {
	Table,
	TableBody,
	TableCell,
	TableHead,
	TableHeader,
	TableRow,
} from '@/Components/ui/table';
import { Pagination } from '@/types/pagination';
import { router } from '@inertiajs/vue3';
import { Category } from '../CategoryTable/columns';

interface DataTableProps {
	data: Pagination<Transaction>;
	columns: ColumnDef<Transaction, any>[];
	filter: any,
	categories: Category[];
}
const props = defineProps<DataTableProps>();

const sorting = ref<SortingState>([]);

const columnFilters = ref<ColumnFiltersState>(props.filter ? Object.entries(props.filter).map(([id, value]) => ({
  id,
  value,
})): []);

const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});

const pagination = ref<PaginationState>({
	pageIndex: props.data.current_page - 1,
	pageSize: props.data.per_page,
});

const filters = computed(() => {
	return Object.assign({}, ...columnFilters.value.map((filter) => ({
		[filter.id]: Array.isArray(filter) ? (filter.value as Array<any>).join(',') : filter.value
	})))
})

watch(columnFilters, (newValue) => {
	router.get(
		route('transactions.index', {
			_query: {
				filter: filters.value,
				page: 1,
				per_page: pagination.value.pageSize,
			},
		}),
		{},
		{
			preserveState: true,
			onSuccess() {
				pagination.value.pageIndex = 0
			}
		}
	)
});

watch(pagination, (newValue) => 
	router.get(
		route('transactions.index', {
			_query: {
				filter: filters.value,
				page: newValue.pageIndex + 1,
				per_page: newValue.pageSize,
			},
		}),
		{},
		{
			preserveState: true,
		}
	)
)

const table = useVueTable({
	get data() {
		return props.data.data;
	},
	get columns() {
		return props.columns;
	},
	state: {
		get sorting() {
			return sorting.value;
		},
		get columnFilters() {
			return columnFilters.value;
		},
		get columnVisibility() {
			return columnVisibility.value;
		},
		get rowSelection() {
			return rowSelection.value;
		},
		get pagination() {
			return pagination.value;
		},
	},
	enableRowSelection: true,
	onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
	onColumnFiltersChange: (updaterOrValue) =>
		valueUpdater(updaterOrValue, columnFilters),
	onColumnVisibilityChange: (updaterOrValue) =>
		valueUpdater(updaterOrValue, columnVisibility),
	onRowSelectionChange: (updaterOrValue) =>
		valueUpdater(updaterOrValue, rowSelection),
	getCoreRowModel: getCoreRowModel(),
	getFilteredRowModel: getFilteredRowModel(),
	getPaginationRowModel: getPaginationRowModel(),
	getSortedRowModel: getSortedRowModel(),
	getFacetedRowModel: getFacetedRowModel(),
	getFacetedUniqueValues: getFacetedUniqueValues(),
	manualPagination: true,
	get rowCount() {
		return props.data.total;
	},
	onPaginationChange: (updaterOrValue) => valueUpdater(updaterOrValue, pagination),
	manualFiltering: true,
	onGlobalFilterChange: (updaterOrValue) => valueUpdater(updaterOrValue, filters),
	// manualSorting: true,
	autoResetPageIndex: false,
});
</script>

<template>
	<div class="space-y-4">
		<DataTableToolbar :table="table" :categories="categories"/>

		<div class="rounded-md border">
			<Table>
				<TableHeader>
					<TableRow
						v-for="headerGroup in table.getHeaderGroups()"
						:key="headerGroup.id"
					>
						<TableHead v-for="header in headerGroup.headers" :key="header.id">
							<FlexRender
								v-if="!header.isPlaceholder"
								:render="header.column.columnDef.header"
								:props="header.getContext()"
							/>
						</TableHead>
					</TableRow>
				</TableHeader>
				<TableBody>
					<template v-if="table.getRowModel().rows?.length">
						<TableRow
							v-for="row in table.getRowModel().rows"
							:key="row.id"
							:data-state="row.getIsSelected() && 'selected'"
						>
							<TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
								<FlexRender
									:render="cell.column.columnDef.cell"
									:props="cell.getContext()"
								/>
							</TableCell>
						</TableRow>
					</template>

					<TableRow v-else>
						<TableCell :colspan="columns.length" class="h-24 text-center">
							No results.
						</TableCell>
					</TableRow>
				</TableBody>
			</Table>
		</div>

		<DataTablePagination :table="table" />
	</div>
</template>
