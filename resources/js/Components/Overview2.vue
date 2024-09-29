<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area'
import { BarChart } from '@/Components/ui/chart-bar'
import { LineChart } from '@/Components/ui/chart-line'
import OverviewTooltip from '@/Components/OverviewTooltip.vue'
import { formatMoney } from '@/utils'

const props = defineProps<{
    overview: Array<any>,
}>()

console.log(props.overview)

const xFormatter = (value, i) => {
    return props.overview[i].name
}

const yFormatter = (value, i) => {
    return formatMoney(value);
}
</script>

<template>
    <BarChart
        :data="overview"
        index="name"
        :xFormatter="xFormatter"
        :yFormatter="yFormatter"
        :categories="['Entrada', 'Saída']"
        :colors="['#22C55E', '#EF4444']"
        :showXAxis="true"
        :showYAxis="true"
        :showGridLine="true"
        :showGradiant="true"
        :showLegend="true"
        :customTooltip="OverviewTooltip"
    />
</template>