<script setup lang="ts">
import { useColorMode } from '@vueuse/core'

const { store } = useColorMode()
</script>

<template>
   <div class="min-h-screen">
        <div>
            <slot />
        </div>
   </div>
</template>