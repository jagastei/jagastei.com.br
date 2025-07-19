import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { z } from 'zod';
import { accountSchema } from '../AccountTable/columns';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';
import { Globe, HandCoins, SmartphoneNfc, CreditCard } from 'lucide-vue-next';

// declare module '@tanstack/vue-table' {
// 	interface TableMeta<TData> {
// 		brands: Brand[];
// 		accounts: Account[];
// 	}
// }

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
	digits: z.string().nullable(),
	brand_id: z.string().nullable(),
	credit: z.boolean(),
	virtual: z.boolean(),
	international: z.boolean(),
	last_digits: z.string().nullable(),
	formatted_expiration_date: z.string().nullable(),
	created_at: z.string(),
	updated_at: z.string(),
	account: accountSchema,
	brand: brandSchema.nullable(),
	expiration_month: z.number().nullable(),
	expiration_year: z.number().nullable(),
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
                        onError: (event: any) => {
                            event.target.src = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9Imx1Y2lkZSBsdWNpZGUtcGlnZ3ktYmFuay1pY29uIGx1Y2lkZS1waWdneS1iYW5rIj48cGF0aCBkPSJNMTEgMTdoM3YyYTEgMSAwIDAgMCAxIDFoMmExIDEgMCAwIDAgMS0xdi0zYTMuMTYgMy4xNiAwIDAgMCAyLTJoMWExIDEgMCAwIDAgMS0xdi0yYTEgMSAwIDAgMC0xLTFoLTFhNSA1IDAgMCAwLTItNFYzYTQgNCAwIDAgMC0zLjIgMS42bC0uMy40SDExYTYgNiAwIDAgMC02IDZ2MWE1IDUgMCAwIDAgMiA0djNhMSAxIDAgMCAwIDEgMWgyYTEgMSAwIDAgMCAxLTF6Ii8+PHBhdGggZD0iTTE2IDEwaC4wMSIvPjxwYXRoIGQ9Ik0yIDh2MWEyIDIgMCAwIDAgMiAyaDEiLz48L3N2Zz4=';
                        },
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
				// h(
				// 	'img',
				// 	{
				// 		alt: row.original.brand.name,
				// 		src: `https://jagastei.com.br.test/images/brands-svg/flat/${row.original.brand.identifier}.svg`,
				// 		class: 'w-8',
				// 	},
				// 	row.original.brand.name
				// ),
				h('span', { class: 'ml-3' }, row.original.last_digits),
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
		accessorKey: 'formatted_expiration_date',
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Validade' }),

		cell: ({ row }) => {
			return h('div', {}, row.getValue('formatted_expiration_date'));
		},
		enableSorting: false,
		enableHiding: false,
	},
	// {
	// 	accessorKey: 'options',
	// 	header: ({ column }) =>
	// 		h(DataTableColumnHeader, { column, title: '' }),
	// 	cell: ({ row }) => {
	// 		return h('div', { class: 'flex items-center gap-2' }, [
	// 		    row.original.credit && h(HandCoins, { class: 'size-4' }),
	//             row.original.virtual && h(SmartphoneNfc, { class: 'size-4' }),
	//             row.original.international && h(Globe, { class: 'size-4' }),
	// 		]);
	// 	},
	// 	enableSorting: false,
	// 	enableHiding: false,
	// },
	{
		id: 'actions',
		cell: ({ row, table }) => {
			// const brands = table.options.meta?.brands as Brand[];
			// const accounts = table.options.meta?.accounts as Account[];

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
