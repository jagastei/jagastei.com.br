<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { formatMoney } from '@/utils';
import { CurveType } from '@unovis/ts';
import { computed } from 'vue';

const props = defineProps<{
	data: Array<any>;
}>();

const lastBalance = computed(() => {
	return props.data[props.data.length - 1].Saldo;
});

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
		:colors="[lastBalance > 0 ? '#22C55E' : '#EF4444']"
		:showXAxis="true"
		:showYAxis="true"
		:showGridLine="true"
		:showGradiant="true"
		:showLegend="true"
		:customTooltip="OverviewTooltip"
		:curveType="CurveType.Linear"
	/>
</template>
