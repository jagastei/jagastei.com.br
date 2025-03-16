<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import {
	DropdownMenu,
	DropdownMenuContent,
	DropdownMenuGroup,
	DropdownMenuItem,
	DropdownMenuLabel,
	DropdownMenuSeparator,
	DropdownMenuShortcut,
	DropdownMenuTrigger,
	DropdownMenuSub,
	DropdownMenuSubTrigger,
	DropdownMenuSubContent,
	DropdownMenuPortal,
} from '@/Components/ui/dropdown-menu';
import {
	ChevronDownIcon,
	LifeBuoyIcon,
	LightbulbIcon,
	LogOutIcon,
	UserIcon,
	UserPlusIcon,
	DollarSignIcon,
	FlaskConical,
	CodeIcon,
} from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { Icon } from '@iconify/vue';
import { computed } from 'vue';

const { store } = useColorMode();
const user = usePage().props.auth.user;

const logout = () => {
	router.post(route('logout'));
};

const getSubscriptionRoute = computed(() => {
	if (!user.stripe_id) {
		return route('subscription.checkout');
	}

	return route('subscription.billing');
});
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button variant="outline" size="sm" class="h-10 relative space-x-2 pr-2">
				<span>{{ user.name }}</span>
				<ChevronDownIcon class="h-4 w-4" />
			</Button>
		</DropdownMenuTrigger>
		<DropdownMenuContent class="w-56" align="end">
			<DropdownMenuGroup>
				<Link :href="route('profile.edit')">
					<DropdownMenuItem class="cursor-pointer">
						<UserIcon class="mr-2 size-4" />
						<span>Minha conta</span>
					</DropdownMenuItem>
				</Link>
				<a :href="getSubscriptionRoute">
					<DropdownMenuItem class="cursor-pointer">
						<DollarSignIcon class="mr-2 size-4" />
						<span>Meu plano</span>
						<FlaskConical
							v-if="$page.props.auth.on_generic_trial"
							class="ml-auto size-4 text-purple-500"
						/>
					</DropdownMenuItem>
				</a>
			</DropdownMenuGroup>

			<DropdownMenuSeparator />

			<DropdownMenuSub>
				<DropdownMenuSubTrigger class="relative">
					<Icon
						icon="radix-icons:sun"
						class="mr-2 h-4 w-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"
					/>
					<Icon
						icon="radix-icons:moon"
						class="mr-2 absolute h-4 w-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"
					/>
					<span>Tema</span>
				</DropdownMenuSubTrigger>
				<DropdownMenuPortal>
					<DropdownMenuSubContent>
						<DropdownMenuItem @click="store = 'light'">
							<span class="w-full">Claro</span>
							<Icon icon="lucide:check" v-if="store === 'light'" />
						</DropdownMenuItem>
						<DropdownMenuItem @click="store = 'dark'">
							<span class="w-full">Escuro</span>
							<Icon icon="lucide:check" v-if="store === 'dark'" />
						</DropdownMenuItem>
						<DropdownMenuItem @click="store = 'auto'">
							<span class="w-full">Sistema</span>
							<Icon icon="lucide:check" v-if="store === 'auto'" />
						</DropdownMenuItem>
					</DropdownMenuSubContent>
				</DropdownMenuPortal>
			</DropdownMenuSub>

			<DropdownMenuSeparator />

			<DropdownMenuItem
				@click="$emitter.emit('open-invite-dialog')"
				class="cursor-pointer"
			>
				<UserPlusIcon class="mr-2 size-4" />
				<span>Convidar</span>
			</DropdownMenuItem>

			<DropdownMenuItem
				@click="$emitter.emit('open-feedback-dialog')"
				class="cursor-pointer"
			>
				<LightbulbIcon class="mr-2 size-4" />
				<span>Sugestão</span>
			</DropdownMenuItem>

			<DropdownMenuItem
				@click="$emitter.emit('open-support-dialog')"
				class="cursor-pointer"
			>
				<LifeBuoyIcon class="mr-2 size-4" />
				<span>Suporte</span>
			</DropdownMenuItem>

			<DropdownMenuSeparator />

			<a v-if="false" :href="route('docs.intro')" target="_blank">
				<DropdownMenuItem>
					<CodeIcon class="mr-2 size-4" />
					<span>Desenvolvedores</span>
				</DropdownMenuItem>
			</a>

			<DropdownMenuSeparator v-if="false" />

			<DropdownMenuItem class="cursor-pointer" @click="logout">
				<LogOutIcon class="mr-2 size-4" />
				<span>Sair</span>
			</DropdownMenuItem>
		</DropdownMenuContent>
	</DropdownMenu>
</template>
