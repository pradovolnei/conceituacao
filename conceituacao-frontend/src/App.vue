<template>
  <div id="app">
    <nav class="bg-gray-800 p-4 text-white">
      <div class="container mx-auto flex justify-between items-center">
        <router-link to="/" class="font-bold text-xl">App Gerenciamento</router-link>
        <div>
          <template v-if="authStore.isAuthenticated">
            <router-link to="/" class="mr-4 hover:text-gray-300">Home</router-link>
            <router-link v-if="authStore.isAdmin" to="/profiles" class="mr-4 hover:text-gray-300">Perfis</router-link>
            <router-link v-if="authStore.isAdmin" to="/users" class="mr-4 hover:text-gray-300">Usuários</router-link>
            <button @click="logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded-md">Sair</button>
          </template>
          <template v-else>
            <router-link to="/login" class="mr-4 hover:text-gray-300">Login</router-link>
            <router-link to="/register" class="hover:text-gray-300">Registrar</router-link>
          </template>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';
import { onMounted } from 'vue';

const authStore = useAuthStore();
const router = useRouter();

onMounted(() => {
  // Tenta buscar o usuário ao carregar a aplicação se já houver um estado de autenticação
  if (authStore.getIsAuthenticated && !authStore.user) {
    authStore.fetchUser();
  }
});

const logout = async () => {
  const success = await authStore.logout();
  if (success) {
    router.push('/login');
  }
};
</script>