<script setup lang="ts">
import { BarChart } from '@/Components/ui/chart-bar';
import OverviewTooltip from '@/Components/OverviewTooltip.vue';
import { useCurrency } from '@/composables/useCurrency';
import { computed, h } from 'vue';
import { useTranslation } from 'i18next-vue';

const props = defineProps<{
	data: Array<any>;
}>();

const { t } = useTranslation();

const isEmpty = computed(() => props.data.every((item) => item['Saída'] === 0));

const yFormatter = (value: any, i: number) => {
	return useCurrency(t, value);
};

const xFormatter = (value: any, i: number) => {
	if (!Number.isInteger(value)) {
		return '';
	}

	if (props.data[value] === undefined) {
		return '';
	}

	return props.data[value].name;
};

const renderTooltip = (item: any) => {
	return h(OverviewTooltip, {
		title: item.title,
		data: item.data,
		t: t,
	});
};
</script>

<template>
	<div class="relative">
		<div v-if="isEmpty" class="absolute top-0 left-0 w-full h-full">
			<div class="flex items-center justify-center h-full">
				<span class="text-muted-foreground text-sm text-center">
					Nenhuma transação de saída registrada no período selecionado.
				</span>
			</div>
		</div>

		<BarChart
			v-if="isEmpty"
			:data="[
				{
					name: 'Monday, 10 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 15000,
				},
				{
					name: 'Tuesday, 11 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 4000,
				},
				{
					name: 'Wednesday, 12 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 500,
				},
				{
					name: 'Thursday, 13 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 2000,
				},
				{
					name: 'Friday, 14 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 4000,
				},
				{
					name: 'Saturday, 15 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 2000,
				},
				{
					name: 'Sunday, 16 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 7000,
				},
				{
					name: 'Monday, 17 March 2025',
					Saída: Math.floor(Math.random() * 2000) + 10000,
				},
			]"
			index="name"
			:xFormatter="xFormatter"
			:yFormatter="yFormatter"
			:categories="['Saída']"
			:colors="['#EF444433']"
			:showXAxis="false"
			:showYAxis="false"
			:showGridLine="false"
			:showLegend="false"
			:showTooltip="false"
		/>

		<BarChart
			v-else
			:data="data"
			index="name"
			:xFormatter="xFormatter"
			:yFormatter="yFormatter"
			:categories="['Saída']"
			:colors="['#EF4444']"
			:showXAxis="true"
			:showYAxis="true"
			:showGridLine="true"
			:showLegend="true"
			:customTooltip="renderTooltip"
		/>
	</div>
</template>
