<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { useForm } from '@inertiajs/vue3';
import { cn } from '@/utils';
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
import { Brand } from '@/Components/CardTable/columns';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps<{
	brands: Brand[];
	accounts: Account[];
	open: boolean;
}>();

const emit = defineEmits(['close']);

const form = useForm<{
	account: Account | undefined;
	name: string;
	limit: number;
}>({
	account: undefined,
	name: '',
	limit: 0,
});

const submit = () => {
	form
		.transform((data) => ({
			...data,
			account: data.account?.id,
		}))
		.post(route('cards.store'), {
			onFinish: () => {
				onClose();
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
				<DialogTitle>Adicionar cartão</DialogTitle>
				<DialogDescription>
					Identifique seu cartão e clique em adicionar.
				</DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Cartão principal"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="limit" class="text-left"> Limite </Label>

					<Input
						id="limit"
						v-model.lazy="form.limit"
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
					<Label for="account">Conta</Label>
					<SelectAccountDialog v-model="form.account" :accounts="accounts" />

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
