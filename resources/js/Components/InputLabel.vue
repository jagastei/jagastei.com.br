<script setup lang="ts">
defineProps<{
	value?: string;
}>();
</script>

<template>
	<label class="block font-medium text-sm text-gray-700">
		<span v-if="value">{{ value }}</span>
		<span v-else><slot /></span>
	</label>
</template>
