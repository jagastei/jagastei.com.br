<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/utils';

const props = defineProps<{
	class?: HTMLAttributes['class'];
}>();
</script>

<template>
	<tr
		:class="
			cn(
				'transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted',
				props.class
			)
		"
	>
		<slot />
	</tr>
</template>
