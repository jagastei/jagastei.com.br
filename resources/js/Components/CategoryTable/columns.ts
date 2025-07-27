import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { z } from 'zod';

export const categorySchema = z.object({
	id: z.string(),
	wallet_id: z.string(),
	name: z.string(),
	color: z.string().nullable(),
	type: z.enum(['IN', 'OUT']),
	created_at: z.string(),
	updated_at: z.string(),
	transactions_count: z.number().optional(),
	transactions_sum_value: z.number().optional(),
});

export type Category = z.infer<typeof categorySchema>;

export const columns: ColumnDef<Category>[] = [
	// {
	//     id: "select",
	//     header: ({ table }) =>
	//         h(Checkbox, {
	//             checked:
	//                 table.getIsAllPageRowsSelected() ||
	//                 (table.getIsSomePageRowsSelected() && "indeterminate"),
	//             "onUpdate:checked": (value) =>
	//                 table.toggleAllPageRowsSelected(!!value),
	//             ariaLabel: "Select all",
	//             class: "translate-y-0.5",
	//         }),
	//     cell: ({ row }) =>
	//         h(Checkbox, {
	//             checked: row.getIsSelected(),
	//             "onUpdate:checked": (value) => row.toggleSelected(!!value),
	//             ariaLabel: "Select row",
	//             class: "translate-y-0.5",
	//         }),
	//     enableSorting: false,
	//     enableHiding: false,
	// },
	{
		accessorKey: 'name',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Nome' }),
		cell: ({ row }) => {
			return h('div', { class: 'flex items-center' }, [
				h('span', {
					class: 'block size-6 rounded-xl',
					style: {
						'background-color': row.original.color,
					},
				}),
				h('span', { class: 'ml-3' }, row.getValue('name')),
			]);
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		id: 'actions',
        header: () => h('div'),
		cell: ({ row }) => {
			return h(
				'div',
				{
					class: 'flex justify-end',
				},
				[h(DataTableRowActions, { row })]
			);
		},
	},
];
