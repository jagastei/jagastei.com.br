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
import { useTranslation } from 'i18next-vue';
import { Bank } from './AccountTable/columns';

const props = defineProps<{
	modelValue: Bank | undefined;
	banks: Bank[];
}>();

const emits = defineEmits<{
	(e: 'update:modelValue', payload: Bank | undefined): void;
	(e: 'close'): void;
}>();

const { t } = useTranslation();

const modelValue = useVModel(props, 'modelValue', emits, {
	passive: true,
	defaultValue: props.modelValue,
});

const dialogOpen = ref(false);

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
</script>

<template>
	<Popover v-model:open="dialogOpen">
        <PopoverTrigger as-child>
            <Button
                tabindex="3"
                id="bank"
                variant="outline"
                role="combobox"
                class="w-[375px] justify-between mt-2 p-3"
            >
                <div class="flex items-center truncate">
                    <div v-if="modelValue" class="min-w-4">
                        <img
                            :src="`https://jagastei.com.br.test/images/banks/${modelValue?.code}.png`"
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
                        >{{ modelValue ? modelValue?.long_name : 'Escolha um banco' }}</span
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
                    <template v-if="query.length > 0">Nenhum banco encontrado.</template>
                    <template v-else>Informe o nome ou código do banco.</template>
                </CommandEmpty>

                <CommandList>
                    <CommandGroup>
                        <CommandItem
                            v-for="bank in resultList"
                            :key="bank.id"
                            :value="bank"
                            @select="
                                () => {
                                    dialogOpen = false;
                                }
                            "
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
                                        modelValue?.id === bank.id ? 'opacity-100' : 'opacity-0'
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
