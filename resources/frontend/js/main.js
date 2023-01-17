import { createApp } from "vue";
import { createWebHashHistory, createRouter } from "vue-router";
import { createPinia } from "pinia";
import App from "./App.vue";
import routes from "./routes";

import "mapbox-gl/dist/mapbox-gl.css";

const app = createApp(App);

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

const pinia = createPinia();

app.use(router).use(pinia).mount("#ezrentoutwebstore-frontend-app");
