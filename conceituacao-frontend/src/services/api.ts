
import axios from 'axios';

const baseURL = import.meta.env.VITE_API_URL;

const apiClient = axios.create({
  baseURL: baseURL, // Altere para o seu backend URL
  withCredentials: true, // Garante que cookies são enviados
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

// Adiciona um interceptor de requisição para incluir o token CSRF
apiClient.interceptors.request.use(config => {
  // Obtém o token XSRF do cookie
  const xsrfToken = document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN='))?.split('=')[1];

  // Adiciona o token ao cabeçalho da requisição
  if (xsrfToken) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken);
  }

  return config;
});

export default apiClient;