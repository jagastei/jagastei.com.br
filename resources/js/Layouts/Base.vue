<script setup lang="ts">
import ThemeToggle from '@/Components/ThemeToggle.vue'
</script>

<template>
   <div class="relative">
        <div class="absolute top-5 right-5">
            <ThemeToggle />
        </div>

        <slot />

   </div>
</template>