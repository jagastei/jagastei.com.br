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
import { computed } from 'vue';

const props = defineProps<{
	status?: string;
}>();

const form = useForm({});

const submit = () => {
	form.post(route('verification.send'));
};

const verificationLinkSent = computed(
	() => props.status === 'verification-link-sent'
);
</script>

<template>
	<GuestLayout>
		<Head title="Verificar e-mail" />

		<div class="min-h-screen flex flex-col justify-center items-center">
			<div
				class="mb-4 font-medium text-sm text-green-600 max-w-sm text-center"
				v-if="verificationLinkSent"
			>
				A new verification link has been sent to the email address you provided
				during registration.
			</div>

			<Card class="mx-auto max-w-sm">
				<CardHeader>
					<CardTitle class="text-2xl"> Verificar e-mail </CardTitle>
					<CardDescription>
						Obrigado pelo cadastro! Antes de começar, você precisa verificar seu
						e-mail clicando no link que enviamos para você. Caso não tenha recebido um
						e-mail, clique no botão abaixo.
					</CardDescription>
				</CardHeader>
				<CardContent>
					<form @submit.prevent="submit">
						<div class="grid gap-4">
							<Button
								:disabled="form.processing"
								type="submit"
								class="w-full"
								tabindex="1"
							>
								<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
								Reenviar e-mail de verificação
							</Button>
						</div>
					</form>
					<div class="mt-4 text-center text-sm">
						<Link
							:href="route('logout')"
							method="post"
							as="button"
							class="underline"
							tabindex="2"
						>
							Sair
						</Link>
					</div>
				</CardContent>
			</Card>
		</div>
	</GuestLayout>
</template>
