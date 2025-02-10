import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { Emitter } from 'mitt'

declare global {
	interface Window {
		axios: AxiosInstance;
		emitter: Emitter<any>
	}

	var route: typeof ziggyRoute;
}

declare module 'vue' {
	interface ComponentCustomProperties {
		route: typeof ziggyRoute;
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
