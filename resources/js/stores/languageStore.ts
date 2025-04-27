import { defineStore } from 'pinia';
import i18next from 'i18next';
import { ref, computed } from 'vue';
// import { usePage } from '@inertiajs/vue3';

export const availableLanguages = ['pt', 'en'];
export const defaultLanguage = 'pt';

export const useLanguageStore = defineStore(
	'language',
	() => {
		// const user = usePage().props.auth.user;
		// console.log(user);

		const currentLanguage = ref(defaultLanguage);

		const getAvailableLanguages = computed(() => availableLanguages);
		const getCurrentLanguage = computed(() => currentLanguage.value);

		const setLanguage = async (language: string) => {
			if (!availableLanguages.includes(language)) return;

			await i18next.changeLanguage(language);

			document.querySelector('html')?.setAttribute('lang', language);

			currentLanguage.value = language;
		};

		return {
			currentLanguage,
			getAvailableLanguages,
			getCurrentLanguage,
			setLanguage,
		};
	},
	{
		persist: true,
	}
);
