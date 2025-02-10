<script setup>
import { ref } from 'vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { toast } from '@/Components/ui/toast'

const open = ref(false)
const shareLink = ref('https://app.financas.com.br')
const isCopied = ref(false)

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(shareLink.value)
        isCopied.value = true
        toast({
            title: 'Sucesso',
            description: 'Link copiado para área de transferência',
        })
        setTimeout(() => {
            isCopied.value = false
        }, 2000)
    } catch (error) {
        toast({
            title: 'Erro',
            description: 'Falha ao copiar o link',
            variant: 'destructive',
        })
    }
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button variant="outline">
                Compartilhar Link
            </Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Compartilhar Acesso</DialogTitle>
                <DialogDescription>
                    Compartilhe este link para dar acesso ao seu espaço de trabalho.
                </DialogDescription>
            </DialogHeader>

            <div class="flex items-center space-x-2">
                <Input :default-value="shareLink" readonly class="flex-1" />
                <Button type="button" @click="copyToClipboard" :variant="isCopied ? 'success' : 'default'">
                    {{ isCopied ? 'Copiado!' : 'Copiar' }}
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
