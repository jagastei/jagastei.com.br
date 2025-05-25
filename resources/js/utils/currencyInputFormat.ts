const BRL = (locale: string) => {
	let prefix = 'R$ ';

	return {
		prefix,
		suffix: '',
		thousands: '.',
		decimal: ',',
		precision: 2,
		disableNegative: false,
		disabled: false,
		min: null,
		max: null,
		allowBlank: false,
		minimumNumberOfCharacters: 0,
		shouldRound: false,
		focusOnRight: false,
		modelModifiers: {
			number: false,
		},
	};
};

const USD = (locale: string) => {
	let prefix = '$ ';

	if (locale === 'pt') {
		prefix = 'US$ ';
	}

	return {
		prefix,
		suffix: '',
		thousands: ',',
		decimal: '.',
		precision: 2,
		disableNegative: false,
		disabled: false,
		min: null,
		max: null,
		allowBlank: false,
		minimumNumberOfCharacters: 0,
		shouldRound: false,
		focusOnRight: false,
		modelModifiers: {
			number: false,
		},
	};
};

const EUR = (locale: string) => {
	let prefix = '€ ';

	return {
		prefix,
		suffix: '',
		thousands: ',',
		decimal: '.',
		precision: 2,
		disableNegative: false,
		disabled: false,
		min: null,
		max: null,
		allowBlank: false,
		minimumNumberOfCharacters: 0,
		shouldRound: false,
		focusOnRight: false,
		modelModifiers: {
			number: false,
		},
	};
};

const GBP = (locale: string) => {
	let prefix = '£ ';

	return {
		prefix,
		suffix: '',
		thousands: ',',
		decimal: '.',
		precision: 2,
		disableNegative: false,
		disabled: false,
		min: null,
		max: null,
		allowBlank: false,
		minimumNumberOfCharacters: 0,
		shouldRound: false,
		focusOnRight: false,
		modelModifiers: {
			number: false,
		},
	};
};

export const currencyInputFormat = (currency: string, locale: string) => {
	switch (currency) {
		case 'BRL':
			return BRL(locale);
		case 'USD':
			return USD(locale);
		case 'EUR':
			return EUR(locale);
		case 'GBP':
			return GBP(locale);
		default:
			return BRL(locale);
	}
};
