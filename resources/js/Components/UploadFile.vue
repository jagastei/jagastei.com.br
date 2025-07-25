<script setup lang="ts">
import { ref, watch } from 'vue';
import { Button } from '@/Components/ui/button';
import { TransitionRoot } from '@headlessui/vue';
import { CloudUploadIcon, XIcon } from 'lucide-vue-next';

interface Props {
	modelValue?: File[];
	multiple?: boolean;
	accept?: string;
	maxSize?: number; // in MB
	previewMaxHeight?: number; // in pixels
	loading?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
	multiple: false,
	accept: 'image/*',
	maxSize: 10,
	previewMaxHeight: 400,
	loading: false,
});

const emit = defineEmits<{
	'update:modelValue': [files: File[]];
	error: [message: string];
}>();

const file = ref<File | null>(null);
const dragOver = ref(false);
const fileInput = ref<HTMLInputElement>();
const previewUrl = ref<string | null>(null);
const imageLoaded = ref(false);

watch(
	() => props.modelValue,
	(newValue) => {
		if (!newValue?.length) {
			if (previewUrl.value) {
				URL.revokeObjectURL(previewUrl.value);
			}
			file.value = null;
			previewUrl.value = null;
			if (fileInput.value) {
				fileInput.value.value = '';
			}
		}
	},
	{ deep: true }
);

const handleFileSelect = (event: Event) => {
	const input = event.target as HTMLInputElement;
	if (!input.files?.length) return;

	validateAndAddFile(input.files[0]);
};

const handleDrop = (event: DragEvent) => {
	event.preventDefault();
	dragOver.value = false;

	if (!event.dataTransfer?.files.length) return;

	validateAndAddFile(event.dataTransfer.files[0]);
};

const handleImageLoad = () => {
	imageLoaded.value = true;
};

const validateAndAddFile = (newFile: File) => {
	imageLoaded.value = false;
	// Check file size
	if (newFile.size > props.maxSize * 1024 * 1024) {
		emit(
			'error',
			`File ${newFile.name} is too large. Max size is ${props.maxSize}MB`
		);
		return;
	}

	// Check file type if accept prop is set
	if (props.accept && !newFile.type.match(props.accept)) {
		emit('error', `File ${newFile.name} is not an accepted file type`);
		return;
	}

	file.value = newFile;
	updatePreview();
	emit('update:modelValue', file.value ? [file.value] : []);
};

const removeFile = () => {
	imageLoaded.value = false;
	if (previewUrl.value) {
		URL.revokeObjectURL(previewUrl.value);
	}
	file.value = null;
	previewUrl.value = null;
	if (fileInput.value) {
		fileInput.value.value = '';
	}
	emit('update:modelValue', []);
};

const updatePreview = () => {
	if (previewUrl.value) {
		URL.revokeObjectURL(previewUrl.value);
	}
	if (file.value && isImage(file.value)) {
		previewUrl.value = URL.createObjectURL(file.value);
	} else {
		previewUrl.value = null;
	}
};

const openFileDialog = () => {
	fileInput.value?.click();
};

const isImage = (file: File) => file.type.startsWith('image/');
</script>

<template>
	<div
		:class="[
			'relative rounded-lg',
			previewUrl
				? 'p-0 ring-2 ring-primary'
				: 'p-10 border-2 border-dashed border-foreground-muted',
			{ 'border-primary': dragOver },
		]"
		@dragover.prevent="dragOver = true"
		@dragleave.prevent="dragOver = false"
		@drop="handleDrop"
	>
		<input
			ref="fileInput"
			type="file"
			:accept="accept"
			class="hidden"
			@change="handleFileSelect"
		/>

		<div v-if="!file" class="text-center">
			<CloudUploadIcon class="mx-auto size-8 text-muted" :stroke-width="1.5" />
			<div class="mt-2 flex flex-col">
				<Button variant="link" @click="openFileDialog">
					Clique aqui para enviar uma imagem
				</Button>
				<span class="text-foreground text-sm"> ou arraste e solte até aqui </span>
			</div>
		</div>

		<TransitionRoot
			:show="!!file"
			enter="transition-all duration-300 ease-out"
			enter-from="opacity-0 scale-95"
			enter-to="opacity-100 scale-100"
			leave="transition-all duration-200 ease-in"
			leave-from="opacity-100 scale-100"
			leave-to="opacity-0 scale-95"
			class="overflow-hidden"
		>
			<div v-if="file" class="relative">
				<!-- File Info & Remove Button -->
				<!-- <div class="absolute inset-x-0 top-0 z-10 flex items-center justify-between bg-black/50 backdrop-blur p-2">
          <div class="flex flex-col">
            <span class="text-sm text-white">{{ file.name }}</span>
            <span class="text-[10px] text-white/50">{{ (file.size / 1024 / 1024).toFixed(2) }}MB</span>
          </div>
          <Button variant="link" size="icon" @click="removeFile" class="text-white/50 hover:text-white">
            <XIcon class="size-4"/>
          </Button>
        </div> -->

				<div
					class="overflow-y-auto"
					:style="{ maxHeight: `${previewMaxHeight}px` }"
				>
					<!-- Image Preview -->
					<div v-if="isImage(file) && previewUrl" class="relative">
						<Transition name="fade">
							<div v-if="loading" class="absolute inset-0 bg-gray-100/20">
								<div class="w-full h-full shimmer" />
							</div>
						</Transition>

						<img
							:src="previewUrl"
							:alt="file.name"
							class="w-full rounded-lg object-contain"
							@load="handleImageLoad"
							:class="{ 'opacity-0': !imageLoaded }"
						/>
					</div>
				</div>
			</div>
		</TransitionRoot>
	</div>
</template>

<style scoped>
.overflow-y-auto {
	scrollbar-width: thin;
	scrollbar-color: theme('colors.gray.300') theme('colors.gray.100');
}

.overflow-y-auto::-webkit-scrollbar {
	width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
	background: theme('colors.gray.100');
	border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
	background-color: theme('colors.gray.300');
	border-radius: 3px;
}

.shimmer {
	background: linear-gradient(
		-45deg,
		#eeeeee10 30%,
		#ffffff4d 50%,
		#eeeeee10 70%
	);
	/* @apply bg-gradient-to-r from-primary/10 via-primary/50 to-primary/10 from-30% via-50% to-70%; */
	background-size: 400%;
	background-position-x: 100%;
	animation: shimmer 4s infinite ease-in-out;
}

@keyframes shimmer {
	0% {
		background-position: 100%;
	}
	50% {
		background-position: -100%;
	}
	100% {
		background-position: 100%;
	}
}
</style>
