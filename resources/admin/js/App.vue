<script setup>
import { ref } from "vue";

const pageTitle = ref(window.ezrentoutwebstore_object?.page_title);
</script>

<template>
    <h1 v-text="pageTitle"></h1>
    <router-view />
</template>
