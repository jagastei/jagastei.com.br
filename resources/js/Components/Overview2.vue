<script setup lang="ts">
import { BarChart } from '@/Components/ui/chart-bar';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';

const props = defineProps<{
	overview: Array<any>;
}>();

console.log(props.overview);

const xFormatter = (value: any, i: number) => {
	return props.overview[i].name;
};

const yFormatter = (value: any, i: number) => {
	return formatMoney(value);
};
</script>

<template>
	<BarChart
		:data="overview"
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
