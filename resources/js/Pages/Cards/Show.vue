<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Brand, Card as CardSchema } from '@/Components/CardTable/columns';
import { Head, router } from '@inertiajs/vue3';
import WasteChart from '@/Components/Charts/WasteChart.vue';
import DateRangePicker from '@/Components/DateRangePicker.vue';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import CategoryChart from '@/Components/Charts/CategoryChart.vue';
import { useCurrency } from '@/composables/useCurrency';
import { useTranslation } from 'i18next-vue';
import { PenIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import EditDrawer from './EditDrawer.vue';

defineProps<{
	brands: Brand[];
	card: CardSchema;
	totalWasted: number;
	wastedByDay: Array<any>;
}>();

const { t } = useTranslation();

const editDrawerOpen = ref(false);

const closeEditDrawer = () => {
	editDrawerOpen.value = false;
};
</script>

<template>
	<Head title="Cartão" />

	<EditDrawer
		v-if="!!card"
		:open="editDrawerOpen"
		:card="card"
		:brands="brands"
		@close="closeEditDrawer"
	/>

	<AuthenticatedLayout>
		<div class="p-4 lg:p-6">
			<div class="flex flex-1 flex-col h-full">
				<div
					class="flex flex-col md:flex-row md:items-center justify-between gap-y-4 md:gap-y-0"
				>
					<div class="flex items-center gap-x-4 group">
						<img
							:src="`https://jagastei.com.br.test/images/banks/${card.account.bank.code}.png`"
							:alt="card.account.bank.long_name"
							class="size-10 rounded-xl"
						/>

						<h2 class="text-3xl font-bold tracking-tight">
							<span>{{ card.name }}</span>
						</h2>

						<div class="flex items-center gap-x-4">
							<PenIcon
								class="invisible group-hover:visible size-4 text-muted-foreground cursor-pointer"
								@click="editDrawerOpen = true"
							/>
						</div>
					</div>
					<div v-if="false" class="flex items-center space-x-2">
						<!-- <DateRangePicker
							:start-date="startDate"
							:end-date="endDate"
							@update:value="updateDateRange"
						/> -->
						<!-- <Button>Download</Button> -->
					</div>
				</div>
				<Tabs default-value="overview" class="space-y-4 mt-4">
					<TabsList>
						<TabsTrigger value="overview">{{ $t('Overview') }}</TabsTrigger>
						<TabsTrigger value="outcomes">{{ $t('Outcomes') }}</TabsTrigger>
					</TabsList>
					<TabsContent value="overview" class="space-y-4">
						<div v-if="false" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
							<Card>
								<CardHeader
									class="flex flex-row items-center justify-between space-y-0 pb-2"
								>
									<CardTitle class="text-sm font-medium"> Total Revenue </CardTitle>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 24 24"
										fill="none"
										stroke="currentColor"
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										class="h-4 w-4 text-muted-foreground"
									>
										<path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
									</svg>
								</CardHeader>
								<CardContent>
									<div class="text-2xl font-bold">$45,231.89</div>
									<p class="text-xs text-muted-foreground">+20.1% from last month</p>
								</CardContent>
							</Card>
							<Card>
								<CardHeader
									class="flex flex-row items-center justify-between space-y-0 pb-2"
								>
									<CardTitle class="text-sm font-medium"> Subscriptions </CardTitle>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 24 24"
										fill="none"
										stroke="currentColor"
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										class="h-4 w-4 text-muted-foreground"
									>
										<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
										<circle cx="9" cy="7" r="4" />
										<path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
									</svg>
								</CardHeader>
								<CardContent>
									<div class="text-2xl font-bold">+2350</div>
									<p class="text-xs text-muted-foreground">+180.1% from last month</p>
								</CardContent>
							</Card>
							<Card>
								<CardHeader
									class="flex flex-row items-center justify-between space-y-0 pb-2"
								>
									<CardTitle class="text-sm font-medium"> Sales </CardTitle>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 24 24"
										fill="none"
										stroke="currentColor"
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										class="h-4 w-4 text-muted-foreground"
									>
										<rect width="20" height="14" x="2" y="5" rx="2" />
										<path d="M2 10h20" />
									</svg>
								</CardHeader>
								<CardContent>
									<div class="text-2xl font-bold">+12,234</div>
									<p class="text-xs text-muted-foreground">+19% from last month</p>
								</CardContent>
							</Card>
							<Card>
								<CardHeader
									class="flex flex-row items-center justify-between space-y-0 pb-2"
								>
									<CardTitle class="text-sm font-medium"> Active Now </CardTitle>
									<svg
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 24 24"
										fill="none"
										stroke="currentColor"
										strokeLinecap="round"
										strokeLinejoin="round"
										strokeWidth="2"
										class="h-4 w-4 text-muted-foreground"
									>
										<path d="M22 12h-4l-3 9L9 3l-3 9H2" />
									</svg>
								</CardHeader>
								<CardContent>
									<div class="text-2xl font-bold">+573</div>
									<p class="text-xs text-muted-foreground">+201 since last hour</p>
								</CardContent>
							</Card>
						</div>

						<div class="grid gap-4 grid-cols-1 xl:grid-cols-7">
							<Card class="col-span-1 xl:col-span-7">
								<CardHeader>
									<CardTitle class="flex items-center gap-x-2">
										<span class="flex gap-1.5">
											<span>Total gasto no período:</span>
											<span class="text-red-500">{{ useCurrency(t, totalWasted) }}</span>
										</span>

										<!-- <span v-if="balanceData.startBalance !== balanceData.endBalance" :class="['flex items-center', balanceData.startBalance > balanceData.endBalance ? 'text-red-500' : 'text-green-500']">
											<component :is="balanceData.startBalance > balanceData.endBalance ? ArrowDown : ArrowUp" class="size-4" />
											<span class="text-base tracking-wide">
												{{ balanceData.diffBalancePercentage }}%
											</span>
										</span> -->
									</CardTitle>
									<CardDescription>
										<!-- <span>
											Seu saldo atual é de {{ formatMoney(balanceData.endBalance) }}.
										</span> -->
									</CardDescription>
								</CardHeader>
								<CardContent>
									<WasteChart key="wastedByDay" :data="wastedByDay" />
								</CardContent>
							</Card>
						</div>
					</TabsContent>

					<TabsContent value="outcomes" class="space-y-4"></TabsContent>
				</Tabs>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
