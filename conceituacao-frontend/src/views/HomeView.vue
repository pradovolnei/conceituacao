<template>
  <div class="p-8 text-center">
    <h1 class="text-3xl font-bold mb-4">Bem-vindo!</h1>
    <p v-if="isAuthenticated" class="text-lg">Você está logado.</p>
    <p v-else class="text-lg">Por favor, faça login ou registre-se.</p>
    <button v-if="isAuthenticated" @click="logout" class="mt-6 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
      Sair
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const isAuthenticated = ref(false);

onMounted(() => {
  isAuthenticated.value = localStorage.getItem('isAuthenticated') === 'true';
});

const logout = async () => {
  try {
    await axios.post('/logout'); // Rota de logout do Laravel Breeze
    localStorage.removeItem('isAuthenticated');
    isAuthenticated.value = false;
    router.push('/login');
  } catch (error) {
    console.error('Erro ao fazer logout:', error);
  }
};
</script>