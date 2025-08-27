<template>
  <!-- Container principal da página, centraliza o conteúdo -->
  <div class="home-container">
    <!-- Card principal com o conteúdo de boas-vindas -->
    <div class="home-card">
      <h1 class="home-title">
        Bem-vindo!
      </h1>
      <!-- Parágrafo exibido se o usuário estiver autenticado -->
      <p v-if="authStore.isAuthenticated" class="welcome-text">
        Você está logado como <span class="user-name">{{ authStore.user?.name }}</span>.
      </p>
      <!-- Parágrafo exibido se o usuário não estiver autenticado -->
      <p v-else class="welcome-text">
        Por favor, faça login ou registre-se para acessar o sistema.
      </p>
      <!-- Seção de botões para login e registro (apenas se não autenticado) -->
      <div v-if="!authStore.isAuthenticated" class="button-group">
        <router-link
          to="/login"
          class="home-button home-button-login"
        >
          Login
        </router-link>
        <router-link
          to="/register"
          class="home-button home-button-register"
        >
          Registrar
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth';

// Acessa o store de autenticação para verificar o estado do usuário
const authStore = useAuthStore();
</script>

<style scoped>
/* Estilos para o container da página, garantindo que ele ocupe a tela inteira */
.home-container {
  min-height: calc(100vh - 8rem); /* Altura total da tela menos o cabeçalho */
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f3f4f6;
  padding: 1rem;
}

/* Estilos para o card de boas-vindas */
.home-card {
  background-color: #ffffff;
  padding: 3rem;
  border-radius: 1.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  width: 100%;
  max-width: 32rem;
  text-align: center;
  transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.home-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Estilos para o título */
.home-title {
  font-size: 3rem;
  font-weight: 800;
  color: #1f2937;
  margin-bottom: 1rem;
}

/* Estilos para o texto de boas-vindas */
.welcome-text {
  font-size: 1.125rem;
  color: #4b5563;
  margin-bottom: 1.5rem;
}

/* Estilos para o nome do usuário */
.user-name {
  font-weight: 600;
  color: #2563eb;
}

/* Estilos para o grupo de botões */
.button-group {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

/* Estilos gerais para os botões */
.home-button {
  display: inline-block;
  font-weight: 700;
  padding: 0.75rem 1.5rem;
  border-radius: 9999px;
  text-decoration: none;
  transition: all 0.3s ease-in-out;
}

/* Estilos específicos para o botão de login */
.home-button-login {
  background-color: #2563eb;
  color: #ffffff;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.home-button-login:hover {
  background-color: #1e40af;
}

/* Estilos específicos para o botão de registro */
.home-button-register {
  background-color: #e5e7eb;
  color: #1f2937;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.home-button-register:hover {
  background-color: #d1d5db;
}

/* Media query para telas menores (responsividade) */
@media (max-width: 768px) {
  .home-card {
    padding: 2rem;
  }
}
</style>