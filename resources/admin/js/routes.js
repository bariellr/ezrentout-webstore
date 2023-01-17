import { createWebHashHistory, createRouter } from "vue-router";
import Settings from "./pages/Settings.vue";

const routes = [
    {
        path: "/",
        name: "Settings",
        component: Settings,
        props: true,
    },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

export default router;
