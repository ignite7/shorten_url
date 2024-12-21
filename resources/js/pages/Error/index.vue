<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import styles from './index.module.css';

interface IProps {
  status: number;
}

interface IError {
  [key: number]: string;
}

const { status } = defineProps<IProps>();

const titles = computed<IError>(() => ({
  503: 'Service Unavailable',
  500: 'Server Error',
  404: 'Page Not Found',
  403: 'Forbidden',
}));
const descriptions = computed<IError>(() => ({
  503: 'Sorry, we are doing some maintenance. Please check back soon.',
  500: 'Whoops, something went wrong on our servers.',
  404: 'Sorry, the page you are looking for could not be found.',
  403: 'Sorry, you are forbidden from accessing this page.',
}));
const title: string = `${status}: ${titles.value[status] ?? 'Error'}`;
const description: string =
  descriptions.value[status] ?? 'Something went wrong';
</script>

<template>
  <Head :title />
  <div :class="styles.error">
    <h1 :class="styles.text">{{ title }}</h1>
    <p>{{ description }}</p>
  </div>
</template>
