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
import { Budget } from './columns';

const props = defineProps<{
	budget: Budget;
	open: boolean;
}>();

const emit = defineEmits(['close']);

const form = useForm<{
	name: string;
	total: number;
}>({
	name: props.budget.name,
	total: props.budget.total,
});

const submit = () => {
	form.put(route('budgets.update', props.budget.id), {
		onFinish: () => {
			onClose();
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
		<DialogTrigger as-child>
			<slot />
		</DialogTrigger>
		<DialogContent
			class="sm:max-w-[425px]"
			@interactOutside="onClose"
			@escapeKeyDown="onClose"
		>
			<DialogHeader>
				<DialogTitle>Editar orçamento</DialogTitle>
				<DialogDescription> Edite as informações do orçamento. </DialogDescription>
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
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="total" class="text-left"> Valor </Label>

					<Input
						id="total"
						v-model.lazy="form.total"
						class="mt-2"
						v-money3="{
							prefix: 'R$ ',
							suffix: '',
							thousands: '.',
							decimal: ',',
							precision: 2,
							disableNegative: false,
							disabled: false,
							min: null,
							max: null,
							allowBlank: false,
							minimumNumberOfCharacters: 0,
							shouldRound: false,
							focusOnRight: false,
							modelModifiers: {
								number: false,
							},
						}"
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
