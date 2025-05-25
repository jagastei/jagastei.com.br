<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
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
import { Account } from '@/Components/AccountTable/columns';
import { Brand } from '@/Components/CardTable/columns';
import SelectAccountDialog from '@/Components/SelectAccountDialog.vue';
import InputError from '@/Components/InputError.vue';
import { currencyInputFormat } from '@/utils/currencyInputFormat';
import { useLanguageStore } from '@/stores/languageStore';
import { findCardBrand } from '@/utils/findCardBrand';
import SelectBrandDialog from '@/Components/SelectBrandDialog.vue';
import { vMaska } from "maska/vue"
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/Components/ui/tooltip';
import { TooltipArrow } from 'reka-ui';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select'
import { useTranslation } from 'i18next-vue';

defineProps<{
    brands: Brand[];
    accounts: Account[];
    open: boolean;
}>();

const emit = defineEmits(['close']);

const languageStore = useLanguageStore();
const user = usePage().props.auth.user;

const { t } = useTranslation();

const months = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
];

const years = Array.from({ length: 15 }, (_, i) => new Date().getFullYear() + i);

const form = useForm<{
    account: Account | undefined;
    name: string;
    limit: number;
    digits: string;
    brand: Brand | undefined;
    expiration_month: number | null;
    expiration_year: number | null;
    virtual: boolean;
    credit: boolean;
    international: boolean;
}>({
    account: undefined,
    name: '',
    limit: 0,

    digits: '5555500830030331',
    brand: undefined,
    expiration_month: null,
    expiration_year: null,

    virtual: false,
    credit: false,
    international: false,
});

const submit = () => {
    form
        .transform((data) => ({
            ...data,
            brand: data.brand?.id,
            account: data.account?.id,
        }))
        .post(route('cards.store'), {
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
        <DialogContent class="sm:max-w-[425px]" @interactOutside="onClose" @escapeKeyDown="onClose">
            <DialogHeader>
                <DialogTitle>Adicionar cartão</DialogTitle>
                <DialogDescription>
                    Identifique seu cartão e clique em adicionar.
                </DialogDescription>
            </DialogHeader>
            <div class="grid gap-4 py-4">
                <div class="flex flex-col">
                    <Label for="name" class="text-left"> Nome </Label>
                    <Input id="name" v-model="form.name" placeholder="Cartão principal" class="mt-2" autocomplete="off"
                        tabindex="1" />
                </div>

                <div class="flex flex-col">
                    <Label for="limit" class="text-left"> Limite </Label>

                    <Input id="limit" v-model.lazy="form.limit" class="mt-2"
                        v-money3="currencyInputFormat(user.current_wallet?.currency, languageStore.getCurrentLanguage)"
                        tabindex="2" />
                </div>

                <div class="flex flex-col">
                    <Label for="account">Conta</Label>
                    <SelectAccountDialog v-model="form.account" :accounts="accounts" />

                    <InputError class="mt-2" :message="form.errors.account" />
                </div>

                <div class="flex flex-col">
                    <Label for="digits" class="text-left"> Número </Label>
                    <Input id="digits" v-model="form.digits" placeholder="1234 5678 9012 3456" class="mt-2"
                        autocomplete="off" tabindex="3" v-maska="'#### #### #### ####'" />
                </div>

                <div class="flex flex-col">
                    <div class="flex items-center">
                        <Label for="brand">Bandeira</Label>

                        <TooltipProvider>
                            <Tooltip>
                                <TooltipTrigger as-child>
                                    <button @click="$emitter.emit('open-support-dialog')"
                                        class="ml-auto inline-block text-sm underline">
                                        Não encontrou a bandeira do seu cartão?
                                    </button>
                                </TooltipTrigger>
                                <TooltipContent>
                                    Envie uma mensagem solicitando o cadastro da bandeira do seu cartão.
                                    <TooltipArrow class="fill-border" />
                                </TooltipContent>
                            </Tooltip>
                        </TooltipProvider>
                    </div>

                    <SelectBrandDialog v-model="form.brand" :brands="brands"
                        :suggested-brand-identifier="findCardBrand(form.digits)" />

                    <InputError class="mt-2" :message="form.errors.brand" />
                </div>

                <div class="flex flex-col">
                    <Label for="expiration_date" class="text-left"> Data de validade </Label>
                    <!-- <Input id="expiration_date" v-model="form.expiration_month" placeholder="MM" class="mt-2"
                        autocomplete="off" /> -->

                    <div class="mt-2 flex items-center gap-2">
                        <Select>
                            <SelectTrigger>
                                <SelectValue placeholder="Mês" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="(month, index) in months" :key="index" :value="index">
                                        {{ t(month) }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <Select>
                            <SelectTrigger>
                                <SelectValue placeholder="Ano" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="(year, index) in years" :key="index" :value="index">
                                        {{ year }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button :disabled="form.processing" @click="submit" type="button">
                    <Loader2 v-show="form.processing" class="w-4 h-4 mr-2 animate-spin" />
                    Adicionar
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
