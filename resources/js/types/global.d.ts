import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { Emitter } from 'mitt';

declare global {
	interface Window {
		axios: AxiosInstance;
		emitter: Emitter<any>;
		env: Env;
		appUrl: string;
	}

	var route: typeof ziggyRoute;
}

declare module 'vue' {
	interface ComponentCustomProperties {
		route: typeof ziggyRoute;
		$emitter: Emitter<any>;
	}
}

declare module '@inertiajs/core' {
	interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module '@tanstack/vue-table' {
	interface ColumnMeta<TData extends RowData, TValue> {
		title: string;
	}
}
