import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axios from 'axios';

// Configuração base do Axios
axios.defaults.withCredentials = true; // Importante para o Sanctum

// A baseURL deve ser vazia ou "/" para que o proxy do Vite funcione em dev.
// Para produção, a URL completa será injetada pelo processo de build.
axios.defaults.baseURL = '/';

const app = createApp(App);

app.use(router);

app.mount('#app');
