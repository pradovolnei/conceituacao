<template>
  <div class="login-container">
    <div class="login-card">
      <h2 class="login-title">Acesso</h2>
      <p class="login-subtitle">
        Insira os seus detalhes para aceder à sua conta.
      </p>

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
          id="email"
          v-model="form.email"
          type="email"
          class="form-input"
          placeholder="admin@example.com"
        />
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Senha</label>
        <input
          id="password"
          v-model="form.password"
          type="password"
          class="form-input"
          placeholder="********"
        />
      </div>

      <p v-if="error" class="error-message">
        {{ error }}
      </p>

      <button
        @click.prevent="login"
        class="login-button"
      >
        Entrar
      </button>

      <!-- Novo link de cadastro -->
      <div class="register-link-container">
        <router-link to="/register" class="register-link">
          Não tem uma conta? Crie uma aqui.
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useAuthStore } from '@/stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  email: '',
  password: '',
});
const error = ref(null);

const login = async () => {
  error.value = null; // Limpa o erro anterior

  try {
    // 1. Obtém o cookie CSRF. Esta chamada deve ser feita na URL base, sem o prefixo '/api'.
    await axios.get('/sanctum/csrf-cookie');

    // 2. Tenta fazer a requisição de login.
    const response = await axios.post('/login', form.value);

    // 3. Verifica se a resposta contém os dados do utilizador.
    if (response.data && response.data.user) {
      authStore.setUser(response.data.user);
      router.push('/');
    } else {
      error.value = 'Resposta inesperada do servidor.';
    }

  } catch (err) {
    console.error('Erro de login:', err);
    if (err.response) {
      if (err.response.status === 419) {
        error.value = 'Sessão expirada. Por favor, tente novamente.';
      } else if (err.response.data && err.response.data.message) {
        error.value = err.response.data.message;
      } else {
        error.value = 'Ocorreu um erro ao fazer o login. Por favor, tente novamente.';
      }
    } else {
      error.value = 'Não foi possível conectar ao servidor. Verifique a sua conexão.';
    }
  }
};
</script>

<style scoped>
/* Estilos CSS Puros */
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #e0f2fe 0%, #d1e5f4 100%);
  font-family: 'Inter', sans-serif;
}

.login-card {
  width: 100%;
  max-width: 400px;
  padding: 2.5rem;
  background: #ffffff;
  border-radius: 1rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.login-title {
  font-size: 2rem;
  font-weight: 700;
  text-align: center;
  color: #1a202c;
  margin-bottom: 0.5rem;
}

.login-subtitle {
  font-size: 1rem;
  text-align: center;
  color: #4a5568;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4a5568;
  margin-bottom: 0.25rem;
}

.form-input {
  display: block;
  width: 100%;
  padding: 0.75rem 1rem;
  margin-top: 0.25rem;
  color: #1a202c;
  background-color: #f7fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.form-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}

.login-button {
  width: 100%;
  padding: 0.75rem 1rem;
  margin-top: 1.5rem;
  color: #ffffff;
  background: #3b82f6;
  border-radius: 0.5rem;
  font-weight: 700;
  text-align: center;
  transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
  cursor: pointer;
  border: none;
}

.login-button:hover {
  background: #2563eb;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.login-button:active {
  transform: translateY(0);
}

.error-message {
  margin-top: 1rem;
  font-size: 0.875rem;
  text-align: center;
  color: #ef4444;
}

.register-link-container {
  text-align: center;
  margin-top: 1rem;
}

.register-link {
  font-size: 0.875rem;
  color: #4a5568;
  text-decoration: none;
  transition: color 0.3s ease;
}

.register-link:hover {
  color: #3b82f6;
  text-decoration: underline;
}
</style>
