<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import IUser from '@interfaces/IUser';
import { PageProps } from '@types';

const {
  props: { name = 'There!' },
} = usePage<PageProps & IUser>();
</script>

<template>
  <Head title="Home" />
  <h1>Hello, {{ name }}</h1>
</template>
