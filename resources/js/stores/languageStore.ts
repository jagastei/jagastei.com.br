import { defineStore } from 'pinia';
import i18next from 'i18next';
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export const availableLanguages = ['pt', 'en'];
export const defaultLanguage = 'pt';

export const useLanguageStore = defineStore(
	'language',
	() => {
		const user = usePage().props.auth.user;

		const currentLanguage = ref(user.language ?? defaultLanguage);

		const getAvailableLanguages = computed(() => availableLanguages);
		const getCurrentLanguage = computed(() => currentLanguage.value);

		const setLanguage = async (language: string) => {
			if (!availableLanguages.includes(language)) return;

			await i18next.changeLanguage(language);

			document.querySelector('html')?.setAttribute('lang', language);

			currentLanguage.value = language;
		};

		// onMounted(() => {
		// 	setLanguage(currentLanguage.value);
		// })

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
