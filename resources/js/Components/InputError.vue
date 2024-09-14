<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>

<template>
    <div v-if="message">
        <p class="text-sm font-medium text-destructive">
            {{ message }}
        </p>
    </div>
</template>
