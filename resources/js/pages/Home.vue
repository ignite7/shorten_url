<script setup lang="ts">
import { Head, usePage } from "@inertiajs/vue3";
import { PageProps } from "@types";
import IUser from "@interfaces/IUser";

const { props: { name = "There!" } } = usePage<PageProps & IUser>();
</script>

<template>
  <Head title="Home" />
  <h1>Hello, {{ name }}</h1>
</template>
