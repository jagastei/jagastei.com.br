<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { useCurrency } from '@/composables/useCurrency';
import { CurveType } from '@unovis/ts';
import { computed } from 'vue';

const props = defineProps<{
	data: Array<any>;
}>();

const { formatMoney } = useCurrency();

const isEmpty = computed(() => props.data.length === 0);

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

const getColor = (transparency: number = 1) => {
	if (props.data.length === 0) {
		return `rgba(77, 140, 253, ${transparency})`;
	}

	const firstBalance = props.data[0].Saldo;
	const lastBalance = props.data[props.data.length - 1].Saldo;

	if (firstBalance === lastBalance) {
		return `rgba(77, 140, 253, ${transparency})`;
	}

	return lastBalance > 0
		? `rgba(34, 197, 94, ${transparency})`
		: `rgba(239, 68, 68, ${transparency})`;
};
</script>

<template>
	<div class="relative">
		<div v-if="isEmpty" class="absolute top-0 left-0 w-full h-full">
			<div class="flex items-center justify-center h-full">
				<span class="text-muted-foreground text-sm text-center">
					Comece a registrar suas transações para acompanhar a evolução do seu saldo.
				</span>
			</div>
		</div>

		<AreaChart
			v-if="isEmpty"
			:data="[
				{ name: 'Jan', Saldo: Math.floor(Math.random() * 2000) + 1000 },
				{ name: 'Feb', Saldo: Math.floor(Math.random() * 2000) + 1500 },
				{ name: 'Mar', Saldo: Math.floor(Math.random() * 2000) + 1000 },
				{ name: 'Apr', Saldo: Math.floor(Math.random() * 2000) + 3000 },
				{ name: 'May', Saldo: Math.floor(Math.random() * 2000) + 1000 },
				{ name: 'Jun', Saldo: Math.floor(Math.random() * 2000) + 2000 },
				{ name: 'Jul', Saldo: Math.floor(Math.random() * 2000) + 3000 },
				{ name: 'Aug', Saldo: Math.floor(Math.random() * 2000) + 3000 },
				{ name: 'Sep', Saldo: Math.floor(Math.random() * 2000) + 5000 },
				{ name: 'Oct', Saldo: Math.floor(Math.random() * 2000) + 7000 },
				{ name: 'Nov', Saldo: Math.floor(Math.random() * 2000) + 5000 },
				{ name: 'Dec', Saldo: Math.floor(Math.random() * 2000) + 15000 },
			]"
			index="name"
			:xFormatter="xFormatter"
			:yFormatter="yFormatter"
			:categories="['Saldo']"
			:colors="[getColor(0.2)]"
			:showXAxis="false"
			:showYAxis="false"
			:showGridLine="false"
			:showLegend="false"
			:showGradiant="true"
			:showTooltip="false"
			:curveType="CurveType.Linear"
		/>

		<AreaChart
			v-else
			:data="data"
			index="name"
			:xFormatter="xFormatter"
			:yFormatter="yFormatter"
			:categories="['Saldo']"
			:colors="[getColor()]"
			:showXAxis="true"
			:showYAxis="true"
			:showGridLine="true"
			:showLegend="true"
			:showGradiant="true"
			:customTooltip="OverviewTooltip"
			:curveType="CurveType.Linear"
		/>
	</div>
</template>
