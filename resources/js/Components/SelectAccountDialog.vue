<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { cn } from '@/utils';
import {
	Check,
	ChevronsUpDown,
	CirclePlus,
	ExternalLink,
} from 'lucide-vue-next';
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
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Account } from '@/Components/AccountTable/columns';
import { useVModel } from '@vueuse/core';

const propsWithDefaults = withDefaults(
	defineProps<{
		id: string;
		modelValue: Account | undefined;
		accounts: Account[];
		disabled?: boolean;
	}>(),
	{
		disabled: false,
	}
);

const emits = defineEmits<{
	(e: 'update:modelValue', payload: Account | undefined): void;
	(e: 'close'): void;
}>();

const modelValue = useVModel(propsWithDefaults, 'modelValue', emits, {
	passive: true,
	defaultValue: propsWithDefaults.modelValue,
});

const dialogOpen = ref(false);

const query = ref<string>('');

const { results } = useFuse(query, propsWithDefaults.accounts, {
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
</script>

<template>
	<Popover v-model:open="dialogOpen">
		<PopoverTrigger as-child>
			<Button
				tabindex="3"
				:id="id"
				variant="outline"
				role="combobox"
				class="w-[375px] justify-between mt-2 p-3 disabled:opacity-100"
				:disabled="disabled"
			>
				<div class="flex items-center truncate">
					<div v-if="modelValue" class="min-w-4">
						<img
							:src="`https://jagastei.com.br.test/images/banks/${modelValue.bank.code}.png`"
							class="size-4 rounded-xl"
						/>
					</div>
					<span
						:class="[
							'truncate',
							{
								'ml-3': modelValue,
							},
						]"
						>{{ modelValue ? modelValue?.name : 'Escolha uma conta' }}</span
					>
				</div>

				<ChevronsUpDown v-if="!disabled" class="ml-2 size-4 shrink-0 opacity-50" />
			</Button>

			<a
				v-if="modelValue"
				:href="route('accounts.show', modelValue.id)"
				target="_blank"
				class="text-blue-500 absolute right-0 top-0"
			>
				<ExternalLink class="ml-2 size-4 shrink-0" />
			</a>
		</PopoverTrigger>
		<PopoverContent class="w-[375px] p-0">
			<Command v-model="modelValue" v-model:searchTerm="query">
				<CommandInput
					class="h-9"
					placeholder="Buscar"
					name="query"
					autocomplete="off"
				/>

				<CommandEmpty>
					<template v-if="query.length > 0">Nenhuma conta encontrada.</template>
					<template v-else>Informe o nome da conta.</template>
				</CommandEmpty>

				<CommandList>
					<CommandGroup>
						<CommandItem
							v-for="account in resultList"
							:key="account.id"
							:value="account"
							@select="
								() => {
									dialogOpen = false;
								}
							"
							class="flex"
						>
							<div class="min-w-4">
								<img
									:src="`https://jagastei.com.br.test/images/banks/${account.bank.code}.png`"
									class="size-4 rounded-xl"
								/>
							</div>
							<span class="ml-1 block truncate">{{ account.name }}</span>
							<Check
								:class="
									cn(
										'ml-auto h-4 w-4',
										modelValue?.id === account.id ? 'opacity-100' : 'opacity-0'
									)
								"
							/>
						</CommandItem>
					</CommandGroup>
				</CommandList>
				<CommandSeparator />
				<CommandList>
					<CommandGroup>
						<!-- <DialogTrigger as-child> -->
						<CommandItem value="create-category">
							<CirclePlus class="mr-2 h-5 w-5" />
							Adicionar conta
						</CommandItem>
						<!-- </DialogTrigger> -->
					</CommandGroup>
				</CommandList>
			</Command>
		</PopoverContent>
	</Popover>
</template>
