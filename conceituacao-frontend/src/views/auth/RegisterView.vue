<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Registro</h2>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <div class="mb-4">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <div class="mb-6">
          <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Senha:</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            required
          />
        </div>
        <div v-if="error" class="text-red-500 text-xs italic mb-4">{{ error }}</div>
        <div class="flex items-center justify-between">
          <button
            type="submit"
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
          >
            Registrar
          </button>
        </div>
        <p class="text-center text-gray-600 text-sm mt-4">
          Já tem uma conta?
          <router-link to="/login" class="text-blue-500 hover:text-blue-800">Faça login</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});
const error = ref(null);

const register = async () => {
  error.value = null;
  try {
    await axios.get('/sanctum/csrf-cookie');
    const response = await axios.post('/register', form.value);
    console.log('Registro bem-sucedido:', response.data);
    localStorage.setItem('isAuthenticated', 'true');
    router.push('/'); // Redirecionar para a página inicial ou dashboard após registro
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
      error.value = Object.values(err.response.data.errors).flat().join(' ');
    } else if (err.response && err.response.data && err.response.data.message) {
      error.value = err.response.data.message;
    } else {
      error.value = 'Erro ao registrar. Tente novamente.';
    }
    console.error('Erro de registro:', err);
  }
};
</script>
