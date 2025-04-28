import { usePage } from '@inertiajs/vue3';
import { TFunction } from 'i18next';

export function useCurrency(t: TFunction, amount: number): string {
	const user = usePage().props.auth.user;

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
