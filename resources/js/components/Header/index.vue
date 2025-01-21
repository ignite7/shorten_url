<script setup lang="ts">
import styles from './index.module.css';
</script>

<template>
  <header :class="styles.header">
    <div :class="styles.logo">ShortenURL</div>
    <div :class="styles.buttons">
      <button>Login</button>
      <button>Register</button>
    </div>
  </header>
</template>
