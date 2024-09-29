import './bootstrap';
import '../css/app.css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { autoAnimatePlugin } from '@formkit/auto-animate/vue';
import money from 'v-money3';
import mixpanel from 'mixpanel-browser';

// const MIXPANEL_CUSTOM_LIB_URL = "https://<YOUR_PROXY_DOMAIN>/lib.min.js";
// prod: 9c5e2c57b3fdebed16cf40a590cd3b9a
// dev: f4c0e1821c516e58a42beb1c85ea29b3
mixpanel.init('f4c0e1821c516e58a42beb1c85ea29b3', {
	debug: true,
	track_pageview: true,
	persistence: 'localStorage',
	// api_host: "https://<YOUR_PROXY_DOMAIN>",
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
	title: (title) => `${title} - ${appName}`,
	resolve: (name) =>
		resolvePageComponent(
			`./Pages/${name}.vue`,
			import.meta.glob<DefineComponent>('./Pages/**/*.vue')
		),
	setup({ el, App, props, plugin }) {
		createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(ZiggyVue)
			.use(autoAnimatePlugin)
			.use(money)
			.mount(el);
	},
	progress: {
		color: '#4B5563',
	},
});
