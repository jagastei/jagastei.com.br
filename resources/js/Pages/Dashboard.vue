<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Overview from '@/Components/Overview.vue'
import DateRangePicker from '@/Components/DateRangePicker.vue'
import RecentSales from '@/Components/RecentSales.vue'
import UserNav from '@/Components/UserNav.vue'
import { Button } from '@/Components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card'
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/Components/ui/tabs'
import Nav, { type LinkProp } from '@/Components/Nav.vue'
import { ref } from 'vue';
import { cn } from '@/utils'
import { TooltipProvider } from '@/Components/ui/tooltip'
import { ResizableHandle, ResizablePanel, ResizablePanelGroup } from '@/Components/ui/resizable'
import { Separator } from '@/Components/ui/separator'
import Base from '@/Layouts/Base.vue';
import ThemeToggle from '@/Components/ThemeToggle.vue'
import { BellIcon, CircleHelpIcon, SearchIcon, SparklesIcon } from 'lucide-vue-next';

const isCollapsed = ref(false)

const onCollapse = () => {
    isCollapsed.value = true
}

const onExpand = () => {
    isCollapsed.value = false
}

const links: LinkProp[] = [
    {
        title: 'Inbox',
        label: '128',
        icon: 'lucide:inbox',
        variant: 'default',
    },
    {
        title: 'Drafts',
        label: '9',
        icon: 'lucide:file',
        variant: 'ghost',
    },
    {
        title: 'Sent',
        label: '',
        icon: 'lucide:send',
        variant: 'ghost',
    },
    {
        title: 'Junk',
        label: '23',
        icon: 'lucide:archive',
        variant: 'ghost',
    },
    {
        title: 'Trash',
        label: '',
        icon: 'lucide:trash',
        variant: 'ghost',
    },
    {
        title: 'Archive',
        label: '',
        icon: 'lucide:archive',
        variant: 'ghost',
    },
]

const links2: LinkProp[] = [
    {
        title: 'Social',
        label: '972',
        icon: 'lucide:user-2',
        variant: 'ghost',
    },
    {
        title: 'Updates',
        label: '342',
        icon: 'lucide:alert-circle',
        variant: 'ghost',
    },
    {
        title: 'Forums',
        label: '128',
        icon: 'lucide:message-square',
        variant: 'ghost',
    },
    {
        title: 'Shopping',
        label: '8',
        icon: 'lucide:shopping-cart',
        variant: 'ghost',
    },
    {
        title: 'Promotions',
        label: '21',
        icon: 'lucide:archive',
        variant: 'ghost',
    },
]
</script>

<template>
    <Base>

        <Head title="Dashboard" />

        <!-- <a href="/subscription-checkout">Subscribe</a> -->

        <div class="hidden flex-col md:flex h-screen">
            <div class="border-b">
                <div class="flex h-16 items-center pl-5 pr-8">
                    <!-- <TeamSwitcher /> -->
                    <!-- <MainNav class="mx-6" /> -->

                    <div>
                        <img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12"/>
                        <img src="@/../images/green-diamond-white.svg" class="hidden dark:block h-12 w-12"/>
                    </div>
                    
                    <div class="ml-auto flex items-center space-x-2">
                        <Button variant="outline" size="sm" class="h-8 space-x-2">
                            <SearchIcon class="h-4 w-4"/>
                        </Button>

                        <Button variant="outline" size="sm" class="h-8 space-x-2">
                            <BellIcon class="h-4 w-4"/>
                        </Button>

                        <Button variant="outline" size="sm" class="h-8 space-x-2">
                            <SparklesIcon class="h-4 w-4"/>
                        </Button>

                        <ThemeToggle />

                        <Button variant="outline" size="sm" class="h-8 space-x-2">
                            <CircleHelpIcon class="h-4 w-4"/>
                            <span>Suporte</span>
                        </Button>

                        <UserNav />
                    </div>
                </div>
            </div>

            <div class="flex-col md:flex h-full">

                <TooltipProvider :delay-duration="0">
                    <ResizablePanelGroup id="resize-panel-group-1" direction="horizontal" class="h-full items-stretch">

                        <ResizablePanel id="resize-panel-1" :default-size="15" :collapsed-size="4" collapsible :min-size="15" :max-size="20" :class="cn(isCollapsed && 'min-w-[50px] transition-all duration-300 ease-in-out')" @expand="onExpand" @collapse="onCollapse">
                            <Nav :is-collapsed="isCollapsed" :links="links" />
                            <Separator />
                            <Nav :is-collapsed="isCollapsed" :links="links2" />
                        </ResizablePanel>

                        <ResizableHandle id="resize-handle-1" with-handle />

                        <ResizablePanel id="resize-panel-2">
                            <div class="flex-1 space-y-4 p-8">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-3xl font-bold tracking-tight">
                                        Dashboard
                                    </h2>
                                    <div class="flex items-center space-x-2">
                                        <DateRangePicker />
                                        <Button>Download</Button>
                                    </div>
                                </div>
                                <Tabs default-value="overview" class="space-y-4">
                                    <TabsList>
                                        <TabsTrigger value="overview">
                                            Overview
                                        </TabsTrigger>
                                        <TabsTrigger value="analytics" disabled>
                                            Analytics
                                        </TabsTrigger>
                                        <TabsTrigger value="reports" disabled>
                                            Reports
                                        </TabsTrigger>
                                        <TabsTrigger value="notifications" disabled>
                                            Notifications
                                        </TabsTrigger>
                                    </TabsList>
                                    <TabsContent value="overview" class="space-y-4">
                                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                                            <Card>
                                                <CardHeader
                                                    class="flex flex-row items-center justify-between space-y-0 pb-2">
                                                    <CardTitle class="text-sm font-medium">
                                                        Total Revenue
                                                    </CardTitle>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" strokeLinecap="round"
                                                        strokeLinejoin="round" strokeWidth="2"
                                                        class="h-4 w-4 text-muted-foreground">
                                                        <path
                                                            d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                                    </svg>
                                                </CardHeader>
                                                <CardContent>
                                                    <div class="text-2xl font-bold">
                                                        $45,231.89
                                                    </div>
                                                    <p class="text-xs text-muted-foreground">
                                                        +20.1% from last month
                                                    </p>
                                                </CardContent>
                                            </Card>
                                            <Card>
                                                <CardHeader
                                                    class="flex flex-row items-center justify-between space-y-0 pb-2">
                                                    <CardTitle class="text-sm font-medium">
                                                        Subscriptions
                                                    </CardTitle>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" strokeLinecap="round"
                                                        strokeLinejoin="round" strokeWidth="2"
                                                        class="h-4 w-4 text-muted-foreground">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                        <circle cx="9" cy="7" r="4" />
                                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                                    </svg>
                                                </CardHeader>
                                                <CardContent>
                                                    <div class="text-2xl font-bold">
                                                        +2350
                                                    </div>
                                                    <p class="text-xs text-muted-foreground">
                                                        +180.1% from last month
                                                    </p>
                                                </CardContent>
                                            </Card>
                                            <Card>
                                                <CardHeader
                                                    class="flex flex-row items-center justify-between space-y-0 pb-2">
                                                    <CardTitle class="text-sm font-medium">
                                                        Sales
                                                    </CardTitle>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" strokeLinecap="round"
                                                        strokeLinejoin="round" strokeWidth="2"
                                                        class="h-4 w-4 text-muted-foreground">
                                                        <rect width="20" height="14" x="2" y="5" rx="2" />
                                                        <path d="M2 10h20" />
                                                    </svg>
                                                </CardHeader>
                                                <CardContent>
                                                    <div class="text-2xl font-bold">
                                                        +12,234
                                                    </div>
                                                    <p class="text-xs text-muted-foreground">
                                                        +19% from last month
                                                    </p>
                                                </CardContent>
                                            </Card>
                                            <Card>
                                                <CardHeader
                                                    class="flex flex-row items-center justify-between space-y-0 pb-2">
                                                    <CardTitle class="text-sm font-medium">
                                                        Active Now
                                                    </CardTitle>
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" strokeLinecap="round"
                                                        strokeLinejoin="round" strokeWidth="2"
                                                        class="h-4 w-4 text-muted-foreground">
                                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                                                    </svg>
                                                </CardHeader>
                                                <CardContent>
                                                    <div class="text-2xl font-bold">
                                                        +573
                                                    </div>
                                                    <p class="text-xs text-muted-foreground">
                                                        +201 since last hour
                                                    </p>
                                                </CardContent>
                                            </Card>
                                        </div>
                                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                                            <Card class="col-span-4">
                                                <CardHeader>
                                                    <CardTitle>Overview</CardTitle>
                                                </CardHeader>
                                                <CardContent>
                                                    <Overview />
                                                </CardContent>
                                            </Card>
                                            <Card class="col-span-3">
                                                <CardHeader>
                                                    <CardTitle>Recent Sales</CardTitle>
                                                    <CardDescription>
                                                        You made 265 sales this month.
                                                    </CardDescription>
                                                </CardHeader>
                                                <CardContent>
                                                    <RecentSales />
                                                </CardContent>
                                            </Card>
                                        </div>
                                    </TabsContent>
                                </Tabs>
                            </div>
                        </ResizablePanel>
                    </ResizablePanelGroup>
                </TooltipProvider>
            </div>
        </div>
    </Base>
</template>
