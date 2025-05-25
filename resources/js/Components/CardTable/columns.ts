import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { z } from 'zod';
import { accountSchema } from '../AccountTable/columns';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';

declare module '@tanstack/vue-table' {
	interface TableMeta<TData> {
		brands: Brand[];
	}
}

export const brandSchema = z.object({
	id: z.string(),
	identifier: z.string(),
	name: z.string(),
	enabled: z.boolean(),
	created_at: z.string(),
	updated_at: z.string(),
	deleted_at: z.string().nullable(),
});

export const cardSchema = z.object({
	id: z.string(),
	account_id: z.string(),
	name: z.string(),
	limit: z.number(),
	formatted_limit: z.string(),
	digits: z.string(),
	brand_id: z.string(),
	credit: z.boolean(),
	virtual: z.boolean(),
	international: z.boolean(),
	created_at: z.string(),
	updated_at: z.string(),
	account: accountSchema,
	brand: brandSchema,
});

export type Brand = z.infer<typeof brandSchema>;
export type Card = z.infer<typeof cardSchema>;

export const columns: ColumnDef<Card>[] = [
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
				h(
					'img',
					{
						alt: row.original.account.bank.long_name,
						src: `https://jagastei.com.br.test/images/banks/${row.original.account.bank.code}.png`,
						class: 'size-6 rounded-xl',
					},
					row.getValue('name')
				),
				h('span', { class: 'ml-3' }, row.getValue('name')),
			]);
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'details',
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Detalhes' }),
		cell: ({ row }) => {
			return h('div', { class: 'flex items-center' }, [
				h(
					'img',
					{
						alt: row.original.brand.name,
						src: `https://jagastei.com.br.test/images/brands-svg/flat/${row.original.brand.identifier}.svg`,
						class: 'w-8',
					},
					row.original.brand.name
				),
				h('span', { class: 'ml-3' }, row.original.digits),
			]);
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'limit',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Limite' }),

		cell: ({ row }) => {
			const { t } = useTranslation();
			return h('div', {}, useCurrency(t, row.getValue('limit')));
		},
		enableSorting: false,
		enableHiding: false,
	},
	{
		id: 'actions',
		cell: ({ row, table }) => {
			const brands = table.options.meta?.brands as Brand[];

			return h(
				'div',
				{
					class: 'flex justify-end',
				},
				[h(DataTableRowActions, { row, brands })]
			);
		},
	},
];
