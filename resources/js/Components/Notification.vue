<script setup>
import { ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CircleCheckIcon, CircleAlertIcon, XIcon } from 'lucide-vue-next';

const page = usePage();
const show = ref(true);
const type = ref('success');
const message = ref('');
const description = ref('');

watchEffect(() => {
	type.value = page.props.notify?.type || 'success';
	message.value = page.props.notify?.message || '';
	description.value = page.props.notify?.description || '';
	show.value = true;

	setTimeout(() => (show.value = false), 10000);
});

// show && message
</script>

<template>
	<!-- <div
		aria-live="assertive"
		class="mt-16 pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-50"
	>
		<div class="flex w-full flex-col items-center space-y-4 sm:items-end">
			<transition
				enter-active-class="transform ease-out duration-300 transition"
				enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
				enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
				leave-active-class="transition ease-in duration-100"
				leave-from-class="opacity-100"
				leave-to-class="opacity-0"
			>
				<div
					v-if="show && message"
					class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
				>
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<CircleCheckIcon
									v-if="type == 'success'"
									class="h-6 w-6 text-green-400"
									aria-hidden="true"
								/>
								<CircleAlertIcon
									v-if="type == 'danger'"
									class="h-6 w-6 text-red-400"
									aria-hidden="true"
								/>
							</div>
							<div class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900">{{ message }}</p>
								<p v-if="description" class="mt-1 text-sm text-gray-500">
									{{ description }}
								</p>
							</div>
							<div class="ml-4 flex flex-shrink-0">
								<button
									type="button"
									@click.prevent="show = false"
									class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-rauzee-light-500 focus:ring-offset-2"
								>
									<span class="sr-only">Close</span>
									<XIcon class="h-5 w-5" aria-hidden="true" />
								</button>
							</div>
						</div>
					</div>
				</div>
			</transition>
		</div>
	</div> -->
</template>
