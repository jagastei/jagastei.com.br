<script setup>
import { ref } from 'vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/Components/ui/form'
import { useForm } from 'vee-validate'
import { toast } from '@/Components/ui/toast'

const open = ref(false)

const form = useForm({
  initialValues: {
    message: '',
  },
})

const onSubmit = async (values) => {
  try {
    await axios.post(route('support.send'), values)
    toast({
      title: 'Solicitação de suporte enviada',
      description: 'Vamos te responder o mais rápido possível',
    })
    open.value = false
    form.resetForm()
  } catch (error) {
    toast({
      title: 'Erro',
      description: 'Falha ao enviar solicitação de suporte',
      variant: 'destructive',
    })
  }
}
</script>

<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <Button variant="outline">
        Suporte
      </Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[525px]">
      <DialogHeader>
        <DialogTitle>Contato</DialogTitle>
        <DialogDescription>
          Envie uma mensagem e vamos te responder o mais rápido possível.
        </DialogDescription>
      </DialogHeader>

      <Form @submit="onSubmit" :form="form">
        <form @submit="form.handleSubmit($event, onSubmit)" class="space-y-4">
          <FormField
            name="message"
            v-slot="{ field, errorMessage }"
          >
            <FormItem>
              <FormLabel>Mensagem</FormLabel>
              <FormControl>
                <Textarea 
                  placeholder="Descreva seu problema em detalhes"
                  class="min-h-[120px]"
                  v-bind="field"
                />
              </FormControl>
              <FormMessage>{{ errorMessage }}</FormMessage>
            </FormItem>
          </FormField>

          <div class="flex justify-end space-x-2">
            <Button 
              type="button" 
              variant="outline" 
              @click="open = false"
            >
              Cancelar
            </Button>
            <Button 
              type="submit" 
              :disabled="form.isSubmitting"
            >
              Enviar Mensagem
            </Button>
          </div>
        </form>
      </Form>
    </DialogContent>
  </Dialog>
</template>
