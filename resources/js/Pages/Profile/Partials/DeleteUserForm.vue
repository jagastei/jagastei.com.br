<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
	password: '',
});

const confirmUserDeletion = () => {
	confirmingUserDeletion.value = true;
};

const deleteUser = () => {
	form.delete(route('profile.destroy'), {
		preserveScroll: true,
		onSuccess: () => closeModal(),
		onError: () => passwordInput.value?.focus(),
		onFinish: () => form.reset(),
	});
};

const closeModal = () => {
	confirmingUserDeletion.value = false;
	form.reset();
};
</script>

<template>
	<Card>
		<CardHeader>
			<CardTitle>Deletar conta</CardTitle>
			<CardDescription>
				Uma vez que sua conta seja deletada, todos os seus recursos e dados serão
				deletados permanentemente.
			</CardDescription>
		</CardHeader>

		<CardContent>
			<Button variant="destructive" @click="confirmUserDeletion">
				Deletar conta
			</Button>

			<Dialog :open="confirmingUserDeletion" @update:open="closeModal">
				<DialogContent>
					<DialogHeader>
						<DialogTitle>Tem certeza que deseja deletar sua conta?</DialogTitle>
						<DialogDescription>
							Uma vez que sua conta seja deletada, todos os seus recursos e dados serão
							deletados permanentemente. Por favor, digite sua senha para confirmar que
							deseja deletar permanentemente sua conta.
						</DialogDescription>
					</DialogHeader>

					<form @submit.prevent="deleteUser">
						<div class="space-y-2">
							<Label for="password">Senha</Label>
							<Input
								id="password"
								ref="passwordInput"
								v-model="form.password"
								type="password"
								placeholder="Senha"
								@keyup.esc="closeModal"
                                class="focus-visible:ring-destructive"
							/>
							<p v-if="form.errors.password" class="text-sm text-destructive">
								{{ form.errors.password }}
							</p>
						</div>

						<DialogFooter class="mt-6">
							<Button variant="outline" type="button" @click="closeModal">
								Cancelar
							</Button>
							<Button variant="destructive" :disabled="form.processing">
								Deletar conta
							</Button>
						</DialogFooter>
					</form>
				</DialogContent>
			</Dialog>
		</CardContent>
	</Card>
</template>
