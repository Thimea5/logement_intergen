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
        target: 'https://api.uncoinchezmoi.live/api',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
});