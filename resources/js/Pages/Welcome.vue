<script setup lang="ts">
import Button from '@/Components/ui/button/Button.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useColorMode } from '@vueuse/core';
import { CircleCheck, ArrowRight } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
	canLogin?: boolean;
	canRegister?: boolean;
	laravelVersion: string;
	phpVersion: string;
}>();

useColorMode();

const selectedFeature = ref('payroll');

const features = [
	{
		id: 'payroll',
		title: 'Folha de Pagamento',
		description: 'Acompanhe os salários de todos e se foram pagos. Depósito direto não suportado.'
	},
	{
		id: 'expenses',
		title: 'Despesas',
		description: 'Registre e categorize todas as suas despesas. Visualize para onde seu dinheiro está indo.'
	},
	{
		id: 'reports',
		title: 'Relatórios',
		description: 'Gere relatórios detalhados para entender seus padrões de gastos e receitas.'
	},
	{
		id: 'settings',
		title: 'Configurações',
		description: 'Personalize o sistema de acordo com as necessidades do seu negócio.'
	}
];
</script>

<template>
	<Head title="Welcome" />

	<div class="flex flex-col scroll-smooth">
		<header class="py-6 sticky top-0 bg-background/80 backdrop-blur-sm z-50 border-b border-border/40">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<nav class="relative flex justify-between items-center">
					<div class="flex items-center md:gap-x-12">
						<Link href="/" class="flex items-center gap-2">
							<img src="@/../images/green-diamond.svg" class="dark:hidden h-10 w-10" />
							<img
								src="@/../images/green-diamond-white.svg"
								class="hidden dark:block h-10 w-10"
							/>
							<span class="font-medium text-xl hidden sm:inline-block">JaGastei</span>
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
			<!-- Hero Section -->
			<div class="relative overflow-hidden">
				<div class="absolute inset-0 bg-gradient-to-b from-background to-background/5 dark:from-background dark:to-background/20 -z-10"></div>
				<div
					class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center py-24 lg:py-32"
				>
					<div class="flex flex-col items-center">
						<h1
							class="mx-auto max-w-4xl font-display text-5xl font-medium tracking-tight text-foreground sm:text-6xl lg:text-7xl"
						>
							Gestão financeira <span class="text-primary">simplificada</span>
						</h1>

						<p
							class="mx-auto mt-8 max-w-2xl text-xl tracking-tight text-muted-foreground"
						>
							Nosso sistema de controle de gastos é fácil de usar e ajuda você a manter
							suas finanças em ordem.
						</p>

						<div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
							<Link :href="route('register')">
								<Button size="lg" class="px-8">Comece agora <ArrowRight class="ml-2 h-4 w-4" /></Button>
							</Link>
							<Button variant="outline" size="lg">Saiba mais</Button>
						</div>
					</div>
				</div>
			</div>

			<!-- Features Section -->
			<section
				id="features"
				aria-label="Features for running your books"
				class="relative overflow-hidden py-24 sm:py-32 bg-muted/30"
			>
				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
					<div class="max-w-2xl md:mx-auto md:text-center xl:max-w-none">
						<h2
							class="font-display text-3xl tracking-tight text-foreground sm:text-4xl md:text-5xl"
						>
							Tudo que você precisa para gerenciar suas finanças
						</h2>
						<p class="mt-6 text-lg tracking-tight text-muted-foreground">
							Ferramentas simples e poderosas para controlar seus gastos e receitas de forma eficiente.
						</p>
					</div>
					<div
						class="mt-16 grid grid-cols-1 items-center gap-x-8 gap-y-10 lg:grid-cols-12"
					>
						<div
							class="flex flex-col gap-y-6 lg:col-span-5"
						>
							<div
								class="flex flex-col gap-y-4"
							>
								<div v-for="feature in features" :key="feature.id" 
									:class="[
										'relative rounded-xl p-6 transition-all duration-200 hover:bg-card/80',
										selectedFeature === feature.id ? 'bg-card shadow-sm' : ''
									]"
									@click="selectedFeature = feature.id"
								>
									<h3
										class="font-display text-lg font-medium text-foreground"
									>
										{{ feature.title }}
									</h3>
									<p class="mt-2 text-sm text-muted-foreground">
										{{ feature.description }}
									</p>
								</div>
							</div>
						</div>
						<div class="lg:col-span-7">
							<div class="overflow-hidden rounded-xl border border-border bg-background shadow-sm">
								<div class="relative">
									<div
										class="absolute top-0 pl-2.5 inset-x-0 h-8 bg-card flex items-center gap-2"
									>
										<div class="size-3 rounded-full bg-red-500 hover:bg-red-600"></div>
										<div
											class="size-3 rounded-full bg-yellow-500 hover:bg-yellow-600"
										></div>
										<div
											class="size-3 rounded-full bg-green-500 hover:bg-green-600"
										></div>
									</div>

									<img
										width="2174"
										height="1464"
										class="pt-8 w-full rounded-b-xl"
										src="@/../images/dashboard.png"
									/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Pricing Section -->
			<section id="pricing" aria-label="Pricing" class="py-24 sm:py-32">
				<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
					<div class="md:text-center">
						<h2
							class="font-display text-3xl tracking-tight text-foreground sm:text-4xl"
						>
							Preço simples, para todos
						</h2>
						<p class="mt-4 text-lg text-muted-foreground">
							Não importa o tamanho do seu negócio, nosso software é adaptável às suas necessidades.
						</p>
					</div>
					<div class="mt-16 flex justify-center">
						<div class="overflow-hidden rounded-2xl border border-border bg-card shadow-sm max-w-md w-full">
							<div class="p-8">
								<h3 class="font-display text-xl font-medium text-foreground">Plano Negócios</h3>
								<p class="mt-2 text-base text-muted-foreground">
									Perfeito para pequenas e médias empresas.
								</p>
								<p class="mt-6 font-display text-5xl font-light tracking-tight text-foreground">
									R$ 49,90<span class="text-base font-normal text-muted-foreground">/mês</span>
								</p>
								
								<ul role="list" class="mt-10 space-y-4">
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Envie 25 orçamentos e faturas</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Conecte até 5 contas bancárias</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Acompanhe até 50 despesas por mês</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Suporte automatizado de folha de pagamento</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Exporte até 12 relatórios</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Reconciliação em massa de transações</span>
									</li>
									<li class="flex items-start">
										<CircleCheck class="size-5 flex-none text-primary mt-0.5" />
										<span class="ml-3 text-sm text-muted-foreground">Acompanhe em múltiplas moedas</span>
									</li>
								</ul>
								
								<Link :href="route('register')" class="mt-8 block">
									<Button class="w-full" size="lg">Comece agora</Button>
								</Link>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>

		<footer class="border-t border-border/40 bg-muted/30">
			<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
				<div class="py-12 flex flex-col items-center gap-8">
					<Link href="/" class="flex items-center gap-2">
						<img src="@/../images/green-diamond.svg" class="dark:hidden h-8 w-8" />
						<img
							src="@/../images/green-diamond-white.svg"
							class="hidden dark:block h-8 w-8"
						/>
						<span class="font-medium text-lg">JaGastei</span>
					</Link>
					
					<div class="flex gap-8 text-sm text-muted-foreground">
						<a href="#" class="hover:text-foreground transition-colors">Sobre</a>
						<a href="#" class="hover:text-foreground transition-colors">Recursos</a>
						<a href="#" class="hover:text-foreground transition-colors">Preços</a>
						<a href="#" class="hover:text-foreground transition-colors">Contato</a>
					</div>
					
					<p class="text-sm text-muted-foreground">
						2025 JaGastei © Todos os direitos reservados.
					</p>
				</div>
			</div>
		</footer>
	</div>
</template>
