import { reactive, ref } from 'vue';

export default function useAxios(initialData = {}) {
	const processing = ref(false);
	const errors = ref({});
	const hasErrors = ref(false);

	const formData = reactive({ ...initialData });

	const toFormData = (data) => {
		const formData = new FormData();

		const appendToFormData = (data, parentKey = '') => {
			Object.keys(data).forEach((key) => {
				const value = data[key];
				const formKey = parentKey ? `${parentKey}[${key}]` : key;

				if (value === null || value === undefined) {
					formData.append(formKey, '');
				} else if (Array.isArray(value)) {
					value.forEach((item, index) => {
						if (
							typeof item === 'object' &&
							item !== null &&
							!(item instanceof File)
						) {
							appendToFormData(item, `${formKey}[${index}]`);
						} else {
							const processedItem =
								typeof item === 'boolean' ? (item ? '1' : '0') : item;
							formData.append(`${formKey}[${index}]`, processedItem);
						}
					});
				} else if (typeof value === 'object' && !(value instanceof File)) {
					appendToFormData(value, formKey);
				} else {
					const processedValue =
						typeof value === 'boolean' ? (value ? '1' : '0') : value;
					formData.append(formKey, processedValue);
				}
			});
		};

		appendToFormData(data);
		return formData;
	};

	const submit = async (method, url, options = {}) => {
		const {
			onBefore,
			onSuccess,
			onError,
			onFinish,
			transform,
			forceFormData = false,
			// preserveScroll,
			// preserveState,
			...axiosOptions
		} = options;

		processing.value = true;
		clearErrors();

		if (onBefore) {
			const result = onBefore();
			if (result === false) {
				processing.value = false;
				return;
			}
		}

		try {
			let dataToSend = { ...formData };
			if (transform) {
				dataToSend = transform(dataToSend);
			}

			let payload = dataToSend;
			let headers = { ...axiosOptions.headers };

			if (forceFormData || hasFiles(dataToSend)) {
				payload = toFormData(dataToSend);
				headers['Content-Type'] = 'multipart/form-data';
			}

			const response = await axios({
				method: method.toLowerCase(),
				url,
				data: method.toLowerCase() === 'get' ? undefined : payload,
				params: method.toLowerCase() === 'get' ? payload : undefined,
				headers,
				...axiosOptions,
			});

			if (onSuccess) {
				onSuccess(response);
			}

			return response;
		} catch (error) {
			if (error.response?.status === 422 && error.response?.data?.errors) {
				setErrors(error.response.data.errors);
			}

			if (onError) {
				onError(error.response?.data || error.message);
			}

			throw error;
		} finally {
			processing.value = false;

			if (onFinish) {
				onFinish();
			}
		}
	};

	const hasFiles = (data) => {
		const checkForFiles = (obj) => {
			for (const key in obj) {
				const value = obj[key];
				if (value instanceof File || value instanceof FileList) {
					return true;
				}
				if (Array.isArray(value)) {
					for (const item of value) {
						if (typeof item === 'object' && item !== null && checkForFiles(item)) {
							return true;
						}
					}
				}
				if (
					typeof value === 'object' &&
					value !== null &&
					!(value instanceof Date)
				) {
					if (checkForFiles(value)) {
						return true;
					}
				}
			}
			return false;
		};
		return checkForFiles(data);
	};

	const setErrors = (newErrors) => {
		const processedErrors = {};
		for (const field in newErrors) {
			const error = newErrors[field];
			if (Array.isArray(error)) {
				processedErrors[field] = error.join(', ');
			} else {
				processedErrors[field] = error;
			}
		}

		errors.value = processedErrors;
		hasErrors.value = Object.keys(processedErrors).length > 0;
	};

	const clearErrors = (field = null) => {
		if (field) {
			delete errors.value[field];
		} else {
			errors.value = {};
		}
		hasErrors.value = Object.keys(errors.value).length > 0;
	};

	const reset = (fields = null) => {
		if (fields) {
			if (Array.isArray(fields)) {
				fields.forEach((field) => {
					if (field in initialData) {
						formData[field] = initialData[field];
					}
				});
			} else if (typeof fields === 'string') {
				if (fields in initialData) {
					formData[fields] = initialData[fields];
				}
			}
		} else {
			Object.assign(formData, initialData);
		}
		clearErrors();
	};

	const transform = (callback) => {
		return {
			post: (url, options = {}) => post(url, { ...options, transform: callback }),
			put: (url, options = {}) => put(url, { ...options, transform: callback }),
			patch: (url, options = {}) =>
				patch(url, { ...options, transform: callback }),
			delete: (url, options = {}) =>
				_delete(url, { ...options, transform: callback }),
		};
	};

	const post = (url, options = {}) => submit('post', url, options);
	const put = (url, options = {}) => submit('put', url, options);
	const patch = (url, options = {}) => submit('patch', url, options);
	const _delete = (url, options = {}) => submit('delete', url, options);
	const get = (url, options = {}) => submit('get', url, options);

	return new Proxy(formData, {
		get(target, prop) {
			if (prop === 'processing') return processing.value;
			if (prop === 'errors') return errors.value;
			if (prop === 'hasErrors') return hasErrors.value;

			if (prop === 'post') return post;
			if (prop === 'put') return put;
			if (prop === 'patch') return patch;
			if (prop === 'delete') return _delete;
			if (prop === 'get') return get;
			if (prop === 'reset') return reset;
			if (prop === 'clearErrors') return clearErrors;
			if (prop === 'setErrors') return setErrors;
			if (prop === 'transform') return transform;
			if (prop === 'submit') return submit;

			return target[prop];
		},
		set(target, prop, value) {
			if (prop in target && errors.value[prop]) {
				clearErrors(prop);
			}
			target[prop] = value;
			return true;
		},
	});
}
