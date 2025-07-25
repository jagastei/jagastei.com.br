<script setup lang="ts">
import { ref, computed, useTemplateRef, watch } from 'vue';
import {
	CirclePlus,
	CheckIcon,
	ChevronsUpDown,
	Loader2,
} from 'lucide-vue-next';
import { cn } from '@/utils';
import { Button } from '@/Components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import {
	Command,
	CommandEmpty,
	CommandGroup,
	CommandInput,
	CommandItem,
	CommandList,
	CommandSeparator,
} from '@/Components/ui/command';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Wallet } from '@/types';
import InputError from './InputError.vue';
import { useTranslation } from 'i18next-vue';

type Group = {
	label: string;
	wallets: Wallet[];
};

const user = usePage().props.auth.user;
const { t } = useTranslation();

const groups = computed<Group[]>(() => [
	{
		label: t('Wallets'),
		wallets: user.wallets,
	},
]);

const popoverTrigger = useTemplateRef('popoverTrigger');

const open = ref(false);
const showNewWalletDialog = ref(false);
const selectedWallet = ref<Wallet>(user.current_wallet);

const form = useForm<{
	name: string | number;
}>({
	name: '',
});

const submit = () => {
	form.post(route('wallets.store'), {
		onSuccess: () => {
			showNewWalletDialog.value = false;
		},
		onError: (error) => {
			console.log('error: ', error);
		},
	});
};

const switchWallet = (wallet: Wallet) => {
	selectedWallet.value = wallet;
	open.value = false;

	router.put(
		route('wallets.switch'),
		{
			wallet_id: wallet.id,
		},
		{
			onSuccess: () => {
				if (route().current('wallets.show')) {
					window.location.reload();
				}
			},
		}
	);
};

const onClose = () => {
	form.reset();
	showNewWalletDialog.value = false;
};
</script>

<template>
	<Dialog v-model:open="showNewWalletDialog">
		<Popover v-model:open="open">
			<PopoverTrigger as-child>
				<Button
					ref="popoverTrigger"
					variant="outline"
					role="combobox"
					aria-expanded="open"
					aria-label="Select a wallet"
					:class="
						cn(
							'w-full md:w-[200px] justify-between py-0 pl-3 pr-2 h-10',
							$attrs.class ?? ''
						)
					"
				>
					{{ selectedWallet.name }}
					<ChevronsUpDown class="h-4 w-4" />
				</Button>
			</PopoverTrigger>
			<PopoverContent
				class="p-0"
				align="start"
				:style="{
					width: `${popoverTrigger?.$el.clientWidth + 2}px`,
				}"
			>
				<Command>
					<CommandList>
						<CommandGroup
							v-for="group in groups"
							:key="group.label"
							:heading="group.label"
						>
							<CommandItem
								v-for="wallet in group.wallets"
								:key="wallet.id"
								:value="wallet"
								class="text-sm"
								@select="switchWallet(wallet)"
							>
								<!-- <Avatar class="mr-2 h-5 w-5">
									<AvatarImage
										:src="`https://avatar.vercel.sh/${wallet.id}.png`"
										:alt="wallet.name"
										class="grayscale"
									/>
									<AvatarFallback>SC</AvatarFallback>
								</Avatar> -->
								{{ wallet.name }}
								<CheckIcon
									:class="
										cn(
											'ml-auto h-4 w-4',
											selectedWallet.id === wallet.id ? 'opacity-100' : 'opacity-0'
										)
									"
								/>
							</CommandItem>
						</CommandGroup>
					</CommandList>
					<CommandSeparator />
					<CommandList>
						<CommandGroup>
							<DialogTrigger as-child>
								<CommandItem
									value="create-wallet"
									@select="
										() => {
											open = false;
											showNewWalletDialog = true;
										}
									"
								>
									<CirclePlus class="mr-2 h-5 w-5" />
									{{ $t('Add wallet') }}
								</CommandItem>
							</DialogTrigger>
						</CommandGroup>
					</CommandList>
				</Command>
			</PopoverContent>
		</Popover>
		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Adicionar carteira</DialogTitle>
				<DialogDescription>
					Adicione uma nova carteira para gerenciar suas finanças.
				</DialogDescription>
			</DialogHeader>
			<div>
				<div class="space-y-4 py-2 pb-4">
					<div class="space-y-2">
						<Label for="name">Nome da carteira</Label>
						<Input id="name" v-model="form.name" placeholder="Carteira da empresa" />
						<InputError class="mt-2" :message="form.errors.name" />
					</div>
					<!-- <div class="space-y-2">
						<Label for="plan">Subscription plan</Label>
						<Select>
							<SelectTrigger>
								<SelectValue placeholder="Select a plan" />
							</SelectTrigger>
							<SelectContent>
								<SelectItem value="free">
									<span class="font-medium">Free</span> -
									<span class="text-muted-foreground"> Trial for two weeks </span>
								</SelectItem>
								<SelectItem value="pro">
									<span class="font-medium">Pro</span> -
									<span class="text-muted-foreground"> $9/month per user </span>
								</SelectItem>
							</SelectContent>
						</Select>
					</div> -->

					<p class="text-xs text-muted-foreground italic">
						Cada carteira adicional acarreta um aumento de R$ 9,90 no valor da
						assinatura.
					</p>
				</div>
			</div>
			<DialogFooter>
				<Button variant="outline" @click="showNewWalletDialog = false">
					Cancelar
				</Button>
				<Button :disabled="form.processing" @click="submit" type="button">
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Criar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
