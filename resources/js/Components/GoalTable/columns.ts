import type { ColumnDef } from "@tanstack/vue-table";
import { h } from "vue";

import DataTableColumnHeader from "./DataTableColumnHeader.vue";
import DataTableRowActions from "./DataTableRowActions.vue";
import { Checkbox } from "@/Components/ui/checkbox";
import { Badge } from "@/Components/ui/badge";
import { z } from "zod";

import { Icon } from '@iconify/vue';

export const goalSchema = z.object({
    id: z.number(),
    user_id: z.number(),
    name: z.string(),
    total: z.number(),
    formatted_total: z.string(),
    current: z.number(),
    formatted_current: z.string(),
    formatted_limit: z.string(),
    created_at: z.string(),
    updated_at: z.string(),
});

export type Goal = z.infer<typeof goalSchema>;

export const columns: ColumnDef<Goal>[] = [
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
        accessorKey: "name",
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: "Nome" }),
        cell: ({ row }) => {
            return h('span', { class: 'ml-3' }, row.getValue("name"));
        },
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: "formatted_limit",
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: "Limite" }),

        cell: ({ row }) => {
            return h('div', {}, row.getValue('formatted_limit'));
        },
        enableSorting: false,
        enableHiding: false,
    },
    {
        id: "actions",
        cell: ({ row }) => {
            return h('div', {
                class: 'flex justify-end'
            }, [
                h(DataTableRowActions, { row })
            ])
        },
    },
];