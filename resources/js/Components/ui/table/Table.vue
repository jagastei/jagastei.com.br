<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/utils';

const props = defineProps<{
    wrapperClass?: HTMLAttributes['class'];
	class?: HTMLAttributes['class'];
}>();
</script>

<template>
	<div :class="cn('relative w-full overflow-auto', props.wrapperClass)">
		<table :class="cn('w-full caption-bottom text-sm', props.class)">
			<slot />
		</table>
	</div>
</template>
