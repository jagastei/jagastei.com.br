<script setup lang="ts">
import { ref, watch, onBeforeUnmount } from 'vue'
import { Button } from '@/Components/ui/button'
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/Components/ui/dialog'
import { Icon } from '@iconify/vue'
import { TransitionRoot } from '@headlessui/vue'

interface Props {
  modelValue?: File[]
  multiple?: boolean
  accept?: string
  maxSize?: number // in MB
  previewMaxHeight?: number // in pixels
}

const props = withDefaults(defineProps<Props>(), {
  maxSize: 10,
  previewMaxHeight: 400,
})

const emit = defineEmits<{
  'update:modelValue': [files: File[]]
  error: [message: string]
}>()

const file = ref<File | null>(null)
const dragOver = ref(false)
const fileInput = ref<HTMLInputElement>()
const previewUrl = ref<string | null>(null)

const handleFileSelect = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (!input.files?.length) return
  
  validateAndAddFile(input.files[0])
}

const handleDrop = (event: DragEvent) => {
  event.preventDefault()
  dragOver.value = false
  
  if (!event.dataTransfer?.files.length) return
  
  validateAndAddFile(event.dataTransfer.files[0])
}

const validateAndAddFile = (newFile: File) => {
  // Check file size
  if (newFile.size > props.maxSize * 1024 * 1024) {
    emit('error', `File ${newFile.name} is too large. Max size is ${props.maxSize}MB`)
    return
  }
  
  // Check file type if accept prop is set
  if (props.accept && !newFile.type.match(props.accept)) {
    emit('error', `File ${newFile.name} is not an accepted file type`)
    return
  }

  file.value = newFile
  updatePreview()
  emit('update:modelValue', file.value ? [file.value] : [])
}

const removeFile = () => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
  file.value = null
  previewUrl.value = null
  emit('update:modelValue', [])
}

const updatePreview = () => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
  if (file.value && isImage(file.value)) {
    previewUrl.value = URL.createObjectURL(file.value)
  } else {
    previewUrl.value = null
  }
}

const openFileDialog = () => {
  fileInput.value?.click()
}

const isImage = (file: File) => file.type.startsWith('image/')

onBeforeUnmount(() => {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
})
</script>

<template>
  <div
    class="relative rounded-lg border-2 border-dashed border-gray-300 p-6"
    :class="{ 'border-primary': dragOver }"
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
    >
    
    <div v-if="!file" class="text-center">
      <Icon
        icon="heroicons:cloud-arrow-up"
        class="mx-auto h-12 w-12 text-gray-400"
      />
      <div class="mt-2">
        <Button variant="link" @click="openFileDialog">
          Upload a file
        </Button>
        <span class="text-gray-500">
          or drag and drop
        </span>
      </div>
      <p class="mt-1 text-sm text-gray-500">
        File up to {{ maxSize }}MB
      </p>
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
      <div 
        v-if="file"
        class="overflow-y-auto"
        :style="{ maxHeight: `${previewMaxHeight}px` }"
      >
        <!-- Image Preview -->
        <div 
          v-if="isImage(file) && previewUrl"
          class="relative mb-4 overflow-hidden rounded-lg"
        >
          <img
            :src="previewUrl"
            :alt="file.name"
            class="w-full rounded-lg object-contain"
          />
        </div>
        
        <!-- File Info -->
        <div class="flex items-center justify-between rounded bg-gray-50 p-3">
          <div class="flex items-center space-x-3 overflow-hidden">
            <Icon 
              v-if="!isImage(file)"
              icon="heroicons:document" 
              class="h-6 w-6 flex-shrink-0 text-gray-400"
            />
            <div class="overflow-hidden">
              <p class="truncate text-sm font-medium">{{ file.name }}</p>
              <p class="text-xs text-gray-500">
                {{ (file.size / 1024 / 1024).toFixed(2) }}MB
              </p>
            </div>
          </div>
          <Button
            variant="ghost"
            size="icon"
            @click="removeFile"
            class="flex-shrink-0"
          >
            <Icon icon="heroicons:x-mark" class="h-5 w-5" />
          </Button>
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
</style>