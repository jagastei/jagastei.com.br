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
import { toast } from '@/Components/ui/toast';

const props = defineProps({
	open: {
		type: Boolean,
		default: false,
	},
});

const emit = defineEmits(['update:open']);

const inviteLink = ref('https://app.financas.com.br/convite');
const isCopied = ref(false);

const copyToClipboard = async () => {
	try {
		await navigator.clipboard.writeText(inviteLink.value);
		isCopied.value = true;
		toast({
			title: 'Sucesso',
			description: 'Link de convite copiado',
		});
		setTimeout(() => {
			isCopied.value = false;
		}, 2000);
	} catch (error) {
		toast({
			title: 'Erro',
			description: 'Falha ao copiar o link de convite',
			variant: 'destructive',
		});
	}
};
</script>

<template>
	<Dialog :open="open" @update:open="emit('update:open')">
		<DialogContent class="sm:max-w-[425px]">
			<DialogHeader>
				<DialogTitle>Convidar usuário</DialogTitle>
				<DialogDescription>
					Compartilhe este link de convite para que outros usuários possam se juntar
					ao sistema.
				</DialogDescription>
			</DialogHeader>

			<div class="flex items-center space-x-2">
				<Input :default-value="inviteLink" readonly class="flex-1" />
				<Button
					type="button"
					@click="copyToClipboard"
					:variant="isCopied ? 'success' : 'default'"
				>
					{{ isCopied ? 'Copiado!' : 'Copiar' }}
				</Button>
			</div>
		</DialogContent>
	</Dialog>
</template>
