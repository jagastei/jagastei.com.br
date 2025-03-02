<script setup lang="ts">
import type { Column } from '@tanstack/vue-table';
import type { Component } from 'vue';
import { computed, ref } from 'vue';
import type { Transaction } from './columns';
import { Icon } from '@iconify/vue';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
	Command,
	CommandEmpty,
	CommandGroup,
	CommandInput,
	CommandItem,
	CommandList,
	CommandSeparator,
} from '@/Components/ui/command';
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
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { Separator } from '@/Components/ui/separator';
import { cn } from '@/utils';
import { CirclePlusIcon, Loader2 } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import Label from '../ui/label/Label.vue';
import Input from '../ui/input/Input.vue';

interface DataTableFacetedFilter {
	column?: Column<Transaction, any>;
	title?: string;
	options: {
		label: string;
		value: string;
		icon?: Component;
	}[];
}

const props = defineProps<DataTableFacetedFilter>();

const open = ref(false);

const facets = computed(() => props.column?.getFacetedUniqueValues());
const selectedValues = computed(
	() => new Set(props.column?.getFilterValue() as string[])
);

const filterFn = (
	val: string[] | number[] | false[] | true[] | Record<string, any>[],
	term: string
): string[] | number[] | false[] | true[] | Record<string, any>[] => {
	const options = val as DataTableFacetedFilter['options'];

	return options.filter((option) =>
		option.label?.toLowerCase().includes(term.toLowerCase())
	);
};

const form = useForm<{
	name: string;
	color: string;
	type: string;
}>({
	name: '',
	color: '#22C55E',
	type: 'IN',
});

const submit = () => {
	form.post(route('categories.store'), {
		onSuccess: () => {
			form.reset();
			open.value = false;
		},
		onError: (error) => {
			console.log(error);
		},
	});
};

const onClose = () => {
	form.reset();
	open.value = false;
};
</script>

<template>
	<Dialog :open="open">
		<Popover>
			<PopoverTrigger as-child>
				<Button variant="outline" size="sm" class="h-8 border-dashed">
					<Icon icon="radix-icons:plus-circled" class="mr-2 h-4 w-4" />
					{{ title }}
					<template v-if="selectedValues.size > 0">
						<Separator orientation="vertical" class="mx-2 h-4" />
						<Badge variant="secondary" class="rounded-sm px-1 font-normal lg:hidden">
							{{ selectedValues.size }}
						</Badge>
						<div class="hidden space-x-1 lg:flex">
							<Badge
								v-if="selectedValues.size > 2"
								variant="secondary"
								class="rounded-sm px-1 font-normal"
							>
								{{ selectedValues.size }} selecionados
							</Badge>

							<template v-else>
								<Badge
									v-for="option in options.filter((option) =>
										selectedValues.has(option.value)
									)"
									:key="option.value"
									variant="secondary"
									class="rounded-sm px-1 font-normal"
								>
									{{ option.label }}
								</Badge>
							</template>
						</div>
					</template>
				</Button>
			</PopoverTrigger>
			<PopoverContent class="w-[200px] p-0" align="start">
				<Command :filter-function="filterFn">
					<CommandInput :placeholder="title" />
					<CommandList>
						<CommandEmpty>Nenhum resultado.</CommandEmpty>
						<CommandGroup>
							<CommandItem
								v-for="option in options"
								:key="option.value"
								:value="option"
								@select="
									(e) => {
										const isSelected = selectedValues.has(option.value);
										if (isSelected) {
											selectedValues.delete(option.value);
										} else {
											selectedValues.add(option.value);
										}
										const filterValues = Array.from(selectedValues);
										column?.setFilterValue(
											filterValues.length ? filterValues : undefined
										);
									}
								"
							>
								<div
									:class="
										cn(
											'mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary',
											selectedValues.has(option.value)
												? 'bg-primary text-primary-foreground'
												: 'opacity-50 [&_svg]:invisible'
										)
									"
								>
									<Icon icon="radix-icons:check" :class="cn('h-4 w-4')" />
								</div>
								<component
									:is="option.icon"
									v-if="option.icon"
									class="mr-2 h-4 w-4 text-muted-foreground"
								/>
								<span>{{ option.label }}</span>
								<span
									v-if="facets?.get(option.value)"
									class="ml-auto flex h-4 w-4 items-center justify-center font-mono text-xs"
								>
									{{ facets.get(option.value) }}
								</span>
							</CommandItem>
						</CommandGroup>

						<template v-if="selectedValues.size > 0">
							<CommandSeparator />
							<CommandGroup>
								<CommandItem
									:value="{ label: 'Limpar filtros' }"
									class="justify-center text-center"
									@select="column?.setFilterValue(undefined)"
								>
									Limpar filtros
								</CommandItem>
							</CommandGroup>
						</template>
					</CommandList>
					<CommandSeparator />
					<CommandList>
						<CommandGroup>
							<DialogTrigger as-child>
								<CommandItem
									value="create-category"
									@select="
										() => {
											open = true;
										}
									"
								>
									<CirclePlusIcon class="mr-2 h-5 w-5" />
									Adicionar categoria
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
				<DialogTitle>Adicionar categoria</DialogTitle>
				<DialogDescription>
					Adicione uma nova categoria para gerenciar suas entradas.
				</DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Alimentação"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="color" class="text-left w-fit"> Cor </Label>
					<Input
						id="color"
						type="color"
						v-model="form.color"
						class="mt-2 cursor-pointer"
						tabindex="2"
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
