<script setup lang="ts">
import { DonutChart } from '@/Components/ui/chart-donut';
import { formatMoney } from '@/utils';

const props = defineProps<{
	data: Array<any>;
}>();

const formatter = (value: number, i: number | undefined): string => {
	return formatMoney(value);
};
</script>

<template>
	<DonutChart
		class="h-64"
		index="name"
		category="transactions_sum_value"
		:colors="data.map((category) => category.color)"
		type="donut"
		:showLegend="false"
		:centralLabel="true"
		:data="data"
		:valueFormatter="formatter"
	/>
</template>
