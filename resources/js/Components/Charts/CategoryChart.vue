<script setup lang="ts">
import { DonutChart } from '@/Components/ui/chart-donut';
import { formatMoney } from '@/utils';
import { computed } from 'vue';

const props = defineProps<{
	data: Array<any>;
}>();

const isEmpty = computed(() =>
	props.data.every((item) => item.transactions_sum_value === 0)
);

const fakeData = computed(() => {
	return props.data.map((item) => ({
		...item,
		transactions_sum_value: Math.floor(Math.random() * 2000) + 1000,
	}));
});

const formatter = (value: number, i: number | undefined): string => {
	return formatMoney(value);
};
</script>

<template>
	<div class="relative">
		<div v-if="isEmpty" class="absolute top-0 left-0 w-full h-full">
			<div class="flex items-center justify-center h-full">
				<span class="text-muted-foreground text-sm text-center w-1/2">
					Categorize seus gastos e veja como eles se distribuem.
				</span>
			</div>
		</div>

		<DonutChart
			v-if="isEmpty"
			class="h-64"
			:data="fakeData"
			index="name"
			category="transactions_sum_value"
			:colors="fakeData.map((category) => `${category.color}33`)"
			type="donut"
			:showLegend="false"
			:centralLabel="false"
			:valueFormatter="formatter"
			:showTooltip="false"
		/>

		<DonutChart
			v-else
			class="h-64"
			:data="data"
			index="name"
			category="transactions_sum_value"
			:colors="data.map((category) => category.color)"
			type="donut"
			:showLegend="false"
			:centralLabel="true"
			:valueFormatter="formatter"
		/>
	</div>
</template>
