<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { cn } from '@/utils';
import { Check, ChevronsUpDown, Star } from 'lucide-vue-next';
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
import { Brand } from '@/Components/CardTable/columns';
import { useTranslation } from 'i18next-vue';

const props = defineProps<{
	modelValue: Brand | undefined;
	brands: Brand[];
	suggestedBrandIdentifier: string | null;
}>();

const emits = defineEmits<{
	(e: 'update:modelValue', payload: Brand | undefined): void;
	(e: 'close'): void;
}>();

const { t } = useTranslation();

const modelValue = useVModel(props, 'modelValue', emits, {
	passive: true,
	defaultValue: props.modelValue,
});

const dialogOpen = ref(false);

const query = ref<string>('');

const { results } = useFuse(query, props.brands, {
	fuseOptions: {
		keys: ['identifier', 'name'],
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
				id="brand"
				variant="outline"
				role="combobox"
				class="w-[375px] justify-between mt-2 p-3"
			>
				<div class="flex items-center truncate">
					<div v-if="modelValue" class="min-w-4">
						<img
							:src="`https://jagastei.com.br.test/images/brands-svg/flat/${modelValue.identifier}.svg`"
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
						>{{ modelValue ? modelValue?.name : 'Escolha uma bandeira' }}</span
					>
				</div>
				<ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
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
					<template v-if="query.length > 0">Nenhuma marca encontrada.</template>
					<template v-else>Informe o nome da marca.</template>
				</CommandEmpty>

				<CommandList>
					<CommandGroup>
						<CommandItem
							v-for="brand in resultList"
							:key="brand.id"
							:value="brand"
							@select="
								() => {
									dialogOpen = false;
								}
							"
							class="flex"
						>
							<div class="min-w-4">
								<img
									:src="`https://jagastei.com.br.test/images/brands-svg/flat/${brand.identifier}.svg`"
									class="size-4 rounded-xl"
								/>
							</div>
							<div class="ml-1 truncate flex items-center">
                                <span>{{ brand.name }}</span>
                                <Star v-if="brand.identifier === suggestedBrandIdentifier" fill="#eab308" class="size-2 text-yellow-500 ml-2" />
                            </div>
							<Check
								:class="
									cn(
										'ml-auto h-4 w-4',
										modelValue?.id === brand.id ? 'opacity-100' : 'opacity-0'
									)
								"
							/>
						</CommandItem>
					</CommandGroup>
				</CommandList>
			</Command>
		</PopoverContent>
	</Popover>
</template>
