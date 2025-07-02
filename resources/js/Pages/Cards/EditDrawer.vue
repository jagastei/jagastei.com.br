<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Brand, Card } from '@/Components/CardTable/columns';
import {
	Sheet,
	SheetContent,
	SheetDescription,
	SheetHeader,
	SheetTitle,
	SheetTrigger,
	SheetFooter,
	SheetClose,
} from '@/Components/ui/sheet';
import { currencyInputFormat } from '@/utils/currencyInputFormat';
import { useLanguageStore } from '@/stores/languageStore';
import { useTranslation } from 'i18next-vue';
import { vMaska } from 'maska/vue';
import {
	Tooltip,
	TooltipContent,
	TooltipProvider,
	TooltipTrigger,
} from '@/Components/ui/tooltip';
import { TooltipArrow } from 'reka-ui';
import {
	Select,
	SelectContent,
	SelectGroup,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/Components/ui/select';
import { findCardBrand } from '@/utils/findCardBrand';
import SelectBrandDialog from '@/Components/SelectBrandDialog.vue';
import InputError from '@/Components/InputError.vue';
import { Checkbox } from '@/Components/ui/checkbox';
import { Separator } from '@/Components/ui/separator';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';
import type { Account } from '@/Components/AccountTable/columns';

const props = defineProps<{
	brands: Brand[];
	accounts: Account[];
	card: Card;
	open: boolean;
}>();

const emit = defineEmits(['close']);

const languageStore = useLanguageStore();
const user = usePage().props.auth.user;

const { t } = useTranslation();

const months = [
	'January',
	'February',
	'March',
	'April',
	'May',
	'June',
	'July',
	'August',
	'September',
	'October',
	'November',
	'December',
];

const years = Array.from(
	{ length: 15 },
	(_, i) => new Date().getFullYear() + i
);

const form = useForm<{
	name: string;
	limit: number;
	digits: string;
	brand: Brand | undefined;
	expiration_month: number | null;
	expiration_year: number | null;
	credit: boolean;
	virtual: boolean;
	international: boolean;
}>({
	name: props.card.name,
	limit: props.card.limit,
	digits: props.card.digits,
	brand: props.card.brand,
	expiration_month: props.card.expiration_month,
	expiration_year: props.card.expiration_year,
	credit: props.card.credit,
	virtual: props.card.virtual,
	international: props.card.international,
});

const submit = () => {
	form.transform((data) => ({
		...data,
		brand_id: data.brand?.id,
	})).put(route('cards.update', props.card.id), {
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
	<Sheet :open="open" @update:open="onClose">
		<SheetTrigger as-child>
			<slot />
		</SheetTrigger>

		<SheetContent class="sm:max-w-[calc(375px+48px)] flex flex-col">
			<SheetHeader>
				<SheetTitle>Editar cartão</SheetTitle>
				<SheetDescription>Edite as informações do cartão.</SheetDescription>
			</SheetHeader>

			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Cartão principal"
						class="mt-2"
						autocomplete="off"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="limit" class="text-left"> Limite </Label>

					<Input
						id="limit"
						v-model.lazy="form.limit"
						class="mt-2"
						v-money3="
							currencyInputFormat(
								user.current_wallet?.currency,
								languageStore.getCurrentLanguage
							)
						"
					/>
				</div>

				<div class="flex flex-col relative">
					<Label for="account">Conta</Label>
					<SelectAccountDialog
						id="account"
						v-model="card.account"
						:accounts="accounts"
						:disabled="true"
					/>
				</div>

				<Separator class="my-4 w-4" />

				<div class="flex flex-col">
					<Label for="digits" class="text-left"> Número </Label>
					<Input
						id="digits"
						v-model="form.digits"
						placeholder="1234 5678 9012 3456"
						class="mt-2"
						autocomplete="off"
						tabindex="3"
						v-maska="'#### #### #### ####'"
					/>
				</div>

				<div class="flex flex-col">
					<div class="flex items-center">
						<Label for="brand">Bandeira</Label>

						<TooltipProvider>
							<Tooltip>
								<TooltipTrigger as-child>
									<button
										@click="$emitter.emit('open-support-dialog')"
										class="ml-auto inline-block text-sm underline"
									>
										Não encontrou a bandeira do seu cartão?
									</button>
								</TooltipTrigger>
								<TooltipContent class="max-w-[200px]">
									Envie uma mensagem solicitando o cadastro da bandeira do seu cartão.
									<TooltipArrow class="fill-border" />
								</TooltipContent>
							</Tooltip>
						</TooltipProvider>
					</div>

					<SelectBrandDialog
						v-model="form.brand"
						:brands="brands"
						:suggested-brand-identifier="findCardBrand(form.digits)"
					/>

					<InputError class="mt-2" :message="form.errors.brand" />
				</div>

				<fieldset class="flex flex-col">
					<legend
						class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-left"
					>
						Data de validade
					</legend>

					<div class="mt-2 flex items-center gap-2">
						<Select v-model="form.expiration_month">
							<SelectTrigger>
								<SelectValue placeholder="Mês" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem
										v-for="(month, index) in months"
										:key="index"
										:value="index + 1"
									>
										{{ t(month) }}
									</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>

						<Select v-model="form.expiration_year">
							<SelectTrigger>
								<SelectValue placeholder="Ano" />
							</SelectTrigger>
							<SelectContent>
								<SelectGroup>
									<SelectItem
										v-for="(year, index) in years"
										:key="index"
										:value="years[index]"
									>
										{{ year }}
									</SelectItem>
								</SelectGroup>
							</SelectContent>
						</Select>
					</div>
				</fieldset>

				<div>
					<div class="flex items-center space-x-2">
						<Checkbox id="credit" v-model="form.credit" />
						<Label for="credit"> Crédito </Label>
					</div>
				</div>

				<div>
					<div class="flex items-center space-x-2">
						<Checkbox id="virtual" v-model="form.virtual" />
						<Label for="virtual"> Virtual </Label>
					</div>
				</div>

				<div>
					<div class="flex items-center space-x-2">
						<Checkbox id="international" v-model="form.international" />
						<Label for="international"> Internacional </Label>
					</div>
				</div>
			</div>

			<SheetFooter class="mt-auto">
				<Button variant="outline" @click="onClose" type="button"> Cancelar </Button>

				<Button :disabled="form.processing" @click="submit" type="button">
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Salvar
				</Button>
			</SheetFooter>
		</SheetContent>
	</Sheet>
</template>
