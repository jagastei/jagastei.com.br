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

export interface LinkProp {
	title: string;
	label?: string;
	icon: any;
	route?: string;
	active: boolean;
}

export interface NavProps {
	isCollapsed: boolean;
	links: LinkProp[];
}

const props = defineProps<NavProps>();

console.log(props)
</script>

<template>
	<div :data-collapsed="isCollapsed" class="group flex flex-col">
		<nav
			class="grid gap-2 px-6 group-[[data-collapsed=true]]:justify-center group-[[data-collapsed=true]]:px-2"
		>
			<template v-for="(link, index) of links">
				<Tooltip v-if="isCollapsed" :key="`1-${index}`" :delay-duration="0">
					<TooltipTrigger as-child>
						<Link
							:href="link.route ? link.route : '#'"
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
							<component :is="link.icon" class="size-4" />
							<span class="sr-only">{{ link.title }}</span>
						</Link>
					</TooltipTrigger>
					<TooltipContent side="right" class="ml-1 flex items-center gap-4">
						{{ link.title }}
						<span v-if="link.label" class="ml-auto text-muted-foreground">
							{{ link.label }}
						</span>
					</TooltipContent>
				</Tooltip>

				<Link
					v-else
					:key="`2-${index}`"
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
