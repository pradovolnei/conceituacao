<template>
  <nav class="navbar">
    <div class="navbar-container">
      <router-link to="/" class="brand">App Gerenciamento</router-link>
      <div class="nav-links">
        <router-link to="/" class="nav-item">Home</router-link>
        
        <!-- Renderiza os links apenas se o usuário for administrador -->
        <template v-if="authStore.userIsAdmin">
          <router-link to="/profiles" class="nav-item">Perfis</router-link>
          <router-link to="/users" class="nav-item">Usuários</router-link>
        </template>
        
        <button v-if="authStore.isAuthenticated" @click="logout" class="logout-btn">Sair</button>
        <router-link v-else to="/login" class="nav-item">Login</router-link>
      </div>
    </div>
  </nav>
  <main class="main-content">
    <router-view></router-view>
  </main>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import axios from 'axios';

const authStore = useAuthStore();
const router = useRouter();

const logout = async () => {
  try {
    // Chamada para a rota de logout da sua API
    await axios.post('/api/logout');
  } catch (error) {
    console.error('Logout failed:', error);
  } finally {
    // Limpa o estado e redireciona, independente da resposta da API
    authStore.clearUser();
    router.push('/login');
  }
};
</script>

<style scoped>
.navbar {
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 1rem 2rem;
}

.navbar-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

.brand {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
  text-decoration: none;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.nav-item {
  color: #555;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.nav-item:hover {
  color: #007bff;
}

.logout-btn {
  background-color: #dc3545;
  color: #ffffff;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  cursor: pointer;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.logout-btn:hover {
  background-color: #c82333;
}

.main-content {
  padding: 2rem;
  text-align: center;
}
</style>
