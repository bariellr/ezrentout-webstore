<script setup>
import { toRefs } from "vue";
import { ArrowPathIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    text: {
        type: String,
        default: "Loading items...",
    },
    body: String,
});

const { text, body } = toRefs(props);
</script>

<template>
    <div
        class="x-h-[15rem] x-hero x-bg-base-100 x-text-base-content x-rounded-lg"
    >
        <div class="x-text-center x-hero-content">
            <div class="x-max-w-md x-flex x-flex-col x-items-center x-gap-4">
                <ArrowPathIcon
                    class="x-w-12 x-h-12 x-animate-spin"
                ></ArrowPathIcon>

                <p class="x-text-lg" v-text="text"></p>

                <p v-if="body" v-text="body"></p>
            </div>
        </div>
    </div>
</template>
