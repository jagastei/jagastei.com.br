<script lang="ts" setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { useFuse } from '@vueuse/integrations/useFuse'
import { useColorMode } from '@vueuse/core'
import { useForm } from '@inertiajs/vue3';
import { cn } from '@/utils'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/Components/ui/dialog'
import { type DialogContentEmits, type DialogContentProps, useEmitAsProps, } from 'radix-vue'
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/Components/ui/number-field'

import { Check, ChevronsUpDown, Loader2 } from 'lucide-vue-next'
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/Components/ui/command'
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover'
import { Button } from '@/Components/ui/button'
import { Label } from '@/Components/ui/label'
import { Input } from '@/Components/ui/input'
import { CurrencyDisplay, useCurrencyInput, ValueScaling } from 'vue-currency-input'
import { Bank } from '@/Components/AccountTable/columns'

const props = defineProps<{
    open: boolean,
}>();

const emit = defineEmits(['close'])

const form = useForm<{
    name: string,
}>({
    name: '',
    color: '#22C55E',
});

const submit = () => {
    form.post(route('categories.store'), {
        onSuccess: () => {
            onClose()
        },
        onError: (error) => {
            console.log(error)
        },
    });
};

const onClose = () => {
    form.reset()
    emit('close')
}
</script>

<template>
    <Dialog :open="open">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent class="sm:max-w-[425px]" @interactOutside="onClose" @escapeKeyDown="onClose">
            <DialogHeader>
                <DialogTitle>Adicionar categoria</DialogTitle>
                <DialogDescription></DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="flex flex-col">
                    <Label for="name" class="text-left">
                        Nome
                    </Label>
                    <Input id="name" v-model="form.name" placeholder="Alimentação" class="mt-2" autocomplete="off"
                        tabindex="1" />
                </div>

                <div class="flex flex-col">
                    <Label for="color" class="text-left w-fit">
                        Cor
                    </Label>
                    <Input id="color" type="color" v-model="form.color" class="mt-2 cursor-pointer" tabindex="2"/>
                </div>
            </div>
            <DialogFooter>
                <Button :disabled="form.processing" @click="submit" type="button" tabindex="3">
                    <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                    Adicionar
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>