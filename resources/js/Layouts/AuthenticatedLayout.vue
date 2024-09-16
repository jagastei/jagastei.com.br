<script setup lang="ts">
import UserNav from '@/Components/UserNav.vue'
import { Button } from '@/Components/ui/button'
import Nav, { type LinkProp } from '@/Components/Nav.vue'
import { ref } from 'vue';
import { cn } from '@/utils'
import { TooltipProvider } from '@/Components/ui/tooltip'
import { ResizableHandle, ResizablePanel, ResizablePanelGroup } from '@/Components/ui/resizable'
import { Separator } from '@/Components/ui/separator'
import ThemeToggle from '@/Components/ThemeToggle.vue'
import { ArrowRightFromLineIcon, BellIcon, CircleHelpIcon, MenuIcon, SearchIcon, SparklesIcon } from 'lucide-vue-next';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/Components/ui/sheet'
import NavMobile from '@/Components/NavMobile.vue';

const isCollapsed = ref(false)

const onCollapse = () => {
    isCollapsed.value = true
}

const onExpand = () => {
    isCollapsed.value = false
}

const links: LinkProp[] = [
    {
        title: 'Dashboard',
        label: '128',
        icon: 'lucide:chart-pie',
        route: route('dashboard'),
        active: route().current('dashboard'),
    },
    {
        title: 'Drafts',
        label: '9',
        icon: 'lucide:file',
        active: false,
    },
    {
        title: 'Sent',
        label: '',
        icon: 'lucide:send',
        active: false,
    },
    {
        title: 'Junk',
        label: '23',
        icon: 'lucide:archive',
        active: false,
    },
    {
        title: 'Trash',
        label: '',
        icon: 'lucide:trash',
        active: false,
    },
    {
        title: 'Archive',
        label: '',
        icon: 'lucide:archive',
        active: false,
    },
]

const links2: LinkProp[] = [
    {
        title: 'Cartões',
        // label: '342',
        icon: 'lucide:wallet-cards',
        active: false,
    },
    {
        title: 'Contas  ',
        // label: '972',
        icon: 'lucide:piggy-bank',
        route: route('accounts.index'),
        active: route().current('accounts.index'),
    }
]
</script>

<template>
    <div>

        <div class="flex-col md:flex">
            <div class="z-50 flex h-16 items-center pr-4 md:pr-8 border-b sticky top-0">
                <div :class="['hidden md:block',
                    isCollapsed ? 'pl-2.5' : 'pl-5'
                ]">
                    <img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
                    <img src="@/../images/green-diamond-white.svg" class="hidden dark:block h-12 w-12" />
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
                                <img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
                                <img src="@/../images/green-diamond-white.svg" class="hidden dark:block h-12 w-12" />
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
                    <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <SearchIcon class="h-4 w-4" />
                    </Button>

                    <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <BellIcon class="h-4 w-4" />
                    </Button>

                    <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <SparklesIcon class="h-4 w-4" />
                    </Button>

                    <div class="hidden md:flex">
                        <ThemeToggle />
                    </div>

                    <Button variant="outline" size="sm" class="hidden md:flex h-8 space-x-2">
                        <CircleHelpIcon class="h-4 w-4" />
                        <span>Suporte</span>
                    </Button>

                    <UserNav />
                </div>
            </div>

            <div class="md:flex flex-col h-[calc(100vh-64px)]">
                <TooltipProvider :delay-duration="0">
                    <ResizablePanelGroup auto-save-id="resize-panel-group-1" id="resize-panel-group-1"
                        direction="horizontal">

                        <!-- min-w-[240px] max-w-[240px] -->
                        <ResizablePanel id="resize-panel-1" :default-size="5" collapsible :collapsed-size="5"
                            :min-size="15" :max-size="15"
                            :class="cn(isCollapsed ? 'min-w-[64px] max-w-[64px] transition-all duration-300 ease-in-out' : '', 'hidden md:flex flex-col')"
                            @expand="onExpand" @collapse="onCollapse">

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
                        </ResizablePanel>

                        <ResizableHandle id="resize-handle-1" with-handle class="hidden md:flex"/>

                        <ResizablePanel id="resize-panel-2">
                            <div class="p-4 h-full overflow-y-scroll">
                                <slot />
                            </div>
                        </ResizablePanel>

                    </ResizablePanelGroup>
                </TooltipProvider>
            </div>
        </div>
    </div>
</template>
