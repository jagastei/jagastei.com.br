<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { useForm, usePage } from '@inertiajs/vue3';
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
import { Check, ChevronsUpDown, Loader2 } from 'lucide-vue-next';
import {
	Command,
	CommandEmpty,
	CommandGroup,
	CommandInput,
	CommandItem,
	CommandList,
} from '@/Components/ui/command';
import {
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Account, Bank } from '@/Components/AccountTable/columns';
import {
	Tooltip,
	TooltipContent,
	TooltipProvider,
	TooltipTrigger,
} from '@/Components/ui/tooltip';
import { TooltipArrow } from 'reka-ui';
import { currencyInputFormat } from '@/utils/currencyInputFormat';
import { useLanguageStore } from '@/stores/languageStore';

const props = defineProps<{
	banks: Bank[];
	open: boolean;
}>();

const emit = defineEmits(['close', 'success']);

const languageStore = useLanguageStore();
const user = usePage().props.auth.user;

const bankDialogOpen = ref(false);

const query = ref<string>('');

const { results } = useFuse(query, props.banks, {
	fuseOptions: {
		keys: ['code', 'short_name', 'long_name'],
		isCaseSensitive: false,
		threshold: 0.5,
	},
	resultLimit: 50,
	matchAllWhenSearchEmpty: true,
});

const resultList = computed(() => {
	return results.value?.map((r) => r.item);
});

const onSelected = (ev: any) => {
	form.bank = ev.detail.value as Bank;
	bankDialogOpen.value = false;
};

const form = useForm<{
	bank: Bank | undefined;
	name: string;
	initial_balance: number;
}>({
	bank: undefined,
	name: '',
	initial_balance: 0,
});

const submit = () => {
	form
		.transform((data) => ({
			...data,
			bank: data.bank?.id,
		}))
		.post(route('accounts.store'), {
			onSuccess: (res: any) => {
				console.log('onSuccess', res);
				emit('success', res.data as Account);
				form.reset();
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

					<Popover v-model:open="bankDialogOpen">
						<PopoverTrigger as-child>
							<Button
								tabindex="3"
								id="bank"
								variant="outline"
								role="combobox"
								class="w-[375px] justify-between mt-2 p-3"
							>
								<div class="flex items-center truncate">
									<div v-if="form.bank" class="min-w-4">
										<img
											:src="`https://jagastei.com.br.test/images/banks/${form.bank?.code}.png`"
											class="size-4 rounded-xl"
										/>
									</div>
									<span
										:class="[
											'truncate',
											{
												'ml-3': form.bank,
											},
										]"
										>{{ form.bank ? form.bank?.long_name : 'Escolha um banco' }}</span
									>
								</div>
								<ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
							</Button>
						</PopoverTrigger>
						<PopoverContent class="w-[375px] p-0">
							<Command v-model="form.bank" v-model:searchTerm="query">
								<CommandInput
									class="h-9"
									placeholder="Buscar"
									name="query"
									autocomplete="off"
								/>

								<CommandEmpty>
									<template v-if="query.length > 0">Nenhum banco encontrado.</template>
									<template v-else>Informe o nome ou código do banco.</template>
								</CommandEmpty>

								<CommandList>
									<CommandGroup>
										<CommandItem
											v-for="bank in resultList"
											:key="bank.id"
											:value="bank"
											@select="onSelected"
											class="flex"
										>
											<div class="min-w-4">
												<img
													:src="`https://jagastei.com.br.test/images/banks/${bank.code}.png`"
													class="size-4 rounded-xl"
												/>
											</div>
											<span class="ml-1 block truncate">
												{{ bank.code }} - {{ bank.long_name }}</span
											>
											<Check
												:class="
													cn(
														'ml-auto h-4 w-4',
														form.bank?.id === bank.id ? 'opacity-100' : 'opacity-0'
													)
												"
											/>
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
