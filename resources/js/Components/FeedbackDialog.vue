<script setup lang="ts">
import { ref } from 'vue';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
} from '@/Components/ui/dialog';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group';
import { useForm } from '@inertiajs/vue3';
import { toast } from '@/Components/ui/toast';
import Label from './ui/label/Label.vue';

defineProps({
	open: {
		type: Boolean,
		default: false,
	},
});

const emit = defineEmits(['update:open']);

const form = useForm<{
	rating: string | undefined;
	comment: string;
}>({
	rating: undefined,
	comment: '',
});

const submitForm = async () => {
	form.post(route('feedback.store'), {
		onSuccess: () => {
			toast({
				title: 'Feedback enviado',
				description: 'Obrigado pelo seu feedback!',
			});

			emit('update:open', false);
			form.reset();
		},
		onError: () => {
			toast({
				title: 'Erro',
				description: 'Falha ao enviar feedback',
				variant: 'destructive',
			});
		},
	});
};

const ratings = [
	{ value: '1', label: 'Muito insatisfeito' },
	{ value: '2', label: 'Neutro' },
	{ value: '3', label: 'Muito satisfeito' },
];
</script>

<template>
	<Dialog :open="open" @update:open="emit('update:open')">
		<DialogContent class="sm:max-w-[525px]">
			<DialogHeader>
				<DialogTitle>Compartilhe sua sugestão</DialogTitle>
				<DialogDescription>
					Ajude-nos a melhorar compartilhando sua experiência.
				</DialogDescription>
			</DialogHeader>

			<form @submit.prevent="submitForm" class="space-y-6">
				<div>
					<Label>Qual é o seu nível de satisfação?</Label>
					<div class="mt-2">
						<RadioGroup v-model="form.rating" class="flex flex-col space-y-1">
							<div
								v-for="rating in ratings"
								:key="rating.value"
								class="flex items-center space-x-3 space-y-0"
							>
								<Label class="font-normal flex items-center space-x-2">
									<RadioGroupItem :value="rating.value" />
									<span>{{ rating.label }}</span>
								</Label>
							</div>
						</RadioGroup>
					</div>
				</div>

				<div>
					<Label>Sugestão</Label>
					<Textarea
						placeholder="Digite aqui sua sugestão"
						class="min-h-[120px] mt-2"
						v-model="form.comment"
					/>
				</div>

				<DialogFooter>
					<Button
						type="button"
						variant="outline"
						@click="emit('update:open', false)"
					>
						Cancelar
					</Button>
					<Button type="submit" :disabled="form.processing">
						Enviar sugestão
					</Button>
				</DialogFooter>
			</form>
		</DialogContent>
	</Dialog>
</template>
