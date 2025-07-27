<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { ref } from 'vue';
import CreateDialog from './CreateDialog.vue';
import DataTable from '@/Components/CategoryTable/DataTable.vue';
import type { Category } from '@/Components/CategoryTable/columns';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/Components/ui/tabs';
import {
	Card,
	CardContent,
	CardDescription,
	CardFooter,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { PencilIcon } from 'lucide-vue-next';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';
import Separator from '@/Components/ui/separator/Separator.vue';

defineProps<{
	inCategories: Category[];
	outCategories: Category[];
}>();

const { t } = useTranslation();

const currentTab = ref<'IN' | 'OUT'>('IN');

const createCategoryDialog = ref(false);
</script>

<template>
	<Head :title="$t('Categories')" />

	<CreateDialog
		:type="currentTab"
		:open="createCategoryDialog"
		@close="createCategoryDialog = false"
	/>

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col gap-4 p-4 lg:gap-6 lg:p-6 h-full">
			<div class="flex items-center justify-between">
				<h2 class="text-3xl font-bold tracking-tight">
					{{ $t('Categories') }}
				</h2>

				<div class="flex items-center space-x-2">
					<Button @click="createCategoryDialog = true">Adicionar categoria</Button>
				</div>
			</div>

			<Tabs v-model="currentTab" class="space-y-4 h-full">
				<!-- w-full justify-start rounded-none border-b bg-transparent p-0 -->
				<TabsList>
					<!-- relative h-9 rounded-none border-b-2 border-b-transparent bg-transparent px-4 pb-3 pt-2 font-semibold text-muted-foreground shadow-none transition-none data-[state=active]:border-b-primary data-[state=active]:text-foreground data-[state=active]:shadow-none -->
					<TabsTrigger value="IN"> {{ $t('Incomes') }}</TabsTrigger>
					<TabsTrigger value="OUT"> {{ $t('Outcomes') }}</TabsTrigger>
				</TabsList>

				<TabsContent value="IN" class="h-[calc(100%-37px-16px)]">
					<div
						v-if="inCategories.length === 0"
						class="h-full p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
					>
						<div class="flex flex-col items-center gap-1 text-center">
							<h3 class="text-2xl font-bold tracking-tight">
								Você ainda não adicionou uma categoria de entrada.
							</h3>
							<p class="text-sm text-muted-foreground">
								Você pode começar a acompanhar sua evolução financeira após adicionar
								sua primeira categoria.
							</p>

							<Button @click="createCategoryDialog = true" class="mt-4">
								Adicionar categoria
							</Button>
						</div>
					</div>

					<DataTable v-else :data="inCategories" />

					<div
						v-if="false"
						class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
					>
						<Card v-for="category in inCategories" :key="category.id">
							<CardHeader class="flex-row items-center">
								<span
									class="block size-6 rounded-xl"
									:style="{ 'background-color': category.color ?? 'transparent' }"
								></span>

								<div class="ml-3">
									<CardTitle class="text-lg">{{ category.name }}</CardTitle>
									<CardDescription v-if="false" class="text-sm text-muted-foreground">
										{{ useCurrency(t, category.transactions_sum_value!) }}
									</CardDescription>
								</div>

								<div class="ml-auto">
									<Button variant="ghost">
										<PencilIcon class="size-4" />
									</Button>
								</div>
							</CardHeader>
							<CardContent v-if="false" class="p-6 bg-accent">
								{{ useCurrency(t, category.transactions_sum_value!) }}
							</CardContent>
						</Card>
					</div>
				</TabsContent>

				<TabsContent value="OUT" class="h-[calc(100%-37px-16px)]">
					<div
						v-if="outCategories.length === 0"
						class="h-full p-4 flex flex-1 items-center justify-center rounded-lg border border-dashed shadow-sm"
					>
						<div class="flex flex-col items-center gap-1 text-center">
							<h3 class="text-2xl font-bold tracking-tight">
								Você ainda não adicionou uma categoria de saída.
							</h3>
							<p class="text-sm text-muted-foreground">
								Você pode começar a acompanhar sua evolução financeira após adicionar
								sua primeira categoria.
							</p>

							<Button @click="createCategoryDialog = true" class="mt-4">
								Adicionar categoria
							</Button>
						</div>
					</div>

					<DataTable v-else :data="outCategories" />
				</TabsContent>
			</Tabs>
		</div>
	</AuthenticatedLayout>
</template>
