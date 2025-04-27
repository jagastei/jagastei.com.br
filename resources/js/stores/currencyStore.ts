import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
// import { usePage } from '@inertiajs/vue3';

export const availableCurrencies = ['BRL', 'USD', 'EUR', 'GBP'];
export const defaultCurrency = 'BRL';

export const useCurrencyStore = defineStore(
	'currency',
	() => {
		// const user = usePage().props.auth.user;
		// console.log(user);

		const currentCurrency = ref(defaultCurrency);

		const getAvailableCurrencies = computed(() => availableCurrencies);
		const getCurrentCurrency = computed(() => currentCurrency.value);

		const setCurrency = async (currency: string) => {
			if (!availableCurrencies.includes(currency)) return;
			currentCurrency.value = currency;
		};

		return {
			currentCurrency,
			getAvailableCurrencies,
			getCurrentCurrency,
			setCurrency,
		};
	},
);
