<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { TooltipProvider } from '@/Components/ui/tooltip';
import {
	BellIcon,
	GripVerticalIcon,
	MenuIcon,
	SearchIcon,
	SparklesIcon,
} from 'lucide-vue-next';
import {
	Sheet,
	SheetContent,
	SheetDescription,
	SheetHeader,
	SheetTitle,
	SheetTrigger,
} from '@/Components/ui/sheet';
import { useColorMode, useStorage } from '@vueuse/core';
import { ScrollArea } from '@/Components/ui/scroll-area';
import FeedbackDialog from '@/Components/FeedbackDialog.vue';
import SupportDialog from '@/Components/SupportDialog.vue';
import { onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { usePostHog } from '@/composables/usePosthog';
import DocNav, { DocLinkProp } from '@/Components/DocNav.vue';
import DocNavMobile from '@/Components/DocNavMobile.vue';

const user = usePage().props.auth.user;
const { posthog } = usePostHog();

useColorMode();

const isCollapsed = useStorage('is-collapsed', false);

const onCollapse = () => {
	isCollapsed.value = true;
};

const onExpand = () => {
	isCollapsed.value = false;
};

const links: DocLinkProp[] = [
	{
		title: 'Introdução',
		icon: 'lucide:book-open-text',
		type: 'link',
		route: route('docs.intro'),
		active: route().current('docs.intro'),
	},
	{
		title: 'Autenticação',
		icon: 'lucide:key-round',
		type: 'link',
		route: route('docs.auth'),
		active: route().current('docs.auth'),
	},
	{
		type: 'divider',
	},
	{
		title: 'Movimentações',
		icon: 'lucide:arrow-up-down',
		type: 'link',
		route: route('docs.transactions'),
		active: route().current('docs.transactions'),
	},
	{
		title: 'Listar',
		type: 'link',
		method: 'get',
		route: route('docs.transactions.list'),
		active: route().current('docs.transactions.list'),
	},
	{
		title: 'Criar',
		type: 'link',
		method: 'post',
		route: route('docs.transactions.create'),
		active: route().current('docs.transactions.create'),
	},
	{
		title: 'Excluir',
		type: 'link',
		method: 'delete',
		route: route('docs.transactions.destroy'),
		active: route().current('docs.transactions.destroy'),
	},
];

const feedbackDialog = ref(false);
const supportDialog = ref(false);

onMounted(() => {
	window.emitter.on('open-feedback-dialog', () => {
		feedbackDialog.value = true;
	});

	window.emitter.on('open-support-dialog', () => {
		supportDialog.value = true;
	});

	if (user) {
		posthog.identify(user.id, {
			email: user.email,
			name: user.name,
		});
	}
});
</script>

<template>
	<div>
		<FeedbackDialog
			:open="feedbackDialog"
			@update:open="feedbackDialog = $event"
		/>
		<SupportDialog :open="supportDialog" @update:open="supportDialog = $event" />

		<div class="flex-col md:flex">
			<div
				class="z-50 flex h-[4.5rem] items-center pr-4 md:pr-4 lg:pr-6 border-b sticky top-0"
			>
				<div :class="['hidden md:block', isCollapsed ? 'pl-4' : 'pl-6']">
					<img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
					<img
						src="@/../images/green-diamond-white.svg"
						class="hidden dark:block h-12 w-12"
					/>
				</div>

				<Sheet>
					<SheetTrigger as-child class="ml-4 md:hidden mr-2">
						<Button variant="outline" size="sm" class="h-10">
							<MenuIcon class="h-4 w-4" />
						</Button>
					</SheetTrigger>

					<SheetContent side="left" class="px-0">
						<SheetHeader class="px-6">
							<SheetTitle>
								<img
									src="@/../images/green-diamond.svg"
									class="dark:hidden h-12 w-12"
								/>
								<img
									src="@/../images/green-diamond-white.svg"
									class="hidden dark:block h-12 w-12"
								/>
							</SheetTitle>

							<SheetDescription></SheetDescription>
						</SheetHeader>

						<DocNavMobile :is-collapsed="false" :links="links" />
					</SheetContent>
				</Sheet>

				<div class="ml-auto flex items-center space-x-2">
					<Link v-if="$page.props.auth.user" :href="route('dashboard')">
						<Button>Painel</Button>
					</Link>

					<Link v-else :href="route('login')">
						<Button variant="outline">Entrar</Button>
					</Link>
				</div>
			</div>

			<div class="md:flex flex-col h-[calc(100vh-4.5rem)]">
				<TooltipProvider :delay-duration="0">
					<div class="flex h-full">
						<div
							:class="[
								'py-6 relative hidden md:flex flex-col border-r transition-all duration-300 ease-in-out',
								isCollapsed
									? 'min-w-[64px] max-w-[64px]'
									: 'min-w-[280px] max-w-[280px]',
							]"
						>
							<DocNav :is-collapsed="isCollapsed" :links="links" />

							<div v-if="false" class="mt-auto p-6"></div>

							<button
								@click="isCollapsed = !isCollapsed"
								class="absolute right-0 top-[calc(50%-64px)] -translate-y-1/2 translate-x-1/2"
							>
								<GripVerticalIcon class="h-2.5 w-2.5" />
							</button>
						</div>

						<div class="w-full">
							<ScrollArea id="scrollarea" as-child>
								<div class="h-full">
									<slot />
								</div>
							</ScrollArea>
						</div>
					</div>
				</TooltipProvider>
			</div>
		</div>
	</div>
</template>
