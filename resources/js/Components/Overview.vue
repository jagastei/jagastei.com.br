<script setup lang="ts">
import { BarChart } from '@/Components/ui/chart-bar';

const props = defineProps<{
	overview: Array<any>;
}>();

console.log(props.overview);
</script>

<template>
	<BarChart
		:data="overview"
		:categories="['total_value']"
		index="month"
		:rounded-corners="0"
		showGridLine
	/>
</template>
