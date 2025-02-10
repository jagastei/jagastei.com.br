<script setup>
import { ref } from 'vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Textarea } from '@/Components/ui/textarea'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/Components/ui/form'
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group'
import { useForm } from 'vee-validate'
import { toast } from '@/Components/ui/toast'

const open = ref(false)

const form = useForm({
  initialValues: {
    rating: '',
    comment: '',
  },
})

const onSubmit = async () => {
  try {
    await axios.post(route('feedback.send'), form.values)
    toast({
      title: 'Feedback enviado',
      description: 'Obrigado pelo seu feedback!',
    })
    open.value = false
    form.resetForm()
  } catch (error) {
    toast({
      title: 'Erro',
      description: 'Falha ao enviar feedback',
      variant: 'destructive',
    })
  }
}

const ratings = [
  { value: '1', label: 'Muito Insatisfeito' },
  { value: '2', label: 'Insatisfeito' },
  { value: '3', label: 'Neutro' },
  { value: '4', label: 'Satisfeito' },
  { value: '5', label: 'Muito Satisfeito' },
]
</script>

<template>
  <Dialog v-model:open="open">
    <DialogTrigger as-child>
      <Button variant="outline">
        Dar Feedback
      </Button>
    </DialogTrigger>

    <DialogContent class="sm:max-w-[525px]">
      <DialogHeader>
        <DialogTitle>Compartilhe seu Feedback</DialogTitle>
        <DialogDescription>
          Ajude-nos a melhorar compartilhando sua experiência.
        </DialogDescription>
      </DialogHeader>

      <Form @submit="form.handleSubmit(onSubmit)" :form="form" class="space-y-6">
        <FormField
          name="rating"
          v-slot="{ field, errorMessage }"
        >
          <FormItem class="space-y-3">
            <FormLabel>Qual é o seu nível de satisfação?</FormLabel>
            <FormControl>
              <RadioGroup 
                v-model="field.value" 
                @update:model-value="field.handleChange" 
                class="flex flex-col space-y-1"
              >
                <FormItem
                  v-for="rating in ratings"
                  :key="rating.value"
                  class="flex items-center space-x-3 space-y-0"
                >
                  <FormControl>
                    <RadioGroupItem :value="rating.value" />
                  </FormControl>
                  <FormLabel class="font-normal">
                    {{ rating.label }}
                  </FormLabel>
                </FormItem>
              </RadioGroup>
            </FormControl>
            <FormMessage>{{ errorMessage }}</FormMessage>
          </FormItem>
        </FormField>

        <FormField
          name="comment"
          v-slot="{ field, errorMessage }"
        >
          <FormItem>
            <FormLabel>Comentários Adicionais</FormLabel>
            <FormControl>
              <Textarea 
                placeholder="Conte-nos mais sobre sua experiência..."
                class="min-h-[120px]"
                v-bind="field"
              />
            </FormControl>
            <FormMessage>{{ errorMessage }}</FormMessage>
          </FormItem>
        </FormField>

        <DialogFooter>
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
            Enviar Feedback
          </Button>
        </DialogFooter>
      </Form>
    </DialogContent>
  </Dialog>
</template>
