import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// Obtém a URL da API da variável de ambiente
const API_URL = process.env.VITE_API_URL;

export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    proxy: {
      '/api': {
        target: API_URL,
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, '/api'),
      },
      '/sanctum': {
        target: API_URL,
        changeOrigin: true,
      },
      '/login': {
        target: API_URL,
        changeOrigin: true,
      },
      '/register': {
        target: API_URL,
        changeOrigin: true,
      },
      '/logout': {
        target: API_URL,
        changeOrigin: true,
      },
      '/csrf-cookie': {
        target: API_URL,
        changeOrigin: true,
        cookieDomainRewrite: 'localhost',
        cookiePathRewrite: '/',
      },
    }
  }
})
