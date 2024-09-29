import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import DataTableColumnHeader from './DataTableColumnHeader.vue';
import DataTableRowActions from './DataTableRowActions.vue';
import { Checkbox } from '@/Components/ui/checkbox';
import { Badge } from '@/Components/ui/badge';
import { z } from 'zod';
import { Icon } from '@iconify/vue';

export const taskSchema = z.object({
	id: z.string(),
	title: z.string(),
	status: z.string(),
	label: z.string(),
	priority: z.string(),
});

export type Task = z.infer<typeof taskSchema>;

export const columns: ColumnDef<Task>[] = [
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
		accessorKey: 'id',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Task' }),
		cell: ({ row }) => h('div', { class: 'w-20' }, row.getValue('id')),
		enableSorting: false,
		enableHiding: false,
	},
	{
		accessorKey: 'title',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Title' }),

		cell: ({ row }) => {
			const label = labels.find((label) => label.value === row.original.label);

			return h('div', { class: 'flex space-x-2' }, [
				label ? h(Badge, { variant: 'outline' }, () => label.label) : null,
				h(
					'span',
					{ class: 'max-w-[500px] truncate font-medium' },
					row.getValue('title')
				),
			]);
		},
	},
	{
		accessorKey: 'status',
		header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Status' }),

		cell: ({ row }) => {
			const status = statuses.find(
				(status) => status.value === row.getValue('status')
			);

			if (!status) return null;

			return h('div', { class: 'flex w-[100px] items-center' }, [
				status.icon &&
					h(status.icon, {
						class: 'mr-2 h-4 w-4 text-muted-foreground',
					}),
				h('span', status.label),
			]);
		},
		filterFn: (row, id, value) => {
			return value.includes(row.getValue(id));
		},
	},
	{
		accessorKey: 'priority',
		header: ({ column }) =>
			h(DataTableColumnHeader, { column, title: 'Priority' }),
		cell: ({ row }) => {
			const priority = priorities.find(
				(priority) => priority.value === row.getValue('priority')
			);

			if (!priority) return null;

			return h('div', { class: 'flex items-center' }, [
				priority.icon &&
					h(priority.icon, {
						class: 'mr-2 h-4 w-4 text-muted-foreground',
					}),
				h('span', {}, priority.label),
			]);
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

export const labels = [
	{
		value: 'bug',
		label: 'Bug',
	},
	{
		value: 'feature',
		label: 'Feature',
	},
	{
		value: 'documentation',
		label: 'Documentation',
	},
];

export const statuses = [
	{
		value: 'backlog',
		label: 'Backlog',
		icon: h(Icon, {
			icon: 'radix-icons:question-mark-circled',
		}),
	},
	{
		value: 'todo',
		label: 'Todo',
		icon: h(Icon, {
			icon: 'radix-icons:circle',
		}),
	},
	{
		value: 'in progress',
		label: 'In Progress',
		icon: h(Icon, {
			icon: 'radix-icons:stopwatch',
		}),
	},
	{
		value: 'done',
		label: 'Done',
		icon: h(Icon, {
			icon: 'radix-icons:check-circled',
		}),
	},
	{
		value: 'canceled',
		label: 'Canceled',
		icon: h(Icon, {
			icon: 'radix-icons:cross-circled',
		}),
	},
];

export const priorities = [
	{
		value: 'low',
		label: 'Low',
		icon: h(Icon, {
			icon: 'radix-icons:arrow-down',
		}),
	},
	{
		value: 'medium',
		label: 'Medium',
		icon: h(Icon, {
			icon: 'radix-icons:arrow-right',
		}),
	},
	{
		value: 'high',
		label: 'High',
		icon: h(Icon, {
			icon: 'radix-icons:arrow-up',
		}),
	},
];
