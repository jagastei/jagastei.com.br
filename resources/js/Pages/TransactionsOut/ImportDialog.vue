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
import UploadFile from '@/Components/UploadFile.vue';

interface AI {
	empresa: string;
	titulo: string;
	descricao: string;
	itens: {
		descricao: string;
		quantidade: number;
		valor: number;
		total: number;
		categoria: string;
	};
	total: number;
	data: string;
	localizacao: string;
	metodo_pagamento: string;
	metodo_pagamento_sugerido: string;
	categoria: string;
	categoria_sugerida: string;
}

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

const selectCategoryByName = (name: string) => {
	form.category = props.categories.find((c) => c.name === name);
};

const uploadForm = useForm<{
	files: File[];
}>({
	files: [],
});

const ai = ref<AI | null>(null);
const file_path = ref<string | null>(null);

const handleUpload = () => {
	uploadForm.post(route('transactions.out.import'), {
		preserveScroll: true,
		preserveState: true,
		forceFormData: true,
		onSuccess: (response) => {
			uploadForm.files = [];

			ai.value = response.props.ai as AI;
			file_path.value = response.props.file_path as string | null;

			form.title = ai.value.titulo as string;
			form.value = ai.value.total as number;
			selectCategoryByName(ai.value.categoria as string);
			form.datetime = ai.value.data as string;
		},
	});
};

const form = useForm<{
	type: 'OUT';
	title: string;
	value: number;
	category: Category | undefined;
	account: Account | undefined;
	datetime: string | undefined;
}>({
	type: 'OUT',
	title: '',
	value: 0,
	category: undefined,
	account: undefined,
	datetime: undefined,
});

const submit = () => {
	form
		.transform((data) => ({
			...data,
			category: data.category?.id,
			account: data.account?.id,
			ai: ai.value,
			file_path: file_path.value,
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
	<Dialog v-if="!ai" :open="open">
		<DialogTrigger as-child>
			<slot />
		</DialogTrigger>

		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Importar registro</DialogTitle>
				<DialogDescription>
					Faça o upload de uma notinha fiscal.
				</DialogDescription>
			</DialogHeader>

			<div class="py-2">
				<UploadFile
					v-model="uploadForm.files"
					:preview-max-height="319"
					:loading="uploadForm.processing"
				/>
			</div>
			<DialogFooter>
				<Button
					v-if="!uploadForm.processing && uploadForm.files.length > 0"
					variant="outline"
					@click="uploadForm.files = []"
				>
					Usar outra imagem
				</Button>

				<Button
					type="submit"
					@click="handleUpload"
					:disabled="uploadForm.files.length === 0 || uploadForm.processing"
				>
					<Loader2 v-if="uploadForm.processing" class="size-4 mr-2 animate-spin" />
					Enviar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>

	<Dialog v-else :open="open">
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

					<SelectAccountDialog
						id="account"
						v-model="form.account"
						:accounts="accounts"
						:banks="[]"
					/>
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
