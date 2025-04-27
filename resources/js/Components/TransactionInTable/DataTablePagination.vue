<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import type { Transaction } from './columns';

import { Button } from '@/Components/ui/button';
import {
	Select,
	SelectContent,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/Components/ui/select';
import { AcceptableValue } from 'reka-ui';
import {
	ChevronLeft,
	ChevronRight,
	ChevronsLeft,
	ChevronsRight,
} from 'lucide-vue-next';

interface DataTablePaginationProps {
	table: Table<Transaction>;
}
defineProps<DataTablePaginationProps>();
</script>

<template>
	<div class="flex items-center justify-between">
		<div class="flex-1 text-sm text-muted-foreground">
			{{ table.getFilteredSelectedRowModel().rows.length }} de
			{{ table.getFilteredRowModel().rows.length }} linhas selecionadas.
		</div>
		<div class="flex items-center space-x-6 lg:space-x-8">
			<div class="flex items-center space-x-2">
				<p class="text-sm font-medium">Linhas por página</p>
				<Select
					:model-value="`${table.getState().pagination.pageSize}`"
					@update:model-value="
						(value: AcceptableValue) => table.setPageSize(Number(value))
					"
				>
					<SelectTrigger class="h-8 w-[70px]">
						<SelectValue :placeholder="`${table.getState().pagination.pageSize}`" />
					</SelectTrigger>
					<SelectContent side="top">
						<SelectItem
							v-for="pageSize in [10, 20, 30, 40, 50]"
							:key="pageSize"
							:value="`${pageSize}`"
						>
							{{ pageSize }}
						</SelectItem>
					</SelectContent>
				</Select>
			</div>
			<div class="flex items-center justify-center text-sm font-medium">
				Página {{ table.getState().pagination.pageIndex + 1 }} de
				{{ table.getPageCount() }}
			</div>
			<div class="flex items-center space-x-2">
				<Button
					variant="outline"
					class="hidden h-8 w-8 p-0 lg:flex"
					:disabled="!table.getCanPreviousPage()"
					@click="table.setPageIndex(0)"
				>
					<span class="sr-only">Go to first page</span>
					<ChevronsLeft class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="h-8 w-8 p-0"
					:disabled="!table.getCanPreviousPage()"
					@click="table.previousPage()"
				>
					<span class="sr-only">Go to previous page</span>
					<ChevronLeft class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="h-8 w-8 p-0"
					:disabled="!table.getCanNextPage()"
					@click="table.nextPage()"
				>
					<span class="sr-only">Go to next page</span>
					<ChevronRight class="h-4 w-4" />
				</Button>
				<Button
					variant="outline"
					class="hidden h-8 w-8 p-0 lg:flex"
					:disabled="!table.getCanNextPage()"
					@click="table.setPageIndex(table.getPageCount() - 1)"
				>
					<span class="sr-only">Go to last page</span>
					<ChevronsRight class="h-4 w-4" />
				</Button>
			</div>
		</div>
	</div>
</template>
