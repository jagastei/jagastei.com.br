<script setup lang="ts">
import UserNav from '@/Components/UserNav.vue';
import { Button } from '@/Components/ui/button';
import Nav, { type LinkProp } from '@/Components/Nav.vue';
import { TooltipProvider } from '@/Components/ui/tooltip';
import { Separator } from '@/Components/ui/separator';
import ThemeToggle from '@/Components/ThemeToggle.vue';
import {
	BellIcon,
	CircleHelpIcon,
	GripVerticalIcon,
	MenuIcon,
	SearchIcon,
	SparklesIcon,
} from 'lucide-vue-next';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import {
	Sheet,
	SheetContent,
	SheetDescription,
	SheetHeader,
	SheetTitle,
	SheetTrigger,
} from '@/Components/ui/sheet';
import NavMobile from '@/Components/NavMobile.vue';
import {
	Collapsible,
	CollapsibleContent,
	CollapsibleTrigger,
} from '@/Components/ui/collapsible';
import { useStorage } from '@vueuse/core';
import { ScrollArea } from '@/Components/ui/scroll-area';

const isCollapsed = useStorage('is-collapsed', false);

const onCollapse = () => {
	isCollapsed.value = true;
};

const onExpand = () => {
	isCollapsed.value = false;
};

const links: LinkProp[] = [
	{
		title: 'Dashboard',
		label: '',
		icon: 'lucide:chart-pie',
		route: route('dashboard'),
		active: route().current('dashboard'),
	},
	{
		title: 'Movimentações',
		label: '',
		icon: 'lucide:arrow-left-right',
		route: route('transactions.index'),
		active: route().current('transactions.index'),
	},
	{
		title: 'Orçamentos',
		label: '',
		icon: 'lucide:box',
		route: route('budgets.index'),
		active: route().current('budgets.index'),
	},
	{
		title: 'Metas',
		label: '',
		icon: 'lucide:goal',
		route: route('goals.index'),
		active: route().current('goals.index'),
	},
];

const links2: LinkProp[] = [
	{
		title: 'Categorias',
		// label: '342',
		icon: 'lucide:tags',
		route: route('categories.index'),
		active: route().current('categories.index'),
	},
	{
		title: 'Cartões',
		// label: '342',
		icon: 'lucide:wallet-cards',
		route: route('cards.index'),
		active: route().current('cards.index'),
	},
	{
		title: 'Contas  ',
		// label: '972',
		icon: 'lucide:piggy-bank',
		route: route('accounts.index'),
		active: route().current('accounts.index'),
	},
];
</script>

<template>
	<div>
		<div class="flex-col md:flex">
			<div class="z-50 flex h-16 items-center pr-4 md:pr-8 border-b sticky top-0">
				<div :class="['hidden md:block', isCollapsed ? 'pl-2.5' : 'pl-5']">
					<img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
					<img
						src="@/../images/green-diamond-white.svg"
						class="hidden dark:block h-12 w-12"
					/>
				</div>

				<Sheet>
					<SheetTrigger as-child class="ml-4 md:hidden">
						<Button variant="outline" size="sm" class="h-8 space-x-2">
							<MenuIcon class="h-4 w-4" />
						</Button>
					</SheetTrigger>

					<SheetContent side="left" class="px-4">
						<SheetHeader>
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

						<NavMobile :is-collapsed="false" :links="links" />
						<div class="-mx-4">
							<Separator />
						</div>
						<NavMobile :is-collapsed="false" :links="links2" />
					</SheetContent>
				</Sheet>

				<div class="ml-auto flex items-center space-x-2">
					<!-- <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <SearchIcon class="h-4 w-4" />
                    </Button> -->

					<!-- <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <BellIcon class="h-4 w-4" />
                    </Button> -->

					<div class="hidden md:flex">
						<ThemeToggle />
					</div>

					<Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
						<SparklesIcon class="h-4 w-4" />
					</Button>

					<Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
						<CircleHelpIcon class="h-4 w-4" />
						<span>Suporte</span>
					</Button>

					<UserNav />
				</div>
			</div>

			<div class="md:flex flex-col h-[calc(100vh-64px)]">
				<TooltipProvider :delay-duration="0">
					<div class="flex h-full">
						<div
							:class="[
								'relative hidden md:flex flex-col border-r transition-all duration-300 ease-in-out',
								isCollapsed
									? 'min-w-[64px] max-w-[64px]'
									: 'min-w-[280px] max-w-[280px]',
							]"
						>
							<Nav :is-collapsed="isCollapsed" :links="links" />
							<Separator />
							<Nav :is-collapsed="isCollapsed" :links="links2" />

							<!-- <div class="mt-auto p-6">
                                <Card>
                                    <CardHeader>
                                        <CardTitle>Upgrade to Pro</CardTitle>
                                        <CardDescription>
                                            Unlock all features and get unlimited access to our
                                            support team.
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent>
                                        <Button size="sm" class="w-full">
                                            Upgrade
                                        </Button>
                                    </CardContent>
                                </Card>
                            </div> -->

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
