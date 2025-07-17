import posthog from 'posthog-js';

export function usePostHog() {
	posthog.init(import.meta.env.VITE_POSTHOG_KEY, {
		api_host: import.meta.env.VITE_POSTHOG_HOST,
		capture_pageview: false,
		autocapture: false,
        opt_out_capturing_by_default: true,
	});

    posthog.opt_out_capturing();

	return {
		posthog,
	};
}
