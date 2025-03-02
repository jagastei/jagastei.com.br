<script setup lang="ts">
import { ref } from 'vue';

import { CirclePlusIcon, CheckIcon, ChevronsUpDown } from 'lucide-vue-next';

import { cn } from '@/utils';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
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
import {
	Select,
	SelectContent,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/Components/ui/select';

type Wallet = {
	label: string;
	value: string;
};

type Group = {
	label: string;
	wallets: Wallet[];
};

const groups: Group[] = [
	{
		label: 'Carteiras',
		wallets: [
			{
				label: 'Alicia Koch',
				value: 'personal',
			},
			{
				label: 'Acme Inc.',
				value: 'acme-inc',
			},
			{
				label: 'Monsters Inc.',
				value: 'monsters',
			},
		],
	},
];

const open = ref(false);
const showNewWalletDialog = ref(false);
const selectedWallet = ref<Wallet>(groups[0].wallets[0]);

const filter = (
	val: string[] | number[] | false[] | true[] | Record<string, any>[],
	term: string
): string[] | number[] | false[] | true[] | Record<string, any>[] => {
	const wallets = val as Wallet[];

	return wallets.filter((wallet) =>
		wallet.label?.toLowerCase().includes(term.toLowerCase())
	);
};
</script>

<template>
	<Dialog v-model:open="showNewWalletDialog">
		<Popover v-model:open="open">
			<PopoverTrigger as-child>
				<Button
					variant="outline"
					role="combobox"
					aria-expanded="open"
					aria-label="Select a wallet"
					:class="cn('w-[200px] justify-between py-0 px-2 h-10', $attrs.class ?? '')"
				>
					<!-- <Avatar class="mr-2 h-5 w-5">
						<AvatarImage
							:src="`https://avatar.vercel.sh/${selectedWallet.value}.png`"
							:alt="selectedWallet.label"
						/>
						<AvatarFallback>SC</AvatarFallback>
					</Avatar> -->
					{{ selectedWallet.label }}
					<ChevronsUpDown class="ml-auto h-4 w-4 shrink-0 opacity-50" />
				</Button>
			</PopoverTrigger>
			<PopoverContent class="w-[200px] p-0">
				<Command :filter-function="filter">
					<CommandList>
						<CommandGroup
							v-for="group in groups"
							:key="group.label"
							:heading="group.label"
						>
							<CommandItem
								v-for="wallet in group.wallets"
								:key="wallet.value"
								:value="wallet"
								class="text-sm"
								@select="
									() => {
										selectedWallet = wallet;
										open = false;
									}
								"
							>
								<!-- <Avatar class="mr-2 h-5 w-5">
									<AvatarImage
										:src="`https://avatar.vercel.sh/${wallet.value}.png`"
										:alt="wallet.label"
										class="grayscale"
									/>
									<AvatarFallback>SC</AvatarFallback>
								</Avatar> -->
								{{ wallet.label }}
								<CheckIcon
									:class="
										cn(
											'ml-auto h-4 w-4',
											selectedWallet.value === wallet.value ? 'opacity-100' : 'opacity-0'
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
									<CirclePlusIcon class="mr-2 h-5 w-5" />
									Adicionar carteira
								</CommandItem>
							</DialogTrigger>
						</CommandGroup>
					</CommandList>
				</Command>
			</PopoverContent>
		</Popover>
		<DialogContent>
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
						<Input id="name" placeholder="Carteira pessoal" />
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
				</div>
			</div>
			<DialogFooter>
				<Button variant="outline" @click="showNewWalletDialog = false">
					Cancelar
				</Button>
				<Button type="submit"> Criar </Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
