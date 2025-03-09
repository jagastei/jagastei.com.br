<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';
import { CurveType } from '@unovis/ts';
import { computed } from 'vue';

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

const getColor = () => {
	if(props.data.length === 0) {
		return '#4d8cfd';
	}

	const firstBalance = props.data[0].Saldo;
	const lastBalance = props.data[props.data.length - 1].Saldo;

	if(firstBalance === lastBalance) {
		return '#4d8cfd';
	}

	return lastBalance > 0 ? '#22C55E' : '#EF4444';
};
</script>

<template>
	<AreaChart
		:data="data"
		index="name"
		:xFormatter="xFormatter"
		:yFormatter="yFormatter"
		:categories="['Saldo']"
		:colors="[getColor()]"
		:showXAxis="true"
		:showYAxis="true"
		:showGridLine="true"
		:showGradiant="true"
		:showLegend="true"
		:customTooltip="OverviewTooltip"
		:curveType="CurveType.Linear"
	/>
</template>
