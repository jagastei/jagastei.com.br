<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { Loader2 } from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Account } from './columns';

const props = defineProps<{
	open: boolean;
	account: Account;
}>();

const emit = defineEmits(['close']);

const form = useForm<{
	name: string;
}>({
	name: props.account.name,
});

const submit = () => {
	form.put(route('accounts.update', props.account.id), {
		onSuccess: () => {
			onClose();
		},
		onError: (error) => {
			console.log(error);
		},
	});
};

const onClose = () => {
	form.reset();
	emit('close');
};
</script>

<template>
	<Dialog :open="open" @update:open="onClose">
		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Editar conta</DialogTitle>
				<DialogDescription>
					Edite os dados da conta e clique em salvar.
				</DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Conta principal"
						class="mt-2"
						autocomplete="off"
					/>
				</div>
			</div>
			<DialogFooter>
				<Button variant="outline" @click="onClose" type="button"> Cancelar </Button>

				<Button :disabled="form.processing" @click="submit" type="button">
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Salvar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
