<script setup lang="ts">
import BaseLayout from '@/Layouts/Base.vue'
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next'
import InputError from '@/Components/InputError.vue';

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <BaseLayout>

        <Head title="Criar nova senha" />

        <div class="min-h-screen flex flex-col justify-center items-center">

            <Card class="mx-auto w-1/3 max-w-sm">
                <CardHeader>
                    <CardTitle class="text-2xl">
                        Criar nova senha
                    </CardTitle>
                    <CardDescription>
                        Digite sua nova senha abaixo
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

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="password">Senha</Label>
                                <Input v-model="form.password" id="password" type="password" tabindex="2"/>
                                <InputError class="mt-2" :message="form.errors.password"/>
                            </div>

                            <div class="grid gap-2" v-auto-animate>
                                <Label for="password_confirmation">Confirmação de senha</Label>
                                <Input v-model="form.password_confirmation" id="password_confirmation" type="password" tabindex="3"/>
                                <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                            </div>

                            <Button :disabled="form.processing" type="submit" class="w-full" tabindex="4">
                                <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                                Confirmar
                            </Button>
                        </div>
                    </form>
                    <div class="mt-4 text-center text-sm">
                        <Link :href="route('register')" class="underline" tabindex="5">
                            Voltar ao início
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </BaseLayout>
</template>