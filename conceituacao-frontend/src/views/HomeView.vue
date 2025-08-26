<template>
  <div class="p-8 text-center">
    <h1 class="text-3xl font-bold mb-4">Bem-vindo!</h1>
    <p v-if="authStore.isAuthenticated" class="text-lg">Você está logado como {{ authStore.user?.name }}.</p>
    <p v-else class="text-lg">Por favor, faça login ou registre-se.</p>
    <button v-if="authStore.isAuthenticated" @click="logout" class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
      Sair
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '../stores/auth'; 

const router = useRouter();
const authStore = useAuthStore();
const isAuthenticated = ref(false);

onMounted(() => {
  isAuthenticated.value = localStorage.getItem('isAuthenticated') === 'true';
  if (authStore.getIsAuthenticated && !authStore.user && !authStore.loadingUser) {
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