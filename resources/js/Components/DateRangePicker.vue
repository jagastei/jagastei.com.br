<script setup lang="ts">
import { Calendar as CalendarIcon } from 'lucide-vue-next';
import type { DateRange } from 'radix-vue';
import {
	CalendarDate,
	DateFormatter,
	getLocalTimeZone,
	parseDate,
} from '@internationalized/date';

import { type Ref, ref, watch } from 'vue';
import { cn } from '@/utils';
import { Button } from '@/Components/ui/button';
import { RangeCalendar } from '@/Components/ui/range-calendar';
import {
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { useLanguageStore } from '@/stores/languageStore';
import { computed } from 'vue';

const props = defineProps<{
	startDate: string;
	endDate: string;
}>();

const emit = defineEmits<{
	(e: 'update:value', value: DateRange): void;
}>();

const languageStore = useLanguageStore();

const df = computed(() => {
	return new DateFormatter(languageStore.getCurrentLanguage, {
		dateStyle: 'medium', // short
	});
});

const startDate = parseDate(props.startDate);
const endDate = parseDate(props.endDate);

const value = ref({
	start: startDate,
	end: endDate,
}) as Ref<DateRange>;

watch(value, (newVal: DateRange) => {
	if (newVal.start === undefined || newVal.end === undefined) {
		return;
	}

	emit('update:value', newVal);
});
</script>

<template>
	<div :class="cn('grid gap-2', $attrs.class ?? '')">
		<Popover>
			<PopoverTrigger as-child>
				<Button
					id="date"
					:variant="'outline'"
					:class="
						cn(
							'justify-start text-left font-normal',
							!value && 'text-muted-foreground'
						)
					"
				>
					<CalendarIcon class="mr-2 h-4 w-4 min-w-4" />

					<template v-if="value.start">
						<template v-if="value.end">
							{{ df.format(value.start.toDate(getLocalTimeZone())) }}
							-
							{{ df.format(value.end.toDate(getLocalTimeZone())) }}
						</template>

						<template v-else>
							{{ df.format(value.start.toDate(getLocalTimeZone())) }}
						</template>
					</template>
					<template v-else> Pick a date </template>
				</Button>
			</PopoverTrigger>
			<PopoverContent class="w-auto p-0" align="end">
				<RangeCalendar
					v-model="value"
					:locale="languageStore.getCurrentLanguage"
					:week-starts-on="1"
					weekday-format="short"
					:number-of-months="2"
					initial-focus
					:placeholder="value.start"
					@update:start-value="(startDate) => (value.start = startDate)"
				/>
			</PopoverContent>
		</Popover>
	</div>
</template>
