<script setup>
import { ScrollArea } from '@/Components/ui/scroll-area';

defineProps({
	data: {
		type: Object,
		required: true,
	},
});
</script>

<template>
	<div>
		<ScrollArea class="w-full h-96 rounded-md border p-4">
			<!-- Header -->
			<div class="space-y-2 text-center mb-6">
				<h2 class="font-bold text-xl">{{ data.empresa }}</h2>
				<p class="text-sm text-muted-foreground">{{ data.localizacao }}</p>
				<div class="flex justify-between text-sm">
					<span>Data: {{ data.data }}</span>
					<span>Pagamento: {{ data.metodo_pagamento }}</span>
				</div>
				<p class="text-sm text-muted-foreground">{{ data.categoria }}</p>
			</div>

			<!-- Items -->
			<div class="space-y-4">
				<div
					v-for="(item, index) in data.itens"
					:key="index"
					class="flex justify-between py-2 border-b last:border-b-0"
				>
					<div class="space-y-1">
						<p class="font-medium">{{ item.descricao }}</p>
						<p class="text-sm text-muted-foreground">{{ item.categoria }}</p>
						<p class="text-sm text-muted-foreground">{{ item.quantidade }}</p>
					</div>
					<div class="text-right">
						<p>R$ {{ item.valor }}</p>
						<p class="text-sm text-muted-foreground">Total: R$ {{ item.total }}</p>
					</div>
				</div>
			</div>

			<!-- Footer -->
			<div class="mt-6 pt-4 border-t">
				<div class="flex justify-between items-center">
					<span class="font-bold text-lg">Total</span>
					<span class="font-bold text-lg">{{ data.total }}</span>
				</div>
			</div>
		</ScrollArea>
	</div>
</template>
