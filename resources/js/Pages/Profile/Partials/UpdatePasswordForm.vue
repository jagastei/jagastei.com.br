<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
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

const form = useForm({
	current_password: '',
	password: '',
	password_confirmation: '',
});

const updatePassword = () => {
	form.put(route('password.update'), {
		preserveScroll: true,
		onSuccess: () => form.reset(),
	});
};
</script>

<template>
	<Card>
		<CardHeader>
			<CardTitle>Atualizar senha</CardTitle>
			<CardDescription>
				Certifique-se de que sua conta está usando uma senha longa e aleatória para
				manter-se seguro.
			</CardDescription>
		</CardHeader>

		<CardContent>
			<form @submit.prevent="updatePassword" class="space-y-6">
				<div class="space-y-2">
					<Label for="current_password">Senha atual</Label>
					<Input
						id="current_password"
						v-model="form.current_password"
						type="password"
						autocomplete="current-password"
					/>
					<p v-if="form.errors.current_password" class="text-sm text-destructive">
						{{ form.errors.current_password }}
					</p>
				</div>

				<div class="space-y-2">
					<Label for="password">Nova senha</Label>
					<Input
						id="password"
						v-model="form.password"
						type="password"
						autocomplete="new-password"
					/>
					<p v-if="form.errors.password" class="text-sm text-destructive">
						{{ form.errors.password }}
					</p>
				</div>

				<div class="space-y-2">
					<Label for="password_confirmation">Confirmar senha</Label>
					<Input
						id="password_confirmation"
						v-model="form.password_confirmation"
						type="password"
						autocomplete="new-password"
					/>
					<p
						v-if="form.errors.password_confirmation"
						class="text-sm text-destructive"
					>
						{{ form.errors.password_confirmation }}
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
