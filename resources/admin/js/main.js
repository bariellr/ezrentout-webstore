import { createApp } from "vue";
import App from "./App.vue";
import router from "./routes";
import menuFix from "./admin-menu-fix";

createApp(App).use(router).mount("#ezrentoutwebstore-admin-app");

menuFix("ezrentoutwebstore-admin-app");
