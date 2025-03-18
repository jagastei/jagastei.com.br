<script setup lang="ts">
import { useForm, usePage, router } from '@inertiajs/vue3';
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
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert';
import { useToast } from '@/Components/ui/toast/use-toast'

const props = defineProps<{
	mustVerifyEmail?: Boolean;
	status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
	name: user.name,
	email: user.email,
	phone: user.phone,
});

const verifyEmail = () => {
	router.post(route('verification.send'), {}, {
		preserveScroll: true,
		preserveState: true,
	})
}

const verifyPhone = () => {
	router.post(route('phone.verification.send'), {}, {
		preserveScroll: true,
		preserveState: true,
	})
}

</script>

<template>	
	<Alert v-if="mustVerifyEmail && user.email_verified_at === null">
		<AlertDescription class="flex items-center justify-between">
			Seu endereço de e-mail não está verificado.

			<p v-if="status === 'verification-link-sent'" class="h-6 content-center">
				Um novo link de verificação foi enviado para seu endereço de e-mail.
			</p>

			<Button v-else @click="verifyEmail" variant="link" class="h-6 p-0">
				Clique aqui para reenviar o e-mail de verificação.
			</Button>
		</AlertDescription>
	</Alert>

	<Alert v-if="user.phone_verified_at === null">
		<AlertDescription class="flex items-center justify-between">
			Seu número de telefone não está verificado.

			<p v-if="status === 'verification-link-sent'" class="h-6 content-center">
				Um novo link de verificação foi enviado para seu número de telefone.
			</p>

			<Button v-else @click="verifyPhone" variant="link" class="h-6 p-0">
				Clique aqui para reenviar um código de verificação.
			</Button>
		</AlertDescription>
	</Alert>
	
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

				<div class="space-y-2">
					<Label for="phone">Telefone</Label>
					<Input
						id="phone"
						v-model="form.phone"
						type="tel"
						required
						autocomplete="tel"
					/>
					<p v-if="form.errors.phone" class="text-sm text-destructive">
						{{ form.errors.phone }}
					</p>
				</div>

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
