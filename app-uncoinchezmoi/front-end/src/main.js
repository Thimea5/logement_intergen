import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia"; // import pour le gestionnaire d'état
import router from "./router/index.js"; // import pour la gestion des routes
import { createVuetify } from "vuetify"; // import pour vuetify...
import { aliases, mdi } from "vuetify/iconsets/mdi";
import "vuetify/styles";
import "@mdi/font/css/materialdesignicons.css";
import "leaflet/dist/leaflet.css";
import { setupCalendar } from "v-calendar";

const vuetify = createVuetify({
  icons: {
    defaultSet: "mdi",
    aliases,
    sets: {
      mdi,
    },
  },
});

const app = createApp(App);
const pinia = createPinia();

// Utilisation des plugins importés
app.use(router);
app.use(vuetify);
app.use(pinia);
app.use(setupCalendar, {});

app.mount("#app");
