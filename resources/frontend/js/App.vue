<script setup>
import { onMounted } from "vue";

onMounted(() => {
    // fix ezrentout ahrefs
    const body = document.querySelector("body");

    body.addEventListener("DOMSubtreeModified", (e) => {
        let ahrefs = e.target.querySelectorAll("a[href='#_']");

        ahrefs.forEach((ahref) => {
            ahref.setAttribute("href", "javascript:void(0)");
        });
    });
});
</script>

<template>
    <div data-theme="autumn" class="x-bg-transparent">
        <router-view :key="$route.fullPath" />
    </div>
</template>
