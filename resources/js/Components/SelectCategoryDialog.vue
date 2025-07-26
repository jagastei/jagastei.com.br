<script setup lang="ts">
import { Popover, PopoverTrigger, PopoverContent } from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Command, CommandInput, CommandEmpty, CommandList, CommandGroup, CommandItem, CommandSeparator } from '@/Components/ui/command';
import { ChevronsUpDown, Check, CirclePlus } from 'lucide-vue-next';
import { Category } from './CategoryTable/columns';
import { useVModel } from '@vueuse/core';
import { useFuse } from '@vueuse/integrations/useFuse';
import { computed, ref } from 'vue';
import { useTranslation } from 'i18next-vue';
import { cn } from '@/utils';
import CreateCategoryDialog from '@/Components/CreateCategoryDialog.vue';

const props = defineProps<{
    modelValue: Category | undefined;
    categories: Category[];
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: Category | undefined): void;
    (e: 'categoryCreated', payload: Category): void;
}>();

const { t } = useTranslation();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.modelValue,
});

const dialogOpen = ref(false);
const createDialogOpen = ref(false);

const query = ref<string>('');

const categories = computed(() => props.categories);

const { results } = useFuse(query, categories, {
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

const onCategoryCreated = (category: Category) => {
    createDialogOpen.value = false;
    emits('categoryCreated', category);
}
</script>

<template>
    <CreateCategoryDialog
        :open="createDialogOpen"
        @close="createDialogOpen = false"
        @success="onCategoryCreated"
    />

    <Popover v-model:open="dialogOpen">
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
                        v-if="modelValue"
                        class="block size-4 rounded-xl"
                        :style="{ backgroundColor: modelValue.color ?? '#000000' }"
                    ></div>
                    <span
                        :class="[
                            'truncate',
                            {
                                'ml-3': modelValue,
                            },
                        ]"
                        >{{
                            modelValue ? modelValue?.name : 'Escolha uma categoria'
                        }}</span
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
                            @select="
                                () => {
                                    dialogOpen = false;
                                }
                            "
                            class="flex"
                        >
                            <div
                                class="block size-4 rounded-xl"
                                :style="{ backgroundColor: category.color ?? '#000000' }"
                            ></div>
                            <span class="ml-3 block truncate">{{ category.name }}</span>
                            <Check
                                :class="
                                    cn(
                                        'ml-auto size-4',
                                        modelValue?.id === category.id ? 'opacity-100' : 'opacity-0'
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
                        <CommandItem
                            value="create-category"
                            @select="
                                (event) => {
                                    event.preventDefault();

                                    dialogOpen = false;
                                    createDialogOpen = true;
                                }
                            "
                        >
                            <CirclePlus class="mr-2 h-5 w-5" />
                            Adicionar categoria
                        </CommandItem>
                        <!-- </DialogTrigger> -->
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
