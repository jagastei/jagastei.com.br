<script lang="ts" setup>
import { ref, computed } from 'vue'
import { useFuse } from '@vueuse/integrations/useFuse'
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
import { Account } from '@/Components/AccountTable/columns';
import { Brand } from '@/Components/CardTable/columns';

const props = defineProps<{
    brands: Brand[],
    accounts: Account[],
    open: boolean,
}>();

const emit = defineEmits(['close'])

const accountDialogOpen = ref(false)

const query = ref<string>('')

const { results } = useFuse(query, props.accounts, {
    fuseOptions: {
        keys: ['name'],
        isCaseSensitive: false,
        threshold: 0.5,
    },
    resultLimit: 50,
})

const resultList = computed(() => {
    return results.value?.map(r => r.item)
})

const onSelected = (ev: any) => {
    form.account = ev.detail.value as Account;
    accountDialogOpen.value = false;
}

const form = useForm<{
    account: Account | undefined,
    name: string,
    limit: number,
}>({
    account: undefined,
    name: '',
    limit: 0,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        account: data.account?.id,
    })).post(route('accounts.store'), {
        onFinish: () => {
            onClose()
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
                <DialogTitle>Adicionar cartão</DialogTitle>
                <DialogDescription>
                    Identifique seu cartão e clique em adicionar.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="flex flex-col">
                    <Label for="name" class="text-left">
                        Nome
                    </Label>
                    <Input id="name" v-model="form.name" placeholder="Cartão principal" class="mt-2" autocomplete="off"
                        tabindex="1" />
                </div>

                <div class="flex flex-col">
                    <Label for="limit" class="text-left">
                        Limite
                    </Label>

                    <Input id="limit" v-model.lazy="form.limit" class="mt-2" v-money3="{
                        prefix: 'R$',
                        suffix: '',
                        thousands: '.',
                        decimal: ',',
                        precision: 2,
                        disableNegative: false,
                        disabled: false,
                        min: null,
                        max: null,
                        allowBlank: false,
                        minimumNumberOfCharacters: 0,
                        shouldRound: false,
                        focusOnRight: false,
                        modelModifiers: {
                            number: false,
                        },
                    }" tabindex="2" />
                </div>

                <div class="flex flex-col">
                    <Label for="account">Conta</Label>

                    <Popover v-model:open="accountDialogOpen">
                        <PopoverTrigger as-child>
                            <Button tabindex="3" id="bank" variant="outline" role="combobox"
                                class="w-[375px] justify-between mt-2 p-3">
                                <div class="flex items-center truncate">
                                    <div v-if="form.account" class="min-w-4">
                                        <img :src="`https://jagastei.com.br.test/images/banks/${form.account.bank.code}.png`" class="size-4 rounded-xl" />
                                    </div>
                                    <span :class="['truncate', {
                                        'ml-3': form.account,
                                    }]">{{ form.account ? form.account?.name : 'Escolha uma conta' }}</span>
                                </div>
                                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-[375px] p-0">
                            <Command v-model="form.account" v-model:searchTerm="query">
                                <CommandInput class="h-9" placeholder="Buscar" name="query" autocomplete="off" />

                                <CommandEmpty>
                                    <template v-if="query.length > 0">Nenhuma conta encontrada.</template>
                                    <template v-else>Informe o nome da conta.</template>
                                </CommandEmpty>

                                <CommandList>
                                    <CommandGroup>
                                        <CommandItem v-for="account in resultList" :key="account.id" :value="account"
                                            @select="onSelected" class="flex">
                                            <div class="min-w-4">
                                                <img :src="`https://jagastei.com.br.test/images/banks/${account.bank.code}.png`" class="size-4 rounded-xl" />
                                            </div>
                                            <span class="ml-3 block truncate">{{ account.name }}</span>
                                            <Check
                                                :class="cn('ml-auto h-4 w-4', form.account?.id === account.id ? 'opacity-100' : 'opacity-0')" />
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>
                </div>
            </div>
            <DialogFooter>
                <Button :disabled="form.processing" @click="submit" type="button">
                    <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                    Adicionar
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
