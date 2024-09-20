
<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { computed } from 'vue'
import { accountSchema } from './columns'
import type { Account } from './columns'
import { Icon } from '@iconify/vue'

import { Button } from '@/Components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuSeparator,
  DropdownMenuShortcut,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'

interface DataTableRowActionsProps {
  row: Row<Account>
}
const props = defineProps<DataTableRowActionsProps>()

const account = computed(() => accountSchema.parse(props.row.original))
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button
        variant="ghost"
        class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
      >
        <Icon icon="radix-icons:dots-horizontal" class="h-4 w-4" />
        <span class="sr-only">Open menu</span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent align="end" class="w-[160px]">
      <DropdownMenuItem>Editar</DropdownMenuItem>
      <!-- <DropdownMenuItem>Make a copy</DropdownMenuItem> -->
      <DropdownMenuItem>Favoritar</DropdownMenuItem>
      <DropdownMenuSeparator />
      <DropdownMenuItem>
        Excluir
        <!-- <DropdownMenuShortcut>⌘⌫</DropdownMenuShortcut> -->
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
