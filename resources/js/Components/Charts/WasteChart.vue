<script setup lang="ts">
import { BarChart } from '@/Components/ui/chart-bar';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';

const props = defineProps<{
	data: Array<any>;
}>();

const xFormatter = (value: any, i: number) => {
	return props.data[i].name;
};

const yFormatter = (value: any, i: number) => {
	return formatMoney(value);
};
</script>

<template>
	<BarChart
		:data="data"
		index="name"
		:xFormatter="xFormatter"
		:yFormatter="yFormatter"
		:categories="['Saída']"
		:colors="['#EF4444']"
		:showXAxis="true"
		:showYAxis="true"
		:showGridLine="true"
		:showGradiant="true"
		:showLegend="true"
		:customTooltip="OverviewTooltip"
	/>
</template>
