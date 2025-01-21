<script setup lang="ts">
import Footer from '@components/Footer/index.vue';
import Header from '@components/Header/index.vue';
import { usePage } from '@inertiajs/vue3';

const { flash } = usePage().props;
</script>

<template>
  <Header />
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
  <Footer />
</template>
