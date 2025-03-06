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

window.env = import.meta.env.MODE as Env;

const appName = import.meta.env.VITE_APP_NAME || '';
window.appUrl = import.meta.env.VITE_APP_URL || '';

const emitter = mitt();
window.emitter = emitter;

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

		app.use(plugin).use(ZiggyVue).use(autoAnimatePlugin).use(money).mount(el);
	},
	progress: {
		color: '#4B5563',
	},
});
