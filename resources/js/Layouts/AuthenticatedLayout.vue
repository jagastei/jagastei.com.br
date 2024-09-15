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
import { ArrowRightFromLineIcon, BellIcon, CircleHelpIcon, SearchIcon, SparklesIcon } from 'lucide-vue-next';

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
    <div>
        <div class="hidden flex-col md:flex">
            <div class="bg-white z-50 flex h-16 items-center pr-8  border-b sticky top-0">
                    <div :class="[
                        isCollapsed ? 'pl-2.5' : 'pl-5'
                    ]">
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

            <div class="md:flex flex-col h-[calc(100vh-64px)]">
                <TooltipProvider :delay-duration="0">
                    <ResizablePanelGroup auto-save-id="resize-panel-group-1" id="resize-panel-group-1" direction="horizontal">

                        <ResizablePanel id="resize-panel-1" :default-size="5" collapsible :collapsed-size="5" :min-size="15" :max-size="15" :class="cn(isCollapsed && 'min-w-[64px] max-w-[64px] transition-all duration-300 ease-in-out')" @expand="onExpand" @collapse="onCollapse">
                            <Nav :is-collapsed="isCollapsed" :links="links" />
                            <Separator />
                            <Nav :is-collapsed="isCollapsed" :links="links2" />
                        </ResizablePanel>

                        <ResizableHandle id="resize-handle-1" with-handle />

                        <ResizablePanel id="resize-panel-2">
                            <div class="p-8 h-full overflow-y-scroll">
                                <slot />
                            </div>
                        </ResizablePanel>
                        
                    </ResizablePanelGroup>
                </TooltipProvider>
            </div>
        </div>
    </div>
</template>
