<script setup lang="ts">
import Button from '@/Components/ui/button/Button.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import {
	CircleCheck,
	ArrowRight,
	Users,
	BarChart3,
	Wallet,
	Settings,
} from 'lucide-vue-next';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import HeroChart from '@/Components/Charts/Welcome/HeroChart.vue';
import PricingChart from '@/Components/Charts/Welcome/PricingChart.vue';
import FooterChart from '@/Components/Charts/Welcome/FooterChart.vue';

defineProps<{
	canLogin?: boolean;
	canRegister?: boolean;
	laravelVersion: string;
	phpVersion: string;
}>();

useColorMode();

const selectedFeature = ref('reports');

const features = [
	{
		id: 'reports',
		title: 'Relatórios',
		description: 'Acompanhe a evolução do seu saldo de forma visual e detalhada.',
		icon: BarChart3,
	},
	{
		id: 'receipts',
		title: 'Receitas',
		description: 'Registre e categorize todas as suas receitas.',
		icon: Users,
	},
	{
		id: 'expenses',
		title: 'Despesas',
		description:
			'Registre e categorize todas as suas despesas. Visualize para onde seu dinheiro está indo.',
		icon: Wallet,
	},
	{
		id: 'settings',
		title: 'Configurações',
		description:
			'Personalize o sistema de acordo com as necessidades do seu negócio.',
		icon: Settings,
	},
];

const isVisible = ref(true);

let autoRotateInterval: any = null;
const lastUserInteraction = ref(0);

const selectFeature = (feature: string) => {
	selectedFeature.value = feature;
	lastUserInteraction.value = Date.now();
};

onMounted(() => {
	setTimeout(() => {
		isVisible.value = true;
	}, 100);

	autoRotateInterval = setInterval(() => {
		if (Date.now() - lastUserInteraction.value > 30000) {
			const currentIndex = features.findIndex(
				(f) => f.id === selectedFeature.value
			);
			const nextIndex = (currentIndex + 1) % features.length;
			selectedFeature.value = features[nextIndex].id;
		}
	}, 15000);
});

onBeforeUnmount(() => {
	clearInterval(autoRotateInterval);
});
</script>

<template>
	<!-- <Head title="Welcome" /> -->

	<div class="relative flex flex-col scroll-smooth">

		<!-- sticky top-0 bg-background/80 backdrop-blur-sm z-50 border-b border-border/40 -->
		<header class="py-6">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<nav class="relative flex justify-between items-center">
					<div class="flex items-center md:gap-x-12">
						<Link href="/" class="flex items-center gap-2 group">
						<div class="relative overflow-hidden">
							<img src="@/../images/green-diamond.svg"
								class="dark:hidden h-10 w-10 transition-transform duration-300 group-hover:scale-110" />
							<img src="@/../images/green-diamond-white.svg"
								class="hidden dark:block h-10 w-10 transition-transform duration-300 group-hover:scale-110" />
						</div>
						<span
							class="font-medium text-xl hidden sm:inline-block group-hover:text-primary transition-colors">JaGastei</span>
						</Link>
					</div>

					<div class="flex items-center gap-x-4">
						<Link v-if="$page.props.auth.user" :href="route('dashboard')">
							<Button>{{ $t('Dashboard') }}</Button>
						</Link>

						<template v-else>
							<div class="hidden md:block">
								<Link :href="route('login')">
									<Button variant="outline">{{ $t('Login') }}</Button>
								</Link>
							</div>

							<Link v-if="false && canRegister" :href="route('register')">
							<Button>{{ $t('Register') }}</Button>
							</Link>
						</template>
					</div>
				</nav>
			</div>
		</header>

		<main>
			<!-- Hero Section -->
			<section class="relative py-24 lg:py-64">
				<HeroChart :data="[]" />

				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
					<div class="flex flex-col items-center">
						<div :class="[
							'transition-all duration-1000 transform',
							isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
						]">
							<h1
								class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-foreground sm:text-6xl lg:text-7xl">
								Gestão financeira
								<span class="text-primary relative">
									simplificada
									<span class="absolute bottom-2 left-0 w-full h-1 bg-primary/30 rounded-full"></span>
								</span>
							</h1>

							<p class="mx-auto mt-8 max-w-2xl text-xl tracking-tight text-muted-foreground">
								Nosso sistema de controle de gastos é fácil de usar e ajuda você a
								manter suas finanças em ordem.
							</p>
						</div>

						<div :class="[
							'mt-10 flex flex-col sm:flex-row justify-center gap-4 transition-all duration-1000 delay-300 transform',
							isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8',
						]">
							<Link :href="route('register')">
							<Button size="lg" class="px-8 group">
								Comece agora
								<ArrowRight
									class="ml-2 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
							</Button>
							</Link>
						</div>
					</div>
				</div>
			</section>

			<!-- Features Section -->
			<section class="relative py-24 sm:py-64">
				<div class="mx-auto max-w-7xl  px-4 sm:px-6 lg:px-8 relative">
					<div class="max-w-2xl md:mx-auto md:text-center xl:max-w-none">
						<h2 class="font-display text-3xl tracking-tight text-foreground sm:text-4xl md:text-5xl">
							Tudo que você precisa para gerenciar suas finanças
						</h2>
						<p class="mt-6 text-lg tracking-tight text-muted-foreground">
							Ferramentas simples e poderosas para controlar seus gastos e receitas de
							forma eficiente.
						</p>
					</div>
					<div class="mt-16 grid grid-cols-1 items-center gap-x-8 gap-y-10 lg:grid-cols-12">
						<div class="flex flex-col gap-y-6 lg:col-span-5">
							<div class="flex flex-col gap-y-4">
								<div v-for="feature in features" :key="feature.id" :class="[
									'relative p-6 transition-all duration-200 cursor-pointer group border',
									selectedFeature === feature.id
										? 'bg-card border-border shadow-sm'
										: 'border-transparent',
								]" @click="selectFeature(feature.id)">
									<div class="flex items-start gap-4">
										<div :class="['p-2 rounded-lg transition-colors text-muted-foreground']">
											<component :is="feature.icon" class="h-5 w-5" />
										</div>
										<div>
											<h3 class="font-display text-lg font-medium text-foreground">
												{{ feature.title }}
											</h3>
											<p class="mt-2 text-sm text-muted-foreground">
												{{ feature.description }}
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="lg:col-span-7">
							<div class="overflow-hidden border border-border bg-background shadow-sm">
								<div class="relative">
									<div
										class="absolute top-0 pl-2.5 inset-x-0 h-8 bg-card flex items-center gap-2 border-b border-border">
										<div class="size-3 rounded-full bg-red-500 hover:bg-red-600"></div>
										<div class="size-3 rounded-full bg-yellow-500 hover:bg-yellow-600"></div>
										<div class="size-3 rounded-full bg-green-500 hover:bg-green-600"></div>
									</div>

									<img width="2174" height="1464" class="pt-8 w-full rounded-b-xl dark:hidden"
										src="@/../images/dashboard.png" />
									<img width="2174" height="1464" class="pt-8 w-full rounded-b-xl hidden dark:block"
										src="@/../images/dashboard-dark.png" />
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Pricing Section -->
			<section class="relative py-24 sm:pt-32 sm:pb-[32rem]">
				<PricingChart :data="[]" />

				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
					<div class="md:text-center">
						<h2 class="font-display text-3xl tracking-tight text-foreground sm:text-4xl">
							Preço simples, para todos
						</h2>
						<p class="mt-4 text-lg text-muted-foreground">
							Não importa o tamanho do seu negócio, nosso sistema se adapta às suas
							necessidades.
						</p>
					</div>
					<div class="mt-16 flex justify-center">
						<div
							class="overflow-hidden border border-border bg-card shadow-sm max-w-md w-full hover:shadow-lg transition-shadow duration-300">
							<div class="p-8">
								<h3 class="font-display text-xl font-medium text-foreground">
									Plano individual
								</h3>
								<p class="mt-2 text-base text-muted-foreground">
									Para pessoas e pequenos negócios.
								</p>

								<p class="mt-6 text-sm text-muted-foreground line-through">R$ 49,90</p>

								<p class="font-display text-5xl font-light tracking-tight text-foreground">R$ 9,90<span class="text-base font-normal text-muted-foreground">/mês</span></p>

								<p class="mt-2 text-sm text-primary font-medium">Preço promocional de lançamento</p>

								<ul role="list" class="mt-10 space-y-4">
									<li class="flex items-center">
										<CircleCheck class="size-5 flex-none text-primary" />
										<span class="ml-2 text-sm text-muted-foreground">Integração com WhatsApp</span>
									</li>
									<li class="flex items-center">
										<CircleCheck class="size-5 flex-none text-primary" />
										<span class="ml-2 text-sm text-muted-foreground">Sem limite de contas</span>
									</li>
									<li class="flex items-center">
										<CircleCheck class="size-5 flex-none text-primary" />
										<span class="ml-2 text-sm text-muted-foreground">Sem limite de cartões</span>
									</li>
									<li class="flex items-center">
										<CircleCheck class="size-5 flex-none text-primary" />
										<span class="ml-2 text-sm text-muted-foreground">Sem limite de transações</span>
									</li>
								</ul>

								<Link :href="route('register')" class="mt-8 block">
								<Button class="w-full group" size="lg">
									Comece agora
									<ArrowRight
										class="ml-2 h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" />
								</Button>
								</Link>

								<p class="mt-4 text-xs text-center text-muted-foreground">
									Sem contratos. Cancele a qualquer momento.
								</p>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>

		<footer class="relative border-t border-primary bg-card">
			<FooterChart :data="[]" />

			<div class="absolute top-0 w-full h-full flex items-center justify-center mx-auto px-4 sm:px-6 lg:px-8">
				<div class="py-12 flex flex-col items-center gap-8">
					<Link href="/" class="flex items-center gap-2 group">
					<img src="@/../images/green-diamond.svg"
						class="dark:hidden h-8 w-8 transition-transform duration-300 group-hover:scale-110" />
					<img src="@/../images/green-diamond-white.svg"
						class="hidden dark:block h-8 w-8 transition-transform duration-300 group-hover:scale-110" />
					<span class="font-medium text-lg group-hover:text-primary transition-colors">JaGastei</span>
					</Link>

					<div v-if="false" class="flex items-center gap-6">
						<a href="#" class="text-muted-foreground hover:text-foreground transition-colors">
							<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
								<path fill-rule="evenodd"
									d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
									clip-rule="evenodd" />
							</svg>
						</a>
						<a href="#" class="text-muted-foreground hover:text-foreground transition-colors">
							<svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
								<path
									d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
							</svg>
						</a>
					</div>

					<p class="text-sm text-muted-foreground">
						© JaGastei 2025
					</p>

					<p class="text-sm text-muted-foreground">
						{{ $t('Made by') }} <a href="https://github.com/diego-lipinski-de-castro" target="_blank" class="text-primary hover:text-primary/80 transition-colors underline">diego-lipinski-de-castro</a>
					</p>
				</div>
			</div>
		</footer>
	</div>
</template>

<style scoped>
.fade-in {
	animation: fadeIn 0.8s ease-in-out forwards;
}

@keyframes fadeIn {
	from {
		opacity: 0;
		transform: translateY(20px);
	}

	to {
		opacity: 1;
		transform: translateY(0);
	}
}
</style>
