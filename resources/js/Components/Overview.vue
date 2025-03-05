<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';
import { CurveType } from '@unovis/ts';

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
	<AreaChart
		:data="overview"
		index="name"
		:xFormatter="xFormatter"
		:yFormatter="yFormatter"
		:categories="['Saldo']"
		:colors="['#22C55E']"
		:showXAxis="true"
		:showYAxis="true"
		:showGridLine="true"
		:showGradiant="true"
		:showLegend="true"
		:customTooltip="OverviewTooltip"
		:curveType="CurveType.Linear"
	/>
</template>
