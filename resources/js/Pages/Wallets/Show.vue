<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
	Select,
	SelectContent,
	SelectItem,
	SelectTrigger,
	SelectValue,
} from '@/Components/ui/select';
import {
	AlertDialog,
	AlertDialogAction,
	AlertDialogCancel,
	AlertDialogContent,
	AlertDialogDescription,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogTitle,
	AlertDialogTrigger,
} from '@/Components/ui/alert-dialog';
import {
	Card,
	CardContent,
	CardDescription,
	CardHeader,
	CardTitle,
} from '@/Components/ui/card';
import { Separator } from '@/Components/ui/separator';
import InputError from '@/Components/InputError.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import Dialog from '@/Components/ui/dialog/Dialog.vue';
import DialogTrigger from '@/Components/ui/dialog/DialogTrigger.vue';
import DialogContent from '@/Components/ui/dialog/DialogContent.vue';
import DialogHeader from '@/Components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/Components/ui/dialog/DialogTitle.vue';
import DialogDescription from '@/Components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/Components/ui/dialog/DialogFooter.vue';
import { Loader2 } from 'lucide-vue-next';

const props = defineProps<{
	wallet: {
		id: string;
		name: string;
		currency: string;
	};
}>();

const currencies = {
	BRL: 'Real Brasileiro',
	USD: 'Dólar Americano',
	EUR: 'Euro',
	GBP: 'Libra Esterlina',
};

const form = useForm({
	name: props.wallet.name,
	currency: props.wallet.currency,
});

const deleteForm = useForm({});

const submit = () => {
	form.put(route('wallets.update', props.wallet.id), {
		preserveScroll: true,
		onSuccess: () => {},
	});
};

const confirmingWalletDeletion = ref(false);

const confirmWalletDeletion = () => {
	confirmingWalletDeletion.value = true;
};

const deleteWallet = () => {
	deleteForm.delete(route('wallets.destroy', props.wallet.id), {
		preserveScroll: true,
		onSuccess: () => closeModal(),
	});
};

const closeModal = () => {
	confirmingWalletDeletion.value = false;
};
</script>

<template>
	<Head title="Carteira" />

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col p-4 lg:p-6 gap-4 lg:gap-6">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Carteira</h2>
				</div>
			</div>

			<div class="space-y-6">
				<Card>
					<CardHeader>
						<CardTitle>Informações Gerais</CardTitle>
						<CardDescription>
							Atualize as informações básicas da sua carteira
						</CardDescription>
					</CardHeader>
					<CardContent>
						<form @submit.prevent="submit" class="space-y-6">
							<div class="grid gap-6">
								<div class="space-y-2">
									<Label for="name">Nome</Label>
									<Input
										id="name"
										v-model="form.name"
										type="text"
										:disabled="form.processing"
										maxlength="30"
									/>
									<InputError :message="form.errors.name" />
								</div>

								<div class="space-y-2">
                                    <Label for="currency">Moeda</Label>
                                    <Select v-model="form.currency" :disabled="form.processing">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Selecione uma moeda" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="(name, code) in currencies" :key="code" :value="code">
                                                {{ code }} - {{ name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.currency" />
                                </div>
							</div>

							<Button type="submit" :disabled="form.processing">
								<Loader2 v-if="form.processing" class="size-4 mr-2 animate-spin" />
								Salvar alterações
							</Button>
						</form>
					</CardContent>
				</Card>

				<Card>
					<CardHeader>
						<CardTitle>Deletar carteira</CardTitle>
						<CardDescription>
							Ações irreversíveis que podem afetar sua carteira
						</CardDescription>
					</CardHeader>
					<CardContent>
						<Button
							variant="destructive"
							:disabled="deleteForm.processing"
							@click="confirmWalletDeletion"
						>
							Deletar carteira
						</Button>

						<Dialog :open="confirmingWalletDeletion" @update:open="closeModal">
							<DialogContent>
								<DialogHeader>
									<DialogTitle>
										Tem certeza que deseja deletar esta carteira?
									</DialogTitle>
									<DialogDescription>
										Esta ação não pode ser desfeita. Todos os dados relacionados a esta
										carteira serão excluídos permanentemente.
									</DialogDescription>
								</DialogHeader>

								<DialogFooter>
									<Button variant="outline" type="button" @click="closeModal">
										Cancelar
									</Button>

									<Button
										variant="destructive"
										:disabled="deleteForm.processing"
										@click="deleteWallet"
									>
										<Loader2
											v-if="deleteForm.processing"
											class="size-4 mr-2 animate-spin"
										/>
										Excluir carteira
									</Button>
								</DialogFooter>
							</DialogContent>
						</Dialog>
					</CardContent>
				</Card>
			</div>
		</div>
	</AuthenticatedLayout>
</template>
