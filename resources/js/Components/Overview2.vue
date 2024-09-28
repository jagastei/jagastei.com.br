<script setup lang="ts">
import { AreaChart } from '@/Components/ui/chart-area'
import { BarChart } from '@/Components/ui/chart-bar'
import { LineChart } from '@/Components/ui/chart-line'

const props = defineProps<{
    overview: Array<any>,
}>()
</script>

<template>
    <BarChart
        :data="overview"
        index="name"
        :categories="['total_in', 'total_out']"
        :colors="['#22C55E', '#EF4444']"
        :showXAxis="true"
        :showYAxis="true"
        :showGridLine="true"
        :showGradiant="true"
    />
</template>