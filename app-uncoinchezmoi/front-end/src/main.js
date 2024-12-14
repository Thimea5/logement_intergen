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

import Home from "./components/Home.vue";
import About from "./components/About.vue";
import Login from "./components/LogIn.vue";
import Register from "./components/Register.vue";
import UserProfile from "./components/UserProfile.vue";
import ErrorPage from "./components/Error.vue";
import LegalNotices from "./components/LegalNotices.vue";
import ForgotPasswordForm from "./components/ForgotPasswordForm.vue";
import MapComponent from "./components/MapComponent.vue";
import AdvancedSearch from "./components/AdvancedSearch.vue";
import PostDetails from "./components/PostDetails.vue";
import NewComment from "./components/NewComment.vue";
import ConversationComponent from "./components/ConversationComponent.vue";
import ViewPost from "./components/ViewPost.vue";
import MessageComponent from "./components/MessageComponent.vue";
import NewReport from "./components/NewReport.vue";
import Reservation from "./components/Reservation.vue";

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
