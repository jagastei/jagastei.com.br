<script setup>
import { ref } from 'vue';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import {
	Form,
	FormControl,
	FormField,
	FormItem,
	FormLabel,
	FormMessage,
} from '@/Components/ui/form';
import { useForm } from '@inertiajs/vue3';
import { toast } from '@/Components/ui/toast';

defineProps({
	open: {
		type: Boolean,
		default: false,
	},
});

const emit = defineEmits(['update:open']);

const form = useForm({
	message: '',
});

const submitForm = async () => {
	form.post(route('support.store'), {
		onSuccess: () => {
			toast({
				title: 'Solicitação de suporte enviada',
				description: 'Vamos te responder o mais rápido possível',
			});

			emit('update:open', false);
			form.reset();
		},
		onError: () => {
			toast({
				title: 'Erro',
				description: 'Falha ao enviar solicitação de suporte',
				variant: 'destructive',
			});
		},
	});
};
</script>

<template>
	<Dialog :open="open" @update:open="emit('update:open')">
		<DialogContent class="sm:max-w-[525px]">
			<DialogHeader>
				<DialogTitle>Contato</DialogTitle>
				<DialogDescription>
					Envie uma mensagem e vamos te responder o mais rápido possível.
				</DialogDescription>
			</DialogHeader>

			<form @submit.prevent="submitForm" class="space-y-4">
				<div>
					<Label>Mensagem</Label>
					<Textarea
						placeholder="Descreva seu problema em detalhes"
						class="mt-2 min-h-[120px]"
						v-model="form.message"
					/>
				</div>

				<div class="flex justify-end space-x-2">
					<Button type="button" variant="outline" @click="emit('update:open')">
						Cancelar
					</Button>
					<Button type="submit" :disabled="form.processing">
						Enviar mensagem
					</Button>
				</div>
			</form>
		</DialogContent>
	</Dialog>
</template>
