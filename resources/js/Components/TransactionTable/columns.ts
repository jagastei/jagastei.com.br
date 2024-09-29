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
	category_id: z.number(),
	account_id: z.number(),
	method: z.enum(['CARD', 'TED', 'PIX', 'UNKNOWN']),
	card_id: z.number(),
	created_at: z.string(),
	updated_at: z.string(),
	category: categorySchema,
	account: accountSchema,
	card: cardSchema,
});

export type Transaction = z.infer<typeof transactionSchema>;

export const columns: ColumnDef<Transaction>[] = [
	{
		id: 'select',
		header: ({ table }) =>
			h(Checkbox, {
				checked:
					table.getIsAllPageRowsSelected() ||
					(table.getIsSomePageRowsSelected() && 'indeterminate'),
				'onUpdate:checked': (value) => table.toggleAllPageRowsSelected(!!value),
				ariaLabel: 'Select all',
				class: 'translate-y-0.5',
			}),
		cell: ({ row }) =>
			h(Checkbox, {
				checked: row.getIsSelected(),
				'onUpdate:checked': (value) => row.toggleSelected(!!value),
				ariaLabel: 'Select row',
				class: 'translate-y-0.5',
			}),
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'title',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Title' }),

		cell: ({ row }) => {
			// const label = labels.find(
			//     (label) => label.value === row.original.label
			// );

			return h('div', { class: 'flex space-x-2' }, [
				// label
				//     ? h(Badge, { variant: "outline" }, () => label.label)
				//     : null,
				h(
					'span',
					{ class: 'max-w-[500px] truncate font-medium' },
					row.getValue('title')
				),
			]);
			return null;
		},
	},
	{
		accessorKey: 'category',
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Categoria' }),

		cell: ({ row }) => {
			// const status = statuses.find(
			//     (status) => status.value === row.getValue("status")
			// );

			// if (!status) return null;

			return h('div', { class: 'flex w-[100px] items-center' }, [
				// status.icon &&
				//     h(status.icon, {
				//         class: "mr-2 h-4 w-4 text-muted-foreground",
				//     }),
				h('span', row.original.category.name),
			]);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		accessorKey: 'formatted_value',
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
		accessorKey: 'created_at',
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
