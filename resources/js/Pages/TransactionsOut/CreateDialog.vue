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
import { Account } from '@/Components/AccountTable/columns';
import { Category } from '@/Components/CategoryTable/columns';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';

const props = defineProps<{
	categories: Category[];
	accounts: Account[];
	open: boolean;
}>();

const emit = defineEmits(['close']);

const categoryDialogOpen = ref(false);

const query = ref<string>('');

const { results } = useFuse(query, props.categories, {
	fuseOptions: {
		keys: ['name'],
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
	form.category = ev.detail.value as Category;
	categoryDialogOpen.value = false;
};

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
	<Dialog :open="open">
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

					<Popover v-model:open="categoryDialogOpen">
						<PopoverTrigger as-child>
							<Button
								tabindex="3"
								id="category"
								variant="outline"
								role="combobox"
								class="w-[375px] justify-between mt-2 p-3"
							>
								<div class="flex items-center truncate">
									<div
										v-if="form.category"
										class="block size-6 rounded-xl"
										:style="{ backgroundColor: form.category.color ?? '#000000' }"
									></div>
									<span
										:class="[
											'truncate',
											{
												'ml-3': form.category,
											},
										]"
										>{{
											form.category ? form.category?.name : 'Escolha uma categoria'
										}}</span
									>
								</div>
								<ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
							</Button>
						</PopoverTrigger>
						<PopoverContent class="w-[375px] p-0">
							<Command v-model="form.category" v-model:searchTerm="query">
								<CommandInput
									class="h-9"
									placeholder="Buscar"
									name="query"
									autocomplete="off"
								/>

								<CommandEmpty>
									<template v-if="query.length > 0"
										>Nenhuma categoria encontrada.</template
									>
									<template v-else>Informe o nome da categoria.</template>
								</CommandEmpty>

								<CommandList>
									<CommandGroup>
										<CommandItem
											v-for="category in resultList"
											:key="category.id"
											:value="category"
											@select="onSelected"
											class="flex"
										>
											<div
												class="block size-6 rounded-xl"
												:style="{ backgroundColor: category.color ?? '#000000' }"
											></div>
											<span class="ml-3 block truncate">{{ category.name }}</span>
											<Check
												:class="
													cn(
														'ml-auto h-4 w-4',
														form.category?.id === category.id ? 'opacity-100' : 'opacity-0'
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

				<div class="flex flex-col">
					<Label for="account">Conta</Label>

					<SelectAccountDialog v-model="form.account" :accounts="accounts" />
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
