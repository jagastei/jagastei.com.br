<script lang="ts" setup>
import { cn } from '@/utils';
import { buttonVariants } from '@/Components/ui/button';
import {
	Tooltip,
	TooltipContent,
	TooltipTrigger,
} from '@/Components/ui/tooltip';
import { Link } from '@inertiajs/vue3';
import { DocNavProps } from './DocNav.vue';
import Separator from './ui/separator/Separator.vue';

defineProps<DocNavProps>();
</script>

<template>
	<div class="group flex flex-col py-4">
		<nav class="grid gap-2">
			<template v-for="(link, index) of links" :key="`3-${index}`">
				<Separator v-if="link.type === 'divider'" class="my-6" />

				<button
					v-else
					:class="
						cn(
							buttonVariants({
								variant: link.active ? 'default' : 'ghost',
								size: 'sm',
							}),
							link.active &&
								'dark:bg-muted dark:text-white dark:hover:bg-muted dark:hover:text-white',
							'mx-6 justify-start'
						)
					"
				>
					<component v-if="link.icon" :is="link.icon" class="mr-2 size-4" />
					{{ link.title }}

					<span
						v-if="link.method"
						:class="[
							'ml-auto uppercase text-xs font-medium',
							{
								'text-green-500': link.method === 'get',
								'text-blue-500': link.method === 'post',
								'text-yellow-500': link.method === 'put',
								'text-red-500': link.method === 'delete',
							},
						]"
						>{{ link.method }}</span
					>
				</button>
			</template>
		</nav>
	</div>
</template>
