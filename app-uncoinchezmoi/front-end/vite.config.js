// vite.config.js
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost/logement_intergen/app-uncoinchezmoi/api',  // Adresse où tourne votre backend PHP (Wamp)
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '')  // On retire "/api" du chemin
      }
    }
  }
});
