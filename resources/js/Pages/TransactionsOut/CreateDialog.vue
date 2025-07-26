<script lang="ts" setup>
import { ref, computed } from 'vue';
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
import { Account } from '@/Components/AccountTable/columns';
import { Category } from '@/Components/CategoryTable/columns';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';
import InputError from '@/Components/InputError.vue';
import SelectCategoryDialog from '@/Components/SelectCategoryDialog.vue';

const props = defineProps<{
	categories: Category[];
	accounts: Account[];
	open: boolean;
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
}

const form = useForm<{
	type: 'OUT';
	title: string;
	value: number;
	category: Category | undefined;
	account: Account | undefined;
}>({
	type: 'OUT',
	title: '',
	value: 0,
	category: undefined,
	account: undefined,
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
                        :link="false"
					/>

					<InputError class="mt-2" :message="form.errors.account" />
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
