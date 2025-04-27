import { defineStore } from 'pinia';
import i18next from 'i18next';

export const availableLanguages = ['en', 'pt'];
export const defaultLanguage = 'pt';

export const useLanguageStore = defineStore('language', {
	state: () => ({
        availableLanguages,
        currentLanguage: defaultLanguage,
    }),

    getters: {
        getAvailableLanguages: (state) => state.availableLanguages,
        getCurrentLanguage: (state) => state.currentLanguage,
    },

    actions: {
        async setLanguage(language: string) {
            if(! availableLanguages.includes(language)) return;

            await i18next.changeLanguage(language);

            document.querySelector('html')?.setAttribute('lang', language);

            this.currentLanguage = language;
        },
    },
});