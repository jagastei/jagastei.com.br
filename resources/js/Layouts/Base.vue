<script setup lang="ts">
import ThemeToggle from '@/Components/ThemeToggle.vue'
</script>

<template>
   <div class="relative">
        <!-- <div class="absolute bottom-5 right-5">
            <ThemeToggle />
        </div> -->

        <div class="">
            <slot />
        </div>
   </div>
</template>