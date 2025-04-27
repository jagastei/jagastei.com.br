import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { autoAnimatePlugin } from '@formkit/auto-animate/vue';
import money from 'v-money3';
import mitt from 'mitt';
import { Env } from './types/index';
import { usePostHog } from './composables/usePosthog';
import i18next from 'i18next';
import i18nextVue from 'i18next-vue';
import Backend from 'i18next-http-backend';
import { availableLanguages, defaultLanguage } from './stores/languageStore';
import { createPinia } from 'pinia'

window.env = import.meta.env.MODE as Env;

const appName = import.meta.env.VITE_APP_NAME || '';
window.appUrl = import.meta.env.VITE_APP_URL || '';

const emitter = mitt();
window.emitter = emitter;

const { posthog } = usePostHog();

const pinia = createPinia();

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) =>
		resolvePageComponent(
			`./Pages/${name}.vue`,
			import.meta.glob<DefineComponent>('./Pages/**/*.vue')
		),
	setup({ el, App, props, plugin }) {
		const app = createApp({ render: () => h(App, props) });

		app.config.globalProperties.$emitter = emitter;

		document.addEventListener('inertia:start', (event) => {
			posthog.capture('$pageleave');
		});

		document.addEventListener('inertia:navigate', (event) => {
			posthog.capture('$pageview', {
				path: event.detail.page.url,
				component: event.detail.page.component,
			});
		});

		const i18n = i18next.use(Backend).init({
			debug: false,
			fallbackLng: defaultLanguage,
			supportedLngs: availableLanguages,
			backend: {
				loadPath: '/api/language/{{lng}}',
			},
		});

		app.use(i18nextVue, { i18next });
		app.use(plugin);
		app.use(ZiggyVue);
		app.use(autoAnimatePlugin);
		app.use(money);
		app.use(pinia);
		i18n.then(() => {
			app.mount(el);
		});
	},
	progress: {
		color: '#4B5563',
	},
});
