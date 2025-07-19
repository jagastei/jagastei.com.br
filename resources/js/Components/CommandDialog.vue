<script setup lang="ts">
import {
    Calculator,
    Calendar,
    CreditCard,
    Settings,
    Smile,
    User,
} from 'lucide-vue-next'

import {
    CommandDialog,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
    CommandShortcut,
    useCommand,
} from '@/Components/ui/command'
import {
	DialogTitle,
} from '@/Components/ui/dialog';
import DialogDescription from './ui/dialog/DialogDescription.vue';
import { computed, ref } from 'vue';
import axios from 'axios';
import { watchDebounced } from '@vueuse/core';
import type { ListboxItemSelectEvent, AcceptableValue } from 'reka-ui';
import { Card } from './CardTable/columns';
import { Account } from './AccountTable/columns';

defineProps<{
    open: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

const search = ref('');

const result = ref<{
    accounts: Account[];
    cards: Card[];
}>({
    accounts: [],
    cards: [],
});

let searchRequestController: AbortController;

const handleSearch = async () => {
    if (searchRequestController) {
        searchRequestController.abort();
    }

    searchRequestController = new AbortController();
    const searchRequestSignal = searchRequestController.signal;

    try {
        const response = await axios.get(route('search', { search: search.value.trim() }), {
            signal: searchRequestSignal,
        });

        result.value = response.data;
    } catch (error) {
        // console.error(error);
    }
};

watchDebounced(search, handleSearch, { debounce: 50 });

const isEmpty = computed(() => {
    return result.value.accounts.length === 0 && result.value.cards.length === 0 && search.value.length > 0;
});

const handleSelect = (event: ListboxItemSelectEvent<AcceptableValue>) => {
    console.log(event);
};
</script>

<template>
    <CommandDialog :open="open" @update:open="emit('update:open', $event)" class="max-w-[450px]">
        <DialogTitle class="sr-only">{{ $t('Search') }}</DialogTitle>
        <DialogDescription class="sr-only">{{ $t('Search') }}</DialogDescription>

        <CommandInput v-model="search" :placeholder="$t('Type to search')" />

        <CommandList>
            <CommandEmpty v-if="isEmpty">{{ $t('No results found.') }}</CommandEmpty>

            <CommandGroup v-if="result.accounts.length > 0" :heading="$t('Accounts')">
                <CommandItem v-for="account in result.accounts" :key="account.id" :value="account.id" @select="handleSelect">
                    <img
                        v-if="account.bank"
                        :src="`https://jagastei.com.br.test/images/banks/${account.bank.code}.png`"
                        :alt="account.bank.long_name"
                        class="size-6 rounded-xl"
                        @error="(event: any) => {
                            event.target.src = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9Imx1Y2lkZSBsdWNpZGUtcGlnZ3ktYmFuay1pY29uIGx1Y2lkZS1waWdneS1iYW5rIj48cGF0aCBkPSJNMTEgMTdoM3YyYTEgMSAwIDAgMCAxIDFoMmExIDEgMCAwIDAgMS0xdi0zYTMuMTYgMy4xNiAwIDAgMCAyLTJoMWExIDEgMCAwIDAgMS0xdi0yYTEgMSAwIDAgMC0xLTFoLTFhNSA1IDAgMCAwLTItNFYzYTQgNCAwIDAgMC0zLjIgMS42bC0uMy40SDExYTYgNiAwIDAgMC02IDZ2MWE1IDUgMCAwIDAgMiA0djNhMSAxIDAgMCAwIDEgMWgyYTEgMSAwIDAgMCAxLTF6Ii8+PHBhdGggZD0iTTE2IDEwaC4wMSIvPjxwYXRoIGQ9Ik0yIDh2MWEyIDIgMCAwIDAgMiAyaDEiLz48L3N2Zz4=';
                        }"
                        />
                    <span>{{ account.name }}</span>
                </CommandItem>
            </CommandGroup>

            <CommandGroup v-if="result.cards.length > 0" :heading="$t('Cards')">
                <CommandItem v-for="card in result.cards" :key="card.id" :value="card.id" @select="handleSelect">
                    <img
                        v-if="card.account.bank"
                        :src="`https://jagastei.com.br.test/images/banks/${card.account.bank.code}.png`"
                        :alt="card.account.bank.long_name"
                        class="size-6 rounded-xl"
                        @error="(event: any) => {
                            event.target.src = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9Imx1Y2lkZSBsdWNpZGUtcGlnZ3ktYmFuay1pY29uIGx1Y2lkZS1waWdneS1iYW5rIj48cGF0aCBkPSJNMTEgMTdoM3YyYTEgMSAwIDAgMCAxIDFoMmExIDEgMCAwIDAgMS0xdi0zYTMuMTYgMy4xNiAwIDAgMCAyLTJoMWExIDEgMCAwIDAgMS0xdi0yYTEgMSAwIDAgMC0xLTFoLTFhNSA1IDAgMCAwLTItNFYzYTQgNCAwIDAgMC0zLjIgMS42bC0uMy40SDExYTYgNiAwIDAgMC02IDZ2MWE1IDUgMCAwIDAgMiA0djNhMSAxIDAgMCAwIDEgMWgyYTEgMSAwIDAgMCAxLTF6Ii8+PHBhdGggZD0iTTE2IDEwaC4wMSIvPjxwYXRoIGQ9Ik0yIDh2MWEyIDIgMCAwIDAgMiAyaDEiLz48L3N2Zz4=';
                        }"
                        />
                    <div>
                        <span>{{ card.name }}</span>
                        <span class="ml-2 font-medium text-xs text-muted-foreground">{{ card.account.bank.long_name }}</span>
                    </div>
                </CommandItem>
            </CommandGroup>

            <CommandSeparator />
        </CommandList>
    </CommandDialog>
</template>
