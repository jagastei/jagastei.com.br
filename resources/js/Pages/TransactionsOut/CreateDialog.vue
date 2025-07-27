<script lang="ts" setup>
import { ref, computed, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { Loader2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Account, Bank } from '@/Components/AccountTable/columns';
import { Category } from '@/Components/CategoryTable/columns';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';
import InputError from '@/Components/InputError.vue';
import SelectCategoryDialog from '@/Components/SelectCategoryDialog.vue';
import { cn } from '@/utils';
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { Command, CommandGroup, CommandItem, CommandList } from '@/Components/ui/command';
import { Check, ChevronsUpDown } from 'lucide-vue-next';
import { Card } from '@/Components/CardTable/columns';
import SelectCardDialog from '@/Components/SelectCardDialog.vue';

const props = defineProps<{
	categories: Category[];
	accounts: Account[];
	open: boolean;
	cards: Card[];
	banks: Bank[];
}>();

const emit = defineEmits(['close']);

const onCategoryCreated = (category: Category) => {
	router.visit(route('transactions.out.index'), {
		only: ['categories'],
		preserveState: true,
		preserveScroll: true,
		onSuccess: () => {
			form.category = category;
		},
	});
};

const onAccountCreated = (account: Account) => {
	router.visit(route('transactions.out.index'), {
		only: ['accounts'],
		preserveState: true,
		preserveScroll: true,
		onSuccess: () => {
			form.account = account;
		},
	});
};

const methods = {
    CASH: 'Dinheiro',
    CARD: 'Cartão',
    TED: 'TED',
    PIX: 'PIX',
    OTHER: 'Outro',
    // UNKNOWN: 'Desconhecido',
} as const;

const form = useForm<{
	type: 'OUT';
	title: string;
	value: number;
	category: Category | undefined;
	account: Account | undefined;
	method: keyof typeof methods;
    card: Card | undefined;
}>({
	type: 'OUT',
	title: '',
	value: 0,
	category: undefined,
	account: undefined,
    method: 'CASH',
    card: undefined,
});

const submit = () => {
	form
		.transform((data) => ({
			...data,
			category: data.category?.id,
			account: data.account?.id,
		}))
		.post(route('transactions.out.store'), {
			onSuccess: () => {
				form.reset();
				emit('close');
			},
			onError: (error) => {
				console.log(error);
			},
		});
};

const onClose = () => {
	form.reset();
	emit('close');
};

const methodDialogOpen = ref(false);

const cardsOfAccount = computed(() => {
    if (!form.account) {
        return props.cards;
    }

    const _cards = props.cards.filter((card) => card.account.id === form.account?.id);

    if (_cards.length === 1) {
        form.card = _cards[0];
    }

    return _cards;
})

watch(() => form.account, () => {
    form.card = undefined;
});

watch(() => form.method, () => {
    if (form.method !== 'CARD') {
        form.card = undefined;
    }
});
</script>

<template>
	<Dialog :open="open" @update:open="onClose">
		<DialogTrigger as-child>
			<slot />
		</DialogTrigger>

		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Adicionar saída</DialogTitle>
				<DialogDescription> Informe o valor e a categoria. </DialogDescription>
			</DialogHeader>

			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="title" class="text-left"> Nome </Label>
					<Input
						id="title"
						v-model="form.title"
						placeholder="Pagamento de conta"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="value" class="text-left"> Valor </Label>

					<Input
						id="value"
						v-model.lazy="form.value"
						class="mt-2"
						v-money3="{
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
						}"
						tabindex="2"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="category">Categoria</Label>

					<SelectCategoryDialog
						v-model="form.category"
						:categories="categories"
						@categoryCreated="onCategoryCreated"
					/>

					<InputError class="mt-2" :message="form.errors.category" />
				</div>

				<div class="flex flex-col">
					<Label for="account">Conta</Label>

					<SelectAccountDialog
						id="account"
						v-model="form.account"
						:accounts="accounts"
						:banks="banks"
                        :link="false"
                        @accountCreated="onAccountCreated"
					/>

					<InputError class="mt-2" :message="form.errors.account" />
				</div>

                <div class="flex flex-col">
					<Label for="method">Método</Label>

					<Popover v-model:open="methodDialogOpen">
                        <PopoverTrigger as-child>
                            <Button
                                tabindex="3"
                                id="method"
                                variant="outline"
                                role="combobox"
                                class="w-[375px] justify-between mt-2 p-3"
                            >
                                <div class="flex items-center truncate">
                                    <span class="truncate">{{ form.method ? methods[form.method] : 'Escolha um método' }}</span>
                                </div>
                                <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                            </Button>
                        </PopoverTrigger>

                        <PopoverContent class="w-[375px] p-0">
                            <Command v-model="form.method">
                                <CommandList>
                                    <CommandGroup>
                                        <CommandItem
                                            v-for="(value, key) in methods"
                                            :key="key"
                                            :value="key"
                                            @select="() => {
                                                if(form.method === key) return;
                                                methodDialogOpen = false;
                                            }"
                                            class="flex"
                                        >
                                            <span class="block truncate">{{ value }}</span>
                                            <Check
                                                :class="
                                                    cn(
                                                        'ml-auto size-4',
                                                        form.method === key ? 'opacity-100' : 'opacity-0'
                                                    )
                                                "
                                            />
                                        </CommandItem>
                                    </CommandGroup>
                                </CommandList>
                            </Command>
                        </PopoverContent>
                    </Popover>

					<InputError class="mt-2" :message="form.errors.method" />
				</div>

                <div v-if="form.method === 'CARD'" class="flex flex-col">
					<Label for="card">Cartão</Label>

					<SelectCardDialog
						id="card"
						v-model="form.card"
						:cards="cardsOfAccount"
						:link="false"
					/>

					<InputError class="mt-2" :message="form.errors.card" />
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
