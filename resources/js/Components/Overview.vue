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
	console.log([i, value]);
	return value;
	// return props.overview[i].created_at;
};

const yFormatter = (value: any, i: number) => {
	return formatMoney(value);
};
</script>

<template>
	<AreaChart
		:data="overview"
		index="created_at"
		:xFormatter="xFormatter"
		:yFormatter="yFormatter"
		:categories="['Saldo']"
		:colors="['#22C55E']"
		:showXAxis="false"
		:showYAxis="true"
		:showGridLine="true"
		:showGradiant="true"
		:showLegend="true"
		:customTooltip="OverviewTooltip"
		:curveType="CurveType.Linear"
	/>
</template>
