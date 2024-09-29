<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/Components/ui/button';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';

defineProps<{
	canResetPassword?: boolean;
	status?: string;
}>();

const form = useForm({
	email: 'test@example.com',
	password: 'password',
});

const submit = () => {
	form.post(route('login'), {
		onFinish: () => {
			form.reset('password');
		},
	});
};
</script>

<template>
	<GuestLayout>
		<Head title="Entrar" />

		<div class="min-h-screen flex flex-col justify-center items-center">
			<div v-if="status" class="mb-4 font-medium text-sm text-green-600">
				{{ status }}
			</div>

			<Card class="mx-auto max-w-sm">
				<CardHeader>
					<CardTitle class="text-2xl"> Entrar </CardTitle>
					<CardDescription>
						Digite seu e-mail abaixo para entrar na sua conta
					</CardDescription>
				</CardHeader>
				<CardContent>
					<form @submit.prevent="submit">
						<div class="grid gap-4">
							<div class="grid gap-2" v-auto-animate>
								<Label for="email">E-mail</Label>
								<Input
									v-model="form.email"
									id="email"
									type="email"
									placeholder="usuario@dominio.com"
									tabindex="1"
								/>
								<InputError class="mt-2" :message="form.errors.email" />
							</div>

							<div class="grid gap-2" v-auto-animate>
								<div class="flex items-center">
									<Label for="password">Senha</Label>
									<Link
										:href="route('password.request')"
										class="ml-auto inline-block text-sm underline"
										tabindex="5"
									>
										Esqueceu sua senha?
									</Link>
								</div>
								<Input
									v-model="form.password"
									id="password"
									type="password"
									tabindex="2"
								/>
								<InputError class="mt-2" :message="form.errors.password" />
							</div>

							<Button
								:disabled="form.processing"
								type="submit"
								class="w-full"
								tabindex="3"
							>
								<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
								Entrar
							</Button>
						</div>
					</form>
					<div class="mt-4 text-center text-sm">
						Não tem uma conta?
						<Link :href="route('register')" class="underline" tabindex="4">
							Cadastre-se
						</Link>
					</div>
				</CardContent>
			</Card>
		</div>
	</GuestLayout>
</template>
