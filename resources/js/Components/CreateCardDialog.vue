<script setup lang="ts">
import {
	Dialog,
	DialogContent,
	DialogHeader,
	DialogTitle,
	DialogDescription,
	DialogFooter,
} from '@/Components/ui/dialog';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Loader2 } from 'lucide-vue-next';
import useAxios from '@/composables/useAxios';
import type { Category } from './CategoryTable/columns';

defineProps<{
	open: boolean;
}>();

const emit = defineEmits(['close', 'success']);

const form: any = useAxios({
	name: '',
	color: '#22C55E',
	type: 'OUT',
});

const submit = () => {
	form.post(route('api.categories.store'), {
		onSuccess: (res: any) => {
			emit('success', res.data as Category);
			form.reset();
		},
		onError: (errors: any) => {
			console.log(errors);
		},
	});
};
</script>

<template>
	<Dialog
		:open="open"
		@update:open="
			(value) => {
				if (!value) {
					emit('close');
					form.reset();
				}
			}
		"
	>
		<DialogContent class="sm:max-w-[425px]">
			<DialogHeader>
				<DialogTitle>Adicionar categoria</DialogTitle>
				<DialogDescription>
					Adicione uma nova categoria para gerenciar suas entradas.
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
