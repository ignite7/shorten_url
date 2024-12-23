<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

interface IProps {
  name?: string;
}

const { name = 'John Doe' } = defineProps<IProps>();

const renderName = ref<string>(name);
</script>

<template>
  <Head title="Home" />
  <h1>Hello, {{ renderName }}</h1>
  <input type="text" v-model="renderName" />
</template>
