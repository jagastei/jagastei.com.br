<script lang="ts" setup>
import type { DrawerRootEmits, DrawerRootProps } from 'vaul-vue';
import { DrawerRoot } from 'vaul-vue';

const props = withDefaults(defineProps<DrawerRootProps>(), {
	shouldScaleBackground: true,
});

const emits = defineEmits<DrawerRootEmits>();
</script>

<template>
	<DrawerRoot :shouldScaleBackground="shouldScaleBackground">
		<slot />
	</DrawerRoot>
</template>
