<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Alert, AlertDescription } from '@/Components/ui/alert';

defineProps<{
	mustVerifyEmail?: Boolean;
	status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
	name: user.name,
	email: user.email,
});
</script>

<template>
	<Card>
		<CardHeader>
			<CardTitle>Informações de perfil</CardTitle>
			<CardDescription>
				Atualize suas informações de perfil e endereço de e-mail.
			</CardDescription>
		</CardHeader>

		<CardContent>
			<form
				@submit.prevent="form.patch(route('profile.update'))"
				class="space-y-6"
			>
				<div class="space-y-2">
					<Label for="name">Nome</Label>
					<Input
						id="name"
						v-model="form.name"
						type="text"
						required
						autofocus
						autocomplete="name"
					/>
					<p v-if="form.errors.name" class="text-sm text-destructive">
						{{ form.errors.name }}
					</p>
				</div>

				<div class="space-y-2">
					<Label for="email">Email</Label>
					<Input
						id="email"
						v-model="form.email"
						type="email"
						required
						autocomplete="username"
					/>
					<p v-if="form.errors.email" class="text-sm text-destructive">
						{{ form.errors.email }}
					</p>
				</div>

				<Alert v-if="mustVerifyEmail && user.email_verified_at === null">
					<AlertDescription class="flex items-center justify-between">
						Seu endereço de e-mail não está verificado.
						<Button
							variant="link"
							:href="route('verification.send')"
							method="post"
							as="a"
						>
							Clique aqui para reenviar o e-mail de verificação.
						</Button>
					</AlertDescription>
				</Alert>

				<Alert v-if="status === 'verification-link-sent'">
					<AlertDescription>
						Um novo link de verificação foi enviado para seu endereço de e-mail.
					</AlertDescription>
				</Alert>

				<div class="flex items-center gap-4">
					<Button :disabled="form.processing">Salvar</Button>

					<p v-if="form.recentlySuccessful" class="text-sm text-muted-foreground">
						Salvo.
					</p>
				</div>
			</form>
		</CardContent>
	</Card>
</template>
