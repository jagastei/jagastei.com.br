import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { z } from 'zod';
import { categorySchema } from '../CategoryTable/columns';
import { accountSchema } from '../AccountTable/columns';
import { cardSchema } from '../CardTable/columns';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';

export const transactionSchema = z.object({
	id: z.string(),
	title: z.string(),
	type: z.enum(['IN', 'OUT']),
	value: z.number(),
	category_id: z.number(),
	account_id: z.number(),
	method: z.enum(['CASH', 'CARD', 'TED', 'PIX', 'OTHER', 'UNKNOWN']).nullable(),
	card_id: z.number().nullable(),
	datetime: z.string(),
	formatted_datetime: z.string(),
	created_at: z.string(),
	formatted_created_at: z.string(),
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
		enableSorting: false,
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
		id: 'value',
		accessorKey: 'value',
		meta: {
			title: 'Valor',
		},
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Valor' }),
		cell: ({ row }) => {
			// const icon = row.original.type === 'IN' ? ArrowUp : ArrowDown;
			const { t } = useTranslation();

			return h(
				'div',
				{
					class: 'flex items-center',
				},
				[
					// h(icon, {
					// 	// text-muted-foreground
					// 	class: [
					// 		'mr-2 h-4 w-4',
					// 		row.original.type === 'IN' ? 'text-green-500' : 'text-red-500',
					// 	],
					// }),
					h('span', {}, useCurrency(t, row.original.value)),
				]
			);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		id: 'category',
		accessorKey: 'category',
		enableSorting: false,
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
		id: 'datetime',
		accessorKey: 'formatted_datetime',
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
				row.original.formatted_datetime
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
