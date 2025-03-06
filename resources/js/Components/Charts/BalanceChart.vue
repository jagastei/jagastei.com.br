<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';
import { CurveType } from '@unovis/ts';

const props = defineProps<{
	data: Array<any>;
}>();

const xFormatter = (value: any, i: number) => {
	if (!Number.isInteger(value)) {
		return '';
	}

	if (props.data[value] === undefined) {
		return '';
	}

	return props.data[value].name;
};

const yFormatter = (value: any, i: number) => {
	return formatMoney(value);
};
</script>

<template>
	<AreaChart
		:data="data"
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
