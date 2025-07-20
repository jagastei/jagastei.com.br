<script setup lang="ts">
import UserNav from '@/Components/UserNav.vue';
import { Button } from '@/Components/ui/button';
import Nav, { type LinkProp } from '@/Components/Nav.vue';
import { TooltipProvider } from '@/Components/ui/tooltip';
import { Separator } from '@/Components/ui/separator';
import {
	ArrowDown,
	ArrowUp,
	BellIcon,
	ChartPie,
	GripVerticalIcon,
	MenuIcon,
	PiggyBank,
	SearchIcon,
	Settings,
	SparklesIcon,
	Tags,
	WalletCards,
} from 'lucide-vue-next';
import {
	Sheet,
	SheetContent,
	SheetDescription,
	SheetHeader,
	SheetTitle,
	SheetTrigger,
} from '@/Components/ui/sheet';
import NavMobile from '@/Components/NavMobile.vue';
import { useStorage } from '@vueuse/core';
import { ScrollArea } from '@/Components/ui/scroll-area';
import WalletSwitcher from '@/Components/WalletSwitcher.vue';
import FeedbackDialog from '@/Components/FeedbackDialog.vue';
import SupportDialog from '@/Components/SupportDialog.vue';
import InviteDialog from '@/Components/InviteDialog.vue';
import { computed, onMounted, ref, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { usePostHog } from '@/composables/usePosthog';
import Toaster from '@/Components/ui/toast/Toaster.vue';
import { useTranslation } from 'i18next-vue';
import Input from '@/Components/ui/input/Input.vue';
import { useMagicKeys } from '@vueuse/core';
import CommandDialog from '@/Components/CommandDialog.vue';

const { t } = useTranslation();

const user = usePage().props.auth.user;
const { posthog } = usePostHog();

const isCollapsed = useStorage('is-collapsed', false);

const onCollapse = () => {
	isCollapsed.value = true;
};

const onExpand = () => {
	isCollapsed.value = false;
};

const links = computed<LinkProp[]>(() => [
	{
		title: t('Dashboard'),
		label: '',
		icon: ChartPie,
		route: route('dashboard'),
		active: route().current('dashboard'),
	},
	// {
	// 	title: 'Movimentações',
	// 	label: '',
	// 	icon: 'lucide:arrow-left-right',
	// 	route: route('transactions.index'),
	// 	active: route().current('transactions.index'),
	// },
	{
		title: t('Incomes'),
		label: '',
		icon: ArrowUp,
		route: route('transactions.in.index'),
		active: route().current('transactions.in.index'),
	},
	{
		title: t('Outcomes'),
		label: '',
		icon: ArrowDown,
		route: route('transactions.out.index'),
		active: route().current('transactions.out.index'),
	},
	// {
	// 	title: 'Metas',
	// 	label: '',
	// 	icon: 'lucide:goal',
	// 	route: route('goals.index'),
	// 	active: route().current('goals.index'),
	// },
	// {
	// 	title: 'Orçamentos',
	// 	label: '',
	// 	icon: 'lucide:box',
	// 	route: route('budgets.index'),
	// 	active: route().current('budgets.index'),
	// },
]);

const links2 = computed<LinkProp[]>(() => [
	{
		title: t('Categories'),
		// label: '342',
		icon: Tags,
		route: route('categories.index'),
		active: route().current('categories.index'),
	},
	{
		title: t('Cards'),
		// label: '342',
		icon: WalletCards,
		route: route('cards.index'),
		active: route().current('cards.index'),
	},
	{
		title: t('Accounts'),
		// label: '972',
		icon: PiggyBank,
		route: route('accounts.index'),
		active: route().current('accounts.index'),
	},
]);

const links3 = computed<LinkProp[]>(() => [
	{
		title: t('Settings'),
		icon: Settings,
		route: route('wallets.show'),
		active: route().current('wallets.show'),
	},
]);

const inviteDialog = ref(false);
const feedbackDialog = ref(false);
const supportDialog = ref(false);

// const commandDialog = ref(false);

// const { Meta_K, Ctrl_K } = useMagicKeys({
//     passive: false,
//     onEventFired(event) {
//         if(event.key === 'k' && (event.metaKey || event.ctrlKey) && event.type === 'keydown') {
//             // commandDialog.value = !commandDialog.value;
//             event.preventDefault();
//         }
//     }
// });

// watch([Meta_K, Ctrl_K], (value) => {
//     if(value[0] || value[1]) {
//         commandDialog.value = !commandDialog.value;
//     }
// });

onMounted(() => {
	window.emitter.on('open-invite-dialog', () => {
		inviteDialog.value = true;
	});

	window.emitter.on('open-feedback-dialog', () => {
		feedbackDialog.value = true;
	});

	window.emitter.on('open-support-dialog', () => {
		supportDialog.value = true;
	});

	posthog.identify(user.id, {
		email: user.email,
		name: user.name,
	});
});
</script>

<template>
	<div>
		<Toaster />

		<InviteDialog :open="inviteDialog" @update:open="inviteDialog = $event" />
		<FeedbackDialog
			:open="feedbackDialog"
			@update:open="feedbackDialog = $event"
		/>
		<SupportDialog :open="supportDialog" @update:open="supportDialog = $event" />

		<!-- <CommandDialog :open="commandDialog" @update:open="commandDialog = $event" /> -->

		<div class="flex-col md:flex">
			<div
				class="z-50 flex h-[4.5rem] items-center pr-4 md:pr-4 lg:pr-6 border-b sticky top-0"
			>
				<Link
					:href="route('dashboard')"
					:class="['hidden md:block', isCollapsed ? 'pl-4' : 'pl-6']"
				>
					<img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
					<img
						src="@/../images/green-diamond-white.svg"
						class="hidden dark:block h-12 w-12"
					/>
				</Link>

				<Sheet class="md:hidden">
					<SheetTrigger as-child class="ml-4 md:hidden mr-2">
						<Button variant="outline" size="sm" class="h-10">
							<MenuIcon class="h-4 w-4" />
						</Button>
					</SheetTrigger>

					<SheetContent side="left" class="px-4">
						<SheetHeader>
							<SheetTitle>
								<Link :href="route('dashboard')">
									<img
										src="@/../images/green-diamond.svg"
										class="dark:hidden h-12 w-12"
									/>
									<img
										src="@/../images/green-diamond-white.svg"
										class="hidden dark:block h-12 w-12"
									/>
								</Link>
							</SheetTitle>

							<SheetDescription></SheetDescription>
						</SheetHeader>

						<NavMobile :is-collapsed="false" :links="links" />
						<div class="-mx-4">
							<Separator />
						</div>
						<NavMobile :is-collapsed="false" :links="links2" />
						<div class="-mx-4">
							<Separator />
						</div>
						<NavMobile :is-collapsed="false" :links="links3" />
					</SheetContent>
				</Sheet>

				<!-- <button @click="commandDialog = true" class="hidden md:block absolute w-[280px] -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                    <div class="relative cursor-pointer w-full">
                        <Input
                            id="search"
                            :placeholder="t('Search')"
                            readonly
                            class="cursor-pointer w-full bg-muted border-transparent focus-visible:ring-0 placeholder:text-muted-foreground"
                        />

                        <kbd class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-muted-foreground font-sans">
                            ⌘ + K
                        </kbd>
                    </div>
                </button> -->

				<div class="md:ml-auto w-full md:w-auto flex items-center space-x-2">
					<Button
						v-if="false"
						variant="outline"
						size="sm"
						class="hidden md:flex h-10 space-x-2"
					>
						<SearchIcon class="h-4 w-4" />
					</Button>

					<Button
						v-if="false"
						variant="outline"
						size="sm"
						class="hidden md:flex h-10 space-x-2"
					>
						<BellIcon class="h-4 w-4" />
					</Button>

					<Button
						v-if="false"
						variant="outline"
						size="sm"
						class="hidden md:flex h-10 space-x-2"
					>
						<SparklesIcon class="h-4 w-4" />
					</Button>

					<WalletSwitcher />

					<UserNav />
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
							<Nav :is-collapsed="isCollapsed" :links="links" />
							<Separator class="my-6" />
							<Nav :is-collapsed="isCollapsed" :links="links2" />
							<Separator class="my-6" />
							<Nav :is-collapsed="isCollapsed" :links="links3" />

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
