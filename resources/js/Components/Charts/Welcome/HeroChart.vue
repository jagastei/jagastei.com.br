<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area';
import { useCurrency } from '@/composables/useCurrency';
import { CurveType } from '@unovis/ts';
import { computed } from 'vue';
import { useTranslation } from 'i18next-vue';

const props = defineProps<{
	data: Array<any>;
}>();

const { t } = useTranslation();

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
	return useCurrency(t, value);
};

const getColor = (transparency: number = 1) => {
	return `rgba(34, 197, 94, ${transparency})`;
};
</script>

<template>
	<div class="absolute w-full bottom-0 translate-y-20">
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
			:colors="[getColor(0.5)]"
			:showXAxis="false"
			:showYAxis="false"
			:showGridLine="false"
			:showLegend="false"
			:showGradiant="true"
			:showTooltip="false"
			:curveType="CurveType.Linear"
			:margin="{
				top: 0,
				right: 0,
				bottom: 0,
				left: 0,
			}"
		/>
	</div>
</template>
