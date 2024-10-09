import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify'; 

export default defineConfig({
  plugins: [
    vue(),
    vuetify({ autoImport: true }), 
  ],
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost/logement_intergen/app-uncoinchezmoi/api',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
});
