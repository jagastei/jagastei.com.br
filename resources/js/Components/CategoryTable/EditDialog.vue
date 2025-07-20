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
import { Category } from './columns';

const props = defineProps<{
	open: boolean;
	category: Category;
}>();

const emit = defineEmits(['close']);

const form = useForm<{
	name: string;
	color: string;
}>({
	name: props.category.name,
	color: props.category.color ?? '#22C55E',
});

const submit = () => {
	form.put(route('categories.update', props.category.id), {
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
				<DialogTitle>Editar categoria</DialogTitle>
				<DialogDescription>
					Edite os dados da categoria e clique em salvar.
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
				<Button variant="outline" @click="onClose" type="button"> Cancelar </Button>

				<Button :disabled="form.processing" @click="submit" type="button">
					<Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
					Salvar
				</Button>
			</DialogFooter>
		</DialogContent>
	</Dialog>
</template>
