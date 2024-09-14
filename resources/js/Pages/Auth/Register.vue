<script setup lang="ts">
import BaseLayout from '@/Layouts/Base.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next'
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: 'Test user',
    email: 'test@example.com',
    password: 'password',
    password_confirmation: 'password',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <BaseLayout>

        <Head title="Cadastrar" />

        <div class="min-h-screen flex flex-col justify-center items-center">

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <Card class="mx-auto max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Cadastrar
                    </CardTitle>
                    <CardDescription>
                        Preencha o formulário abaixo para criar sua conta
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit">
                        <div class="grid gap-4">

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="name">Nome</Label>
                                <Input v-model="form.name" id="name" type="text" placeholder="João" tabindex="1"/>
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="email">E-mail</Label>
                                <Input v-model="form.email" id="email" type="email" placeholder="usuario@dominio.com" tabindex="2"/>
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="password">Senha</Label>
                                <Input v-model="form.password" id="password" type="password" tabindex="3"/>
                                <InputError class="mt-2" :message="form.errors.password"/>
                            </div>

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="password_confirmation">Confirmação de senha</Label>
                                <Input v-model="form.password_confirmation" id="password_confirmation" type="password" tabindex="4"/>
                                <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                            </div>

                            <Button :disabled="form.processing" type="submit" class="w-full" tabindex="5">
                                <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                Cadastrar
                            </Button>
                        </div>
                    </form>
                    <div class="mt-4 text-center text-sm">
                        Já tem uma conta?
                        <Link :href="route('login')" class="underline" tabindex="6">
                            Entrar
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </BaseLayout>
</template>