<script lang="ts" setup>
import { Icon } from '@iconify/vue';
import { cn } from '@/utils';
import { buttonVariants } from '@/Components/ui/button';
import {
	Tooltip,
	TooltipContent,
	TooltipTrigger,
} from '@/Components/ui/tooltip';
import { Link } from '@inertiajs/vue3';
import Separator from './ui/separator/Separator.vue';

export interface DocLinkProp {
	title?: string;
	icon?: string;
	type: 'link' | 'divider';
	method?: 'get' | 'post' | 'put' | 'delete';
	route?: string;
	active?: boolean;
}

export interface DocNavProps {
	isCollapsed: boolean;
	links: DocLinkProp[];
}

defineProps<DocNavProps>();
</script>

<template>
	<div :data-collapsed="isCollapsed" class="group flex flex-col">
		<nav
			class="grid gap-2 group-[[data-collapsed=true]]:justify-center group-[[data-collapsed=true]]:px-2"
		>
			<template v-for="link of links" :key="link.route">

				<Separator v-if="link.type === 'divider'" class="my-6" />

				<Tooltip v-if="link.type === 'link' && isCollapsed" :delay-duration="0">
					<TooltipTrigger as-child>
						<div
							:class="
								cn(
									buttonVariants({
										variant: link.active ? 'default' : 'ghost',
										size: 'icon',
									}),
									'h-10 w-10',
									link.active &&
										'dark:bg-muted dark:text-muted-foreground dark:hover:bg-muted dark:hover:text-white'
								)
							"
						>
							<Icon v-if="link.icon" :icon="link.icon" class="size-4" />
							<span class="sr-only">{{ link.title }}</span>
						</div>
					</TooltipTrigger>
					<TooltipContent side="right" class="ml-1 flex items-center gap-4">
						{{ link.title }}
					</TooltipContent>
				</Tooltip>

				<Link v-if="link.type === 'link' && !isCollapsed"
					:href="link.route!"
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
					<Icon v-if="link.icon" :icon="link.icon" class="mr-2 size-4" />
					{{ link.title }}

					<span v-if="link.method" :class="['ml-auto uppercase text-xs font-medium', {
						'text-green-500': link.method === 'get',
						'text-blue-500': link.method === 'post',
						'text-yellow-500': link.method === 'put',
						'text-red-500': link.method === 'delete',
					}]"
					>{{ link.method }}</span
				>
			</Link>
		</template>
		</nav>
	</div>
</template>
