<script setup lang="ts">
import BaseLayout from '@/Layouts/Base.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next'
import InputError from '@/Components/InputError.vue';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <BaseLayout>

        <Head title="Esqueci minha senha" />

        <div class="min-h-screen flex flex-col justify-center items-center">

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <Card class="mx-auto max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Esqueceu sua senha?
                    </CardTitle>
                    <CardDescription>
                        Sem problemas. Informe seu e-mail e enviaremos um link para você criar uma nova senha.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="grid gap-4">
                            <div class="grid gap-2" v-auto-animate>
                                <Label for="email">E-mail</Label>
                                <Input v-model="form.email" id="email" type="email" placeholder="usuario@dominio.com" tabindex="1"/>
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <Button :disabled="form.processing" type="submit" class="w-full" tabindex="2">
                                <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                Enviar
                            </Button>
                        </div>
                    </form>
                    <div class="mt-4 text-center text-sm">
                        <Link :href="route('login')" class="underline" tabindex="3">
                            Voltar ao início
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </BaseLayout>
</template>