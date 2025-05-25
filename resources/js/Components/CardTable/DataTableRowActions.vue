<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { computed, ref } from 'vue';
import { cardSchema } from './columns';
import type { Brand, Card } from './columns';
import { router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

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
} from '@/Components/ui/alert-dialog';
import EditDrawer from './EditDrawer.vue';
import { Ellipsis } from 'lucide-vue-next';

interface DataTableRowActionsProps {
    row: Row<Card>;
    brands: Brand[];
}

const props = defineProps<DataTableRowActionsProps>();

const card = computed(() => cardSchema.parse(props.row.original));

const editDrawerOpen = ref(false);
const destroyDialogOpen = ref(false);

const destroy = () => {
    router.delete(route('cards.destroy', card.value.id));
};
</script>

<template>
    <div>
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button variant="ghost" class="flex h-8 w-8 p-0 data-[state=open]:bg-muted">
                    <Ellipsis class="h-4 w-4" />
                    <span class="sr-only">Open menu</span>
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" class="w-[160px]">
                <DropdownMenuItem @click="editDrawerOpen = true">Editar</DropdownMenuItem>
                <!-- <DropdownMenuItem>Favoritar</DropdownMenuItem> -->
                <DropdownMenuSeparator />

                <DropdownMenuItem @click="destroyDialogOpen = true">
                    Excluir
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>

        <EditDrawer
            :open="editDrawerOpen"
            :card="card"
            :brands="brands"
            @close="editDrawerOpen = false"
        />

        <AlertDialog v-model:open="destroyDialogOpen">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Você tem certeza?</AlertDialogTitle>
                    <AlertDialogDescription>
                        Você está prestes a remover o cartão <b>{{ card.name }}</b>. Não será possível desfazer essa
                        ação.
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Voltar</AlertDialogCancel>
                    <AlertDialogAction @click="destroy">Confirmar</AlertDialogAction>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </div>
</template>
