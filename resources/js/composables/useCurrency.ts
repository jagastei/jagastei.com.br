import { usePage } from '@inertiajs/vue3';
import { useTranslation } from 'i18next-vue';

export function useCurrency(amount: number): string {
	const user = usePage().props.auth.user;
	const { t } = useTranslation();

	return t('formatMoney', {
		value: amount,
		formatParams: {
			value: {
				currency: user.current_wallet?.currency,
				locale: user.language,
			},
		},
	});
}
