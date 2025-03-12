<script setup lang="ts">
import Button from '@/Components/ui/button/Button.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { CircleCheck } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
	canLogin?: boolean;
	canRegister?: boolean;
	laravelVersion: string;
	phpVersion: string;
}>();

useColorMode();

const selectedFeature = ref('payroll')
</script>

<template>

	<Head title="Welcome" />

	<div class="flex flex-col scroll-smooth">
		<header class="py-10">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<nav class="relative z-50 flex justify-between">

					<div class="flex items-center md:gap-x-12">
						<Link href="/">
						<img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
						<img src="@/../images/green-diamond-white.svg" class="hidden dark:block h-12 w-12" />
						</Link>
					</div>

					<div class="flex items-center gap-x-4">
						<Link v-if="$page.props.auth.user" :href="route('dashboard')">
						<Button>Painel</Button>
						</Link>

						<template v-else>
							<div class="hidden md:block">
								<Link :href="route('login')">
								<Button variant="ghost">Entrar</Button>
								</Link>
							</div>

							<Link v-if="canRegister" :href="route('register')">
							<Button>Cadastre-se</Button>
							</Link>
						</template>
					</div>
				</nav>
			</div>
		</header>

		<main>
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center py-12 lg:py-24">
				<h1
					class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-foreground sm:text-7xl">
					Gestão financeira para pessoas e pequenos negócios.
				</h1>
				
				<p class="mx-auto mt-12 max-w-2xl text-2xl tracking-tight text-muted-foreground">Nosso sistema de controle de gastos é fácil de usar e ajuda você a manter suas finanças em ordem.</p>

				<div v-if="false" class="mt-10 flex justify-center gap-x-6">
					<Link :href="route('register')">
						<Button>Cadastre-se</Button>
					</Link>
				</div>
			</div>

			<section
				id="features"
				aria-label="Features for running your books"
				class="relative overflow-hidden pt-20 pb-28 sm:py-32"
			>
				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
					<div class="max-w-2xl md:mx-auto md:text-center xl:max-w-none">
						<h2 class="font-display text-3xl tracking-tight text-white sm:text-4xl md:text-5xl">Everything
							you need to run your books.</h2>
						<p class="mt-6 text-lg tracking-tight text-muted-foreground">Well everything you need if you
							aren’t
							that
							picky about minor details like tax compliance.</p>
					</div>
					<div
						class="mt-16 grid grid-cols-1 items-center gap-x-4 gap-y-2 pt-10 sm:gap-y-6 md:mt-20 lg:grid-cols-12 lg:pt-0">
						<div class="-mx-4 flex overflow-x-auto pb-4 sm:mx-0 sm:overflow-visible sm:pb-0 lg:col-span-5">
							<div
								class="relative z-10 flex gap-x-4 px-4 whitespace-nowrap sm:mx-auto sm:px-0 lg:mx-0 lg:flex-col lg:gap-x-0 lg:gap-y-4 lg:whitespace-normal">
								<button :class="[
									'text-left group relative px-4 py-1 lg:rounded-xl lg:p-6 lg:border', {
									}, selectedFeature === 'payroll' ? 'bg-card border-muted' : 'border-transparent hover:bg-muted'
								]" @click="selectedFeature = 'payroll'">
									<h3
										class="font-display text-lg data-selected:not-data-focus:outline-hidden text-foreground">
										Payroll
									</h3>
									<p class="mt-2 hidden text-sm lg:block text-muted-foreground">Keep track of
										everyone's
										salaries
										and whether or not they've been paid. Direct deposit not supported.</p>
								</button>

								<button :class="[
									'text-left group relative px-4 py-1 lg:rounded-xl lg:p-6 lg:border', {
									}, selectedFeature === 'expenses' ? 'bg-card border-muted' : 'border-transparent hover:bg-muted'
								]" @click="selectedFeature = 'expenses'">
									<h3
										class="font-display text-lg data-selected:not-data-focus:outline-hidden text-foreground">
										Expenses
									</h3>
									<p class="mt-2 hidden text-sm lg:block text-muted-foreground">Keep track of
										everyone's
										salaries
										and whether or not they've been paid. Direct deposit not supported.</p>
								</button>

								<button :class="[
									'text-left group relative px-4 py-1 lg:rounded-xl lg:p-6 lg:border', {
									}, selectedFeature === 'reports' ? 'bg-card border-muted' : 'border-transparent hover:bg-muted'
								]" @click="selectedFeature = 'reports'">
									<h3
										class="font-display text-lg data-selected:not-data-focus:outline-hidden text-foreground">
										Reports
									</h3>
									<p class="mt-2 hidden text-sm lg:block text-muted-foreground">Keep track of
										everyone's
										salaries
										and whether or not they've been paid. Direct deposit not supported.</p>
								</button>

								<button :class="[
									'text-left group relative px-4 py-1 lg:rounded-xl lg:p-6 lg:border', {
									}, selectedFeature === 'settings' ? 'bg-card border-muted' : 'border-transparent hover:bg-muted'
								]" @click="selectedFeature = 'settings'">
									<h3
										class="font-display text-lg data-selected:not-data-focus:outline-hidden text-foreground">
										Settings
									</h3>
									<p class="mt-2 hidden text-sm lg:block text-muted-foreground">Keep track of
										everyone's
										salaries
										and whether or not they've been paid. Direct deposit not supported.</p>
								</button>

							</div>
						</div>
						<div class="lg:col-span-7">
							<div class="isolate">
								<div class="-z-10 relative sm:px-6 lg:hidden">
									<div
										class="absolute -inset-x-4 top-[-6.5rem] bottom-[-4.25rem] bg-muted sm:inset-x-0 sm:rounded-t-xl">
									</div>
									<p class="relative mx-auto max-w-2xl text-base text-white sm:text-center">Keep track
										of everyone's salaries and whether or not they've been paid. Direct deposit not
										support
										ed.</p>
								</div>

								<div
									class="z-10 mt-10 w-[45rem] overflow-hidden rounded-xl ring-1 ring-muted sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
									<div class="relative">
										<div
											class="absolute top-0 pl-2.5 inset-x-0 h-8 bg-card flex items-center gap-2">
											<div class="size-3 rounded-full bg-red-500 hover:bg-red-600"></div>
											<div class="size-3 rounded-full bg-yellow-500 hover:bg-yellow-600"></div>
											<div class="size-3 rounded-full bg-green-500 hover:bg-green-600"></div>
										</div>

										<img width="2174" height="1464" class="pt-8 w-full rounded-xl"
											src="@/../images/dashboard.png">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<section id="pricing" aria-label="Pricing" class="py-20 sm:py-32">
				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
					<div class="md:text-center">
						<h2
							class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
						>
							Simple pricing, for everyone.
						</h2>
						<p class="mt-4 text-lg text-muted-foreground">
							It doesn’t matter what size your business is, our software won’t work
							well for you.
						</p>
					</div>
					<div
						class="-mx-4 mt-16 grid max-w-2xl grid-cols-1 gap-y-10 sm:mx-auto lg:-mx-8 lg:max-w-none lg:grid-cols-3 xl:mx-0 xl:gap-x-8"
					>
						<div></div>
						<section class="col-span-1 flex flex-col px-6 sm:px-8 order-first bg-card py-8 lg:order-none">
							<h3 class="mt-5 font-display text-lg text-white">Small business</h3>
							<p class="mt-2 text-base text-white">Perfect for small / medium sized businesses.</p>
							<p class="order-first font-display text-5xl font-light tracking-tight text-white">R$ 49,90
							</p>
							<ul role="list" class="order-last mt-10 flex flex-col gap-y-4 text-sm text-white">
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" />
									<span class="ml-4">Send 25 quotes and invoices</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Connect up to
										5 bank
										accounts</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Track up to 50
										expenses per month</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Automated
										payroll
										support</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Export up to
										12
										reports</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Bulk reconcile
										transactions</span>
								</li>
								<li class="flex">
									<CircleCheck class="size-6 flex-none text-white" /><span class="ml-4">Track in
										multiple
										currencies</span>
								</li>
							</ul>
							<a class="group inline-flex items-center justify-center py-2 px-4 text-sm font-semibold focus:outline-hidden focus-visible:outline-2 focus-visible:outline-offset-2 bg-white text-slate-900 hover:bg-green-50 active:bg-green-200 active:text-slate-600 focus-visible:outline-white mt-8"
								href="/register">Get
								started</a>
						</section>
						<div></div>
					</div>
				</div>
			</section>
		</main>

		<footer>
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<div class="py-16 flex justify-center">
					<Link href="/">
					<img src="@/../images/green-diamond.svg" class="dark:hidden h-12 w-12" />
					<img src="@/../images/green-diamond-white.svg" class="hidden dark:block h-12 w-12" />
					</Link>
				</div>
				<div class="flex justify-center items-center py-10">
					<p class="text-sm text-muted-foreground">
						2025 JaGastei © Todos os direitos reservados.
					</p>
				</div>
			</div>
		</footer>
	</div>

</template>
