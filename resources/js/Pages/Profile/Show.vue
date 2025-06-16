<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Button from '@/Components/ui/button/Button.vue';
import { CloudDownloadIcon } from 'lucide-vue-next';

defineProps<{
	mustVerifyEmail?: boolean;
	status?: string;
}>();

const exportData = () => {
	console.log('export data');
};
</script>

<template>
	<Head title="Minha conta" />

	<AuthenticatedLayout>
		<div class="flex flex-1 flex-col p-4 lg:p-6 gap-4 lg:gap-6">
			<div class="flex items-center justify-between">
				<div>
					<h2 class="text-3xl font-bold tracking-tight">Minha conta</h2>
				</div>

				<div class="flex items-center space-x-2">
					<Button v-if="false" @click="exportData">
						<CloudDownloadIcon class="size-4 mr-2" />
						Exportar dados
					</Button>
				</div>
			</div>

			<div class="space-y-6">
				<UpdateProfileInformationForm
					:must-verify-email="mustVerifyEmail"
					:status="status"
				/>

				<UpdatePasswordForm />

				<DeleteUserForm />
			</div>
		</div>
	</AuthenticatedLayout>
</template>
