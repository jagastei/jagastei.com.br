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

defineProps<{
	open: boolean;
}>();

const emit = defineEmits(['close']);

const form = useForm<{
	name: string;
	color: string;
}>({
	name: '',
	color: '#22C55E',
});

const submit = () => {
	form.post(route('categories.store'), {
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
	<Dialog :open="open">
		<DialogTrigger as-child>
			<slot />
		</DialogTrigger>
		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Adicionar categoria</DialogTitle>
				<DialogDescription>
					Adicionar uma categoria para identificar e analisar melhor seus gastos.
				</DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Alimentação"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="color" class="text-left w-fit"> Cor </Label>
					<Input
						id="color"
						type="color"
						v-model="form.color"
						class="mt-2 cursor-pointer"
						tabindex="2"
					/>
				</div>
			</div>
			<DialogFooter>
				<Button
					:disabled="form.processing"
					@click="submit"
					type="button"
					tabindex="3"
				>
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Adicionar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
