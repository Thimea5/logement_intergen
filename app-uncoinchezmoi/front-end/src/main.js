import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios'; // import pour les requêtes
import router from './router'; // import pour la gestion des routes
import { createVuetify } from 'vuetify'; // Import pour vuetify...
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css'; 

const vuetify = createVuetify({
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  },
});

const app = createApp(App);

// Utilisation des plugins importés
app.use(router);
app.use(vuetify);

app.mount('#app');
