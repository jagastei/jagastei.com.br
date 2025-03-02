import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { Checkbox } from '@/Components/ui/checkbox';
import { Badge } from '@/Components/ui/badge';
import { z } from 'zod';
import { categorySchema } from '../CategoryTable/columns';
import { accountSchema } from '../AccountTable/columns';
import { cardSchema } from '../CardTable/columns';

import { Icon } from '@iconify/vue';

export const transactionSchema = z.object({
	id: z.string(),
	title: z.string(),
	type: z.enum(['IN', 'OUT']),
	value: z.number(),
	formatted_value: z.string(),
	category_id: z.string(),
	account_id: z.string(),
	method: z.enum(['CASH', 'CARD', 'TED', 'PIX', 'OTHER', 'UNKNOWN']),
	card_id: z.string().nullable(),
	created_at: z.string(),
	updated_at: z.string(),
	category: categorySchema,
	account: accountSchema,
	card: cardSchema.nullable(),
});

export type Transaction = z.infer<typeof transactionSchema>;

export const columns: ColumnDef<Transaction>[] = [
	// {
	// 	id: 'select',
	// 	header: ({ table }) =>
	// 		h(Checkbox, {
	// 			checked:
	// 				table.getIsAllPageRowsSelected() ||
	// 				(table.getIsSomePageRowsSelected() && 'indeterminate'),
	// 			'onUpdate:checked': (value) => table.toggleAllPageRowsSelected(!!value),
	// 			ariaLabel: 'Select all',
	// 			class: 'translate-y-0.5',
	// 		}),
	// 	cell: ({ row }) =>
	// 		h(Checkbox, {
	// 			checked: row.getIsSelected(),
	// 			'onUpdate:checked': (value) => row.toggleSelected(!!value),
	// 			ariaLabel: 'Select row',
	// 			class: 'translate-y-0.5',
	// 		}),
	// 	enableSorting: false,
	// 	enableHiding: false,
	// },
	{
		id: 'title',
		accessorKey: 'title',
		meta: {
			title: 'Nome',
		},
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Nome' }),
		cell: ({ row }) => {
			return h(
				'span',
				{ class: 'max-w-[500px] truncate font-medium' },
				row.getValue('title')
			);
		},
	},
	{
		id: 'category',
		accessorKey: 'category',
		meta: {
			title: 'Categoria',
		},
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Categoria' }),

		cell: ({ row }) => {
			return h('div', { class: 'flex items-center' }, [
				h('span', {
					class: 'block size-6 rounded-xl',
					style: {
						'background-color': row.original.category.color,
					},
				}),
				h('span', { class: 'ml-3' }, row.original.category.name),
			]);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		id: 'formatted_value',
		accessorKey: 'formatted_value',
		meta: {
			title: 'Valor',
		},
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Valor' }),
		cell: ({ row }) => {
			const icon = h(Icon, {
				icon: row.original.type === 'IN' ? 'lucide:arrow-up' : 'lucide:arrow-down',
			});

			return h(
				'div',
				{
					class: 'flex items-center',
				},
				[
					h(icon, {
						// text-muted-foreground
						class: [
							'mr-2 h-4 w-4',
							row.original.type === 'IN' ? 'text-green-500' : 'text-red-500',
						],
					}),
					h('span', {}, row.getValue('formatted_value')),
				]
			);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		id: 'created_at',
		accessorKey: 'created_at',
		meta: {
			title: 'Quando',
		},
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Quando' }),
		cell: ({ row }) => {
			return h(
				'span',
				{
					class: 'flex items-center',
				},
				new Date(row.getValue('created_at')).toLocaleString()
			);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		id: 'actions',
		cell: ({ row }) => h(DataTableRowActions, { row }),
	},
];
