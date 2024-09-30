import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { Checkbox } from '@/Components/ui/checkbox';
import { Badge } from '@/Components/ui/badge';
import { z } from 'zod';

import { Icon } from '@iconify/vue';

export const bankSchema = z.object({
	id: z.string(),
	code: z.string(),
	short_name: z.string(),
	long_name: z.string(),
});

// We're keeping a simple non-relational schema here.
// IRL, you will have a schema for your data models.
export const accountSchema = z.object({
	id: z.string(),
	user_id: z.string(),
	bank_id: z.string().nullable(),
	name: z.string(),
	balance: z.number(),
	formatted_balance: z.string(),
	created_at: z.string(),
	updated_at: z.string(),
	bank: bankSchema,
});

export type Bank = z.infer<typeof bankSchema>;
export type Account = z.infer<typeof accountSchema>;

export const columns: ColumnDef<Account>[] = [
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
				h('img', {
					alt: row.original.bank.long_name,
					src: `https://jagastei.com.br.test/images/banks/${row.original.bank.code}.png`,
					class: 'size-6 rounded-xl',
				}),
				h('span', { class: 'ml-3' }, row.getValue('name')),
			]);
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'formatted_balance',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Saldo' }),

		cell: ({ row }) => {
			return h('div', {}, row.getValue('formatted_balance'));
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
