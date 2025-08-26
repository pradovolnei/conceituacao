import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    isAuthenticated: localStorage.getItem('isAuthenticated') === 'true',
    isAdmin: false,
    loadingUser: false,
  }),
  actions: {
    async fetchUser() {
      this.loadingUser = true;
      try {
        const response = await axios.get('/api/user');
        this.user = response.data;
        this.isAuthenticated = true;
        this.isAdmin = this.user.profiles.some(profile => profile.name === 'Administrador');
        localStorage.setItem('isAuthenticated', 'true');
      } catch (error) {
        console.error('Erro ao buscar usuário autenticado:', error);
        this.user = null;
        this.isAuthenticated = false;
        this.isAdmin = false;
        localStorage.removeItem('isAuthenticated');
      } finally {
        this.loadingUser = false;
      }
    },
    async login(credentials) {
      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.post('/login', credentials);
      if (response.status === 204) { // Laravel Breeze retorna 204 para login bem-sucedido
        await this.fetchUser();
        return true;
      }
      return false;
    },
    async register(userData) {
      await axios.get('/sanctum/csrf-cookie');
      const response = await axios.post('/register', userData);
      if (response.status === 201) { // Laravel Breeze retorna 201 para registro bem-sucedido
        await this.fetchUser();
        return true;
      }
      return false;
    },
    async logout() {
      try {
        await axios.post('/logout');
        this.user = null;
        this.isAuthenticated = false;
        this.isAdmin = false;
        localStorage.removeItem('isAuthenticated');
        return true;
      } catch (error) {
        console.error('Erro ao fazer logout:', error);
        return false;
      }
    }
  },
  getters: {
    getIsAdmin: (state) => state.isAdmin,
    getIsAuthenticated: (state) => state.isAuthenticated,
    getUser: (state) => state.user,
  },
});