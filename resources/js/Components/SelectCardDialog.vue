<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { cn } from '@/utils';
import { Check, ChevronsUpDown, CirclePlus } from 'lucide-vue-next';
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
import { useVModel } from '@vueuse/core';
import { Card } from './CardTable/columns';

const props = defineProps<{
    id: string;
    modelValue: Card | undefined;
    cards: Card[];
}>();

const emits = defineEmits<{
	(e: 'update:modelValue', payload: Card | undefined): void;
	(e: 'close'): void;
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
	passive: true,
	defaultValue: props.modelValue,
});

const dialogOpen = ref(false);

const query = ref<string>('');

const cards = computed(() => {
    return props.cards;
})

const { results } = useFuse(query, cards, {
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
			>
				<div class="flex items-center truncate">
					<div v-if="modelValue" class="min-w-4">
						<img
							:src="`https://jagastei.com.br.test/images/banks/${modelValue.account.bank.code}.png`"
							class="size-4 rounded-xl"
							@error="
								(event: any) => {
									event.target.style.background = '#fff';
									event.target.src =
										'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9Imx1Y2lkZSBsdWNpZGUtcGlnZ3ktYmFuay1pY29uIGx1Y2lkZS1waWdneS1iYW5rIj48cGF0aCBkPSJNMTEgMTdoM3YyYTEgMSAwIDAgMCAxIDFoMmExIDEgMCAwIDAgMS0xdi0zYTMuMTYgMy4xNiAwIDAgMCAyLTJoMWExIDEgMCAwIDAgMS0xdi0yYTEgMSAwIDAgMC0xLTFoLTFhNSA1IDAgMCAwLTItNFYzYTQgNCAwIDAgMC0zLjIgMS42bC0uMy40SDExYTYgNiAwIDAgMC02IDZ2MWE1IDUgMCAwIDAgMiA0djNhMSAxIDAgMCAwIDEgMWgyYTEgMSAwIDAgMCAxLTF6Ii8+PHBhdGggZD0iTTE2IDEwaC4wMSIvPjxwYXRoIGQ9Ik0yIDh2MWEyIDIgMCAwIDAgMiAyaDEiLz48L3N2Zz4=';
								}
							"
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

				<ChevronsUpDown class="ml-2 size-4 shrink-0 opacity-50" />
			</Button>
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
					<template v-if="query.length > 0">Nenhum cartão encontrado.</template>
					<template v-else>Informe o nome do cartão.</template>
				</CommandEmpty>

				<CommandList>
					<CommandGroup>
						<CommandItem
							v-for="card in resultList"
							:key="card.id"
							:value="card"
							@select="
								() => {
									dialogOpen = false;
								}
							"
							class="flex"
						>
							<div class="min-w-4">
								<img
									:src="`https://jagastei.com.br.test/images/banks/${card.account.bank.code}.png`"
									class="size-4 rounded-xl"
									@error="
										(event: any) => {
											event.target.style.background = '#fff';
											event.target.src =
												'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9ImN1cnJlbnRDb2xvciIgc3Ryb2tlLXdpZHRoPSIxLjUiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgY2xhc3M9Imx1Y2lkZSBsdWNpZGUtcGlnZ3ktYmFuay1pY29uIGx1Y2lkZS1waWdneS1iYW5rIj48cGF0aCBkPSJNMTEgMTdoM3YyYTEgMSAwIDAgMCAxIDFoMmExIDEgMCAwIDAgMS0xdi0zYTMuMTYgMy4xNiAwIDAgMCAyLTJoMWExIDEgMCAwIDAgMS0xdi0yYTEgMSAwIDAgMC0xLTFoLTFhNSA1IDAgMCAwLTItNFYzYTQgNCAwIDAgMC0zLjIgMS42bC0uMy40SDExYTYgNiAwIDAgMC02IDZ2MWE1IDUgMCAwIDAgMiA0djNhMSAxIDAgMCAwIDEgMWgyYTEgMSAwIDAgMCAxLTF6Ii8+PHBhdGggZD0iTTE2IDEwaC4wMSIvPjxwYXRoIGQ9Ik0yIDh2MWEyIDIgMCAwIDAgMiAyaDEiLz48L3N2Zz4=';
										}
									"
								/>
							</div>
							<span class="ml-1 block truncate">{{ card.name }}</span>
							<Check
								:class="
									cn(
										'ml-auto h-4 w-4',
										modelValue?.id === card.id ? 'opacity-100' : 'opacity-0'
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
							Adicionar cartão
						</CommandItem>
						<!-- </DialogTrigger> -->
					</CommandGroup>
				</CommandList>
			</Command>
		</PopoverContent>
	</Popover>
</template>
