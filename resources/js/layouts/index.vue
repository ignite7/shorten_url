<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const { flash } = usePage().props;
</script>

<template>
  <main class="main">
    <!-- Flash message -->
    <!-- TODO: Create a component for this -->
    <div
      v-if="flash.message"
      :class="['flash-message', `flash-message-${flash.type}`]"
    >
      {{ flash.message }}
    </div>
    <slot></slot>
  </main>
</template>
