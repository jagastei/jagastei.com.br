<script setup lang="ts">
import type { Row } from '@tanstack/vue-table'
import { computed } from 'vue'
import { cardSchema } from './columns'
import type { Card } from './columns'
import { Icon } from '@iconify/vue'
import { router } from '@inertiajs/vue3'
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

import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/Components/ui/alert-dialog'

interface DataTableRowActionsProps {
  row: Row<Card>
}
const props = defineProps<DataTableRowActionsProps>()

const card = computed(() => cardSchema.parse(props.row.original))

const destroy = () => {
  router.delete(route('cards.destroy', card.value.id))
}
</script>

<template>
  <AlertDialog>
    <DropdownMenu>
      <DropdownMenuTrigger as-child>
        <Button variant="ghost" class="flex h-8 w-8 p-0 data-[state=open]:bg-muted">
          <Icon icon="radix-icons:dots-horizontal" class="h-4 w-4" />
          <span class="sr-only">Open menu</span>
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="end" class="w-[160px]">
        <DropdownMenuItem>Editar</DropdownMenuItem>
        <!-- <DropdownMenuItem>Make a copy</DropdownMenuItem> -->
        <DropdownMenuItem>Favoritar</DropdownMenuItem>
        <DropdownMenuSeparator />

        <DropdownMenuItem class="p-0">
          <AlertDialogTrigger class="px-2 py-1.5 w-full text-left">Excluir</AlertDialogTrigger>
        </DropdownMenuItem>

      </DropdownMenuContent>
    </DropdownMenu>

    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
        <AlertDialogDescription>
          Você está prestes a remover o cartão <b>{{ card.name }}</b>. Não será possível desfazer essa ação.
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel>Voltar</AlertDialogCancel>
        <AlertDialogAction @click="destroy">Confirmar</AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
