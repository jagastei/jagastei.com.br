import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { Checkbox } from '@/Components/ui/checkbox';
import { Badge } from '@/Components/ui/badge';
import { z } from 'zod';

import { Icon } from '@iconify/vue';

export const budgetSchema = z.object({
	id: z.string(),
	user_id: z.string(),
	name: z.string(),
	total: z.number(),
	formatted_total: z.string(),
	current: z.number(),
	formatted_current: z.string(),
	formatted_diff: z.string(),
	percentage: z.number(),
	created_at: z.string(),
	updated_at: z.string(),
});

export type Budget = z.infer<typeof budgetSchema>;

export const columns: ColumnDef<Budget>[] = [
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
			return h('span', {}, row.getValue('name'));
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'formatted_total',
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Progresso' }),

		cell: ({ row }) => {
			const text = `${row.original.formatted_total}`;

			return h('div', { class: 'flex flex-col' }, [
				h(
					'span',
					{
						class: 'text-sm',
					},
					text
				),
				h(
					'div',
					{
						class: 'relative mt-2 w-20 h-2 rounded-full overflow-hidden',
					},
					[
						h(
							'div',
							{
								class: [
									'absolute h-full w-full',
									row.original.percentage === 100 ? 'bg-green-100' : 'bg-secondary',
								],
							},
							[]
						),
						h(
							'div',
							{
								'data-nitro': row.original.percentage,
								class: [
									'absolute h-full transition-all duration-1000 ease-out',
									row.original.percentage === 100 ? 'bg-green-500' : 'bg-blue-500',
								],
								style: {
									width: '0%',
								},
							},
							[]
						),
					]
				),
			]);
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		id: 'actions',
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
