<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import BalanceChart from '@/Components/Charts/BalanceChart.vue';
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CategoryChart from '@/Components/Charts/CategoryChart.vue';
import type { DateRange } from 'radix-vue';
import { useCurrency } from '@/composables/useCurrency';
import { computed, ref, toRef, watch } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert';
import { FlaskConical } from 'lucide-vue-next';
import { useTranslation } from 'i18next-vue';
import { usePostHog } from '@/composables/usePosthog';

const props = defineProps<{
	startDate: string;
	endDate: string;
	balanceByDay: Array<any>;
	wastedByDay: Array<any>;
	wasteByDayTransactionCount: number;
	wastedByCategory: Array<any>;
	wastedByCategoryTotal: number;
	currentBalance: number;
}>();

const page = usePage();
const user = computed(() => page.props.auth.user);

const { posthog } = usePostHog();

const { t } = useTranslation();

const updateDateRange = (value: DateRange) => {
	router.get(
		route('dashboard', {
			startDate: value.start?.toString(),
			endDate: value.end?.toString(),
		})
	);
};

const balanceData = computed<{
	startBalance: number;
	endBalance: number;
	diffBalance: number;
	diffBalancePercentage: string;
}>(() => {
	if (props.balanceByDay.length === 0) {
		return {
			startBalance: 0,
			endBalance: 0,
			diffBalance: 0,
			diffBalancePercentage: '0',
		};
	}

	const startBalance = props.balanceByDay[0].Saldo;
	const endBalance = props.balanceByDay[props.balanceByDay.length - 1].Saldo;
	const diffBalance = Math.abs(endBalance - startBalance);
	const diffBalancePercentage = ((diffBalance / startBalance) * 100).toFixed(2);

	return {
		startBalance,
		endBalance,
		diffBalance,
		diffBalancePercentage,
	};
});
</script>

<template>
	<Head :title="$t('Dashboard')" />

	<!-- <dl class="mx-auto grid grid-cols-1 gap-px bg-gray-900/5 sm:grid-cols-2 lg:grid-cols-4">
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
        <dt class="text-sm/6 font-medium text-gray-500">Revenue</dt>
        <dd class="text-xs font-medium text-gray-700">+4.75%</dd>
        <dd class="w-full flex-none text-3xl/10 font-medium tracking-tight text-gray-900">$405,091.00</dd>
    </div>
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
        <dt class="text-sm/6 font-medium text-gray-500">Overdue invoices</dt>
        <dd class="text-xs font-medium text-rose-600">+54.02%</dd>
        <dd class="w-full flex-none text-3xl/10 font-medium tracking-tight text-gray-900">$12,787.00</dd>
    </div>
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
        <dt class="text-sm/6 font-medium text-gray-500">Outstanding invoices</dt>
        <dd class="text-xs font-medium text-gray-700">-1.39%</dd>
        <dd class="w-full flex-none text-3xl/10 font-medium tracking-tight text-gray-900">$245,988.00</dd>
    </div>
    <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-2 bg-white px-4 py-10 sm:px-6 xl:px-8">
        <dt class="text-sm/6 font-medium text-gray-500">Expenses</dt>
        <dd class="text-xs font-medium text-rose-600">+10.18%</dd>
        <dd class="w-full flex-none text-3xl/10 font-medium tracking-tight text-gray-900">$30,156.00</dd>
    </div>
    </dl> -->

	<AuthenticatedLayout>
		<div v-if="$page.props.auth.on_generic_trial" class="p-4 lg:p-6 pb-0 lg:pb-0">
			<Alert variant="default" class="[&>svg]:text-purple-500">
				<FlaskConical class="size-4" />
				<AlertTitle>Você está no período de avaliação</AlertTitle>
				<AlertDescription class="text-muted-foreground">
					Aproveite todos os recursos gratuitamente até
					{{
						new Date($page.props.auth.user.trial_ends_at).toLocaleDateString('pt-BR')
					}}
				</AlertDescription>
			</Alert>
		</div>

		<div class="p-4 lg:p-6">
			<div class="flex flex-1 flex-col h-full">
				<div
					class="flex flex-col md:flex-row md:items-center justify-between gap-y-4 md:gap-y-0"
				>
					<h2 class="text-3xl font-bold tracking-tight">
						{{ $t('Dashboard') }}
					</h2>
					<div class="flex items-center space-x-2">
						<DateRangePicker
							:start-date="startDate"
							:end-date="endDate"
							@update:value="updateDateRange"
							class="w-full"
						/>
						<!-- <Button>Download</Button> -->
					</div>
				</div>
				<Tabs default-value="overview" class="space-y-4 mt-4">
					<!-- <TabsList>
						<TabsTrigger value="overview"> Overview </TabsTrigger>
						<TabsTrigger value="analytics" disabled> Analytics </TabsTrigger>
						<TabsTrigger value="reports" disabled> Reports </TabsTrigger>
						<TabsTrigger value="notifications" disabled> Notifications </TabsTrigger>
					</TabsList> -->
					<TabsContent value="overview" class="space-y-4 mt-0 md:mt-2">
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
											<span>Saldo de</span>
											<span
												:class="[
													{
														'text-green-500': currentBalance > 0,
														'text-red-500': currentBalance < 0,
														'text-blue-500': currentBalance === 0,
													},
												]"
												>{{ useCurrency(t, currentBalance) }}</span
											>
										</span>

										<!-- <span v-if="balanceData.startBalance !== balanceData.endBalance" :class="['flex items-center', balanceData.startBalance > balanceData.endBalance ? 'text-red-500' : 'text-green-500']">
											<component :is="balanceData.startBalance > balanceData.endBalance ? ArrowDown : ArrowUp" class="size-4" />
											<span class="text-base tracking-wide">
												{{ balanceData.diffBalancePercentage }}%
											</span>
										</span> -->
									</CardTitle>
									<CardDescription>
										<span v-if="balanceData.startBalance === balanceData.endBalance">
											O saldo inicial e final são iguais no período selecionado.
										</span>
										<span v-else-if="balanceData.startBalance < balanceData.endBalance">
											Seu saldo aumentou {{ useCurrency(t, balanceData.diffBalance) }} no
											período selecionado.
										</span>
										<span v-else>
											Seu saldo diminuiu {{ useCurrency(t, balanceData.diffBalance) }} no
											período selecionado.
										</span>
										<!-- <span>
											Seu saldo atual é de {{ formatMoney(balanceData.endBalance) }}.
										</span> -->
									</CardDescription>
								</CardHeader>
								<CardContent>
									<BalanceChart key="balanceByDay" :data="balanceByDay" />
								</CardContent>
							</Card>

							<Card class="col-span-1 xl:col-span-5">
								<CardHeader>
									<CardTitle>Gastos</CardTitle>
									<CardDescription
										>Você realizou {{ wasteByDayTransactionCount }} saídas no período
										selecionado.</CardDescription
									>
								</CardHeader>
								<CardContent>
									<WasteChart key="wastedByDay" :data="wastedByDay" />
								</CardContent>
							</Card>

							<Card class="col-span-1 xl:col-span-2">
								<CardHeader>
									<CardTitle>Por categoria</CardTitle>
									<CardDescription
										>Você gastou {{ useCurrency(t, wastedByCategoryTotal) }} no período
										selecionado.</CardDescription
									>
								</CardHeader>
								<CardContent
									class="flex justify-center items-center h-[calc(100%-98px)]"
								>
									<CategoryChart key="wastedByCategory" :data="wastedByCategory" />
								</CardContent>
							</Card>

							<!--
							<Card class="col-span-3">
								<CardHeader>
									<CardTitle>Recent Sales</CardTitle>
									<CardDescription> You made 265 sales this month. </CardDescription>
								</CardHeader>
								<CardContent>
									<RecentSales />
								</CardContent>
							</Card>
							-->
						</div>
					</TabsContent>
				</Tabs>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
