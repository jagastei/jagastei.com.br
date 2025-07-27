<script setup lang="ts">
import {
	Dialog,
	DialogContent,
	DialogHeader,
	DialogTitle,
	DialogDescription,
	DialogFooter,
} from '@/Components/ui/dialog';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Loader2 } from 'lucide-vue-next';
import useAxios from '@/composables/useAxios';
import { Account, Bank } from './AccountTable/columns';
import SelectBankDialog from './SelectBankDialog.vue';
import { TooltipProvider } from './ui/tooltip';
import { Tooltip } from './ui/tooltip';
import { TooltipTrigger } from './ui/tooltip';
import { useLanguageStore } from '@/stores/languageStore';
import { usePage } from '@inertiajs/vue3';
import { currencyInputFormat } from '@/utils/currencyInputFormat';

defineProps<{
	banks: Bank[];
	open: boolean;
}>();

const emit = defineEmits(['close', 'success']);

const languageStore = useLanguageStore();
const user = usePage().props.auth.user;

const form: any = useAxios({
	bank: undefined,
    name: '',
    initial_balance: 0,
});

const submit = () => {
	form.transform((data: any) => ({
		...data,
		bank: data.bank?.id,
	})).post(route('api.accounts.store'), {
		onSuccess: (res: any) => {
			emit('success', res.data as Account);
			form.reset();
		},
		onError: (errors: any) => {
			console.log(errors);
		},
	});
};

const onClose = () => {
	form.reset();
	emit('close');
};
</script>

<template>
	<Dialog
		:open="open"
		@update:open="onClose"
	>
		<DialogContent class="sm:max-w-[425px]">
			<DialogHeader>
				<DialogTitle>Adicionar conta</DialogTitle>
				<DialogDescription>
				    Identifique sua conta bancária e clique em adicionar.
				</DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Conta principal"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="initial_balance" class="text-left"> Saldo inicial </Label>
					<Input
						id="initial_balance"
						v-model.lazy="form.initial_balance"
						class="mt-2"
						v-money3="
							currencyInputFormat(
								user.current_wallet?.currency,
								languageStore.getCurrentLanguage
							)
						"
						tabindex="2"
					/>
				</div>

				<div class="flex flex-col">
					<div class="flex items-center">
						<Label for="bank">Banco</Label>

						<TooltipProvider>
							<Tooltip>
								<TooltipTrigger as-child>
									<button
										@click="$emitter.emit('open-support-dialog')"
										class="ml-auto inline-block text-sm underline"
									>
										Não encontrou seu banco?
									</button>
								</TooltipTrigger>
								<TooltipContent>
									Envie uma mensagem solicitando o cadastro do seu banco.
									<TooltipArrow class="fill-border" />
								</TooltipContent>
							</Tooltip>
						</TooltipProvider>
					</div>

					<SelectBankDialog
						v-model="form.bank"
						:banks="banks"
					/>
				</div>
			</div>
			<DialogFooter>
				<Button
					:disabled="form.processing"
					@click="submit"
					type="button"
					tabindex="3"
				>
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Adicionar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
