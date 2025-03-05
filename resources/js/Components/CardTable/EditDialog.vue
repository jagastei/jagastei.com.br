<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useFuse } from '@vueuse/integrations/useFuse';
import { useForm } from '@inertiajs/vue3';
import { cn } from '@/utils';
import {
	Dialog,
	DialogContent,
	DialogDescription,
	DialogFooter,
	DialogHeader,
	DialogTitle,
	DialogTrigger,
} from '@/Components/ui/dialog';
import { Check, ChevronsUpDown, Loader2 } from 'lucide-vue-next';
import {
	Command,
	CommandEmpty,
	CommandGroup,
	CommandInput,
	CommandItem,
	CommandList,
} from '@/Components/ui/command';
import {
	Popover,
	PopoverContent,
	PopoverTrigger,
} from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Card } from '@/Components/CardTable/columns';

const props = defineProps<{
	card: Card;
	open: boolean;
}>();

const emit = defineEmits(['close']);

const accountDialogOpen = ref(false);

const form = useForm<{
	name: string;
	limit: number;
}>({
	name: props.card.name,
	limit: props.card.limit,
});

const submit = () => {
	form.put(route('cards.update', props.card.id), {
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
				<DialogTitle>Editar cartão</DialogTitle>
				<DialogDescription> Edite as informações do cartão. </DialogDescription>
			</DialogHeader>
			<div class="grid gap-4 py-4">
				<div class="flex flex-col">
					<Label for="name" class="text-left"> Nome </Label>
					<Input
						id="name"
						v-model="form.name"
						placeholder="Cartão principal"
						class="mt-2"
						autocomplete="off"
						tabindex="1"
					/>
				</div>

				<div class="flex flex-col">
					<Label for="limit" class="text-left"> Limite </Label>

					<Input
						id="limit"
						v-model.lazy="form.limit"
						class="mt-2"
						v-money3="{
							prefix: 'R$',
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
