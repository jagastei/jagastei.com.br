<script lang="ts" setup>
import { cn } from '@/utils';
import { buttonVariants } from '@/Components/ui/button';
import {
	Tooltip,
	TooltipContent,
	TooltipTrigger,
} from '@/Components/ui/tooltip';
import { Link } from '@inertiajs/vue3';
import { NavProps } from './Nav.vue';

defineProps<NavProps>();
</script>

<template>
	<div class="group flex flex-col py-4">
		<nav class="grid gap-2">
			<template v-for="(link, index) of links" :key="`3-${index}`">
				<Link
					:href="link.route ? link.route : '#'"
					:class="
						cn(
							buttonVariants({
								variant: link.active ? 'default' : 'ghost',
								size: 'sm',
							}),
							link.active &&
								'dark:bg-muted dark:text-white dark:hover:bg-muted dark:hover:text-white',
							'justify-start'
						)
					"
				>
					<component :is="link.icon" class="mr-2 size-4" />
					{{ link.title }}
					<span
						v-if="link.label"
						:class="cn('ml-auto', link.active && 'text-background dark:text-white')"
					>
						{{ link.label }}
					</span>
				</Link>
			</template>
		</nav>
	</div>
</template>
