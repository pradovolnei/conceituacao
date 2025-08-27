import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// Obtém a URL da API da variável de ambiente.
// Usa 'http://localhost:8000' como fallback para desenvolvimento.
const API_URL = process.env.VITE_API_URL || 'http://localhost:8000';

export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  server: {
    proxy: {
      // Regra para capturar todas as requisições da sua API que começam com /api
      // A requisição para /api/user será encaminhada para http://localhost:8000/api/user
      '/api': {
        target: API_URL,
        changeOrigin: true,
        // O `rewrite` é opcional, mas garante que o caminho da URL seja o mesmo na API
        // rewrite: (path) => path.replace(/^\/api/, '/api'),
      },
      // Regras explícitas para as rotas de autenticação do Sanctum
      // A rota `/sanctum/csrf-cookie` é essencial para obter o token CSRF
      '/sanctum/csrf-cookie': {
        target: API_URL,
        changeOrigin: true,
        // O `cookieDomainRewrite` é crucial para que o cookie seja enviado para o frontend
        cookieDomainRewrite: 'localhost',
      },
      // E as outras rotas de autenticação, se você precisar de regras separadas
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
    }
  }
});
