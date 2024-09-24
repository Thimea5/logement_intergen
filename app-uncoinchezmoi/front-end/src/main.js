import { createApp } from 'vue';
import App from './App.vue';
import axios from 'axios';
import router from './router';

// Importer Vuetify et les styles associés
import { createVuetify } from 'vuetify';
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import 'vuetify/styles'; // Importer les styles de base
import '@mdi/font/css/materialdesignicons.css';

// Créer l'instance Vuetify
const vuetify = createVuetify({
  // Tu peux ici configurer les options de Vuetify si besoin
  
});

const app = createApp(App);

app.use(router); // Utiliser le routeur
app.use(vuetify); // Utiliser Vuetify

app.mount('#app');
