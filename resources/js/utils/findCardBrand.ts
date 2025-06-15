export function findCardBrand(digits: string): string | null {
	// Remove any non-digit characters
	const cleanDigits = digits.replace(/\D/g, '');

	// Define card patterns
	const patterns = {
		amex: /^3[47]\d{13}$/,
		diners: /^3(?:0[0-5]|[68]\d)\d{11}$/,
		discover: /^6(?:011|5\d{2})\d{12}$/,
		elo: /^(?:636368|438935|504175|451416|636297|5067|4576|4011)\d{10}$/,
		hipercard: /^(606282\d{10}|\d{16})$/,
		maestro: /^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/,
		mastercard:
			/^(5[1-5]\d{14}|2(2[2-9]\d{12}|[3-6]\d{13}|7[01]\d{12}|720\d{12}))$/,
		visa: /^4\d{12}(?:\d{3})?$/,
	};

	// Check each pattern
	for (const [brand, pattern] of Object.entries(patterns)) {
		if (pattern.test(cleanDigits)) {
			return brand;
		}
	}

	return null;
}
