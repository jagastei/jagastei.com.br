export interface Wallet {
	id: string;
	name: string;
}

export interface User {
	id: number;
	name: string;
	email: string;
	email_verified_at?: string;
	wallets: Wallet[];
	current_wallet: Wallet;
}

export type PageProps<
	T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
	auth: {
		user: User;
	};
};

export const Env = {
	development: 'development',
	production: 'production',
} as const;

export type Env = (typeof Env)[keyof typeof Env];
