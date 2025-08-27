<template>
  <!-- Container principal, centraliza o card de registro -->
  <div class="register-container">
    <!-- Card de registro -->
    <div class="register-card">
      <h2 class="register-title">Registro</h2>
      <form @submit.prevent="register">
        <!-- Campo de Nome -->
        <div class="form-group">
          <label for="name" class="form-label">Nome:</label>
          <input
            type="text"
            id="name"
            v-model="form.name"
            class="form-input"
            required
          />
        </div>
        <!-- Campo de Email -->
        <div class="form-group">
          <label for="email" class="form-label">Email:</label>
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="form-input"
            required
          />
        </div>
        <!-- Campo de Senha -->
        <div class="form-group">
          <label for="password" class="form-label">Senha:</label>
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="form-input"
            required
          />
        </div>
        <!-- Campo de Confirmação de Senha -->
        <div class="form-group">
          <label for="password_confirmation" class="form-label">Confirmar Senha:</label>
          <input
            type="password"
            id="password_confirmation"
            v-model="form.password_confirmation"
            class="form-input"
            required
          />
        </div>
        <!-- Mensagem de Erro -->
        <div v-if="error" class="error-message">{{ error }}</div>

        <!-- Botão de Registrar -->
        <button
          type="submit"
          class="register-button"
        >
          Registrar
        </button>

        <!-- Link para a tela de login -->
        <p class="login-text">
          Já tem uma conta?
          <router-link to="/login" class="login-link">
            Faça login
          </router-link>
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
    // CORREÇÃO: Remova o prefixo '/api' da URL
    const response = await axios.post('/register', form.value);
    console.log('Registro bem-sucedido:', response.data);
    localStorage.setItem('isAuthenticated', 'true');
    router.push('/');
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

<style scoped>
/* Estilos para o container principal, com fundo cinza claro */
.register-container {
  min-height: calc(100vh - 8rem);
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f3f4f6;
  padding: 1rem;
}

/* Estilos para o card de registro */
.register-card {
  background-color: #ffffff;
  padding: 2.5rem;
  border-radius: 1.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  width: 100%;
  max-width: 24rem;
}

/* Estilos para o título */
.register-title {
  font-size: 2rem;
  font-weight: 800;
  margin-bottom: 2rem;
  text-align: center;
  color: #1f2937;
}

/* Estilos para o grupo de formulário (label + input) */
.form-group {
  margin-bottom: 1.25rem;
}

/* Estilos para o label */
.form-label {
  display: block;
  color: #374151;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

/* Estilos para o input */
.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  color: #374151;
  background-color: #f9fafb;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  outline: none;
  transition: all 0.2s ease-in-out;
}

.form-input:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
}

/* Estilos para a mensagem de erro */
.error-message {
  color: #ef4444;
  font-size: 0.875rem;
  font-weight: 600;
  font-style: italic;
  margin-bottom: 1rem;
  text-align: center;
}

/* Estilos para o botão de registro */
.register-button {
  width: 100%;
  background-color: #10b981;
  color: #ffffff;
  font-weight: 700;
  padding: 0.75rem 1rem;
  border-radius: 9999px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: all 0.2s ease-in-out;
  border: none;
}

.register-button:hover {
  background-color: #059669;
}

/* Estilos para o texto "Já tem uma conta?" */
.login-text {
  text-align: center;
  color: #4b5563;
  font-size: 0.875rem;
  margin-top: 1.5rem;
}

/* Estilos para o link de login */
.login-link {
  color: #2563eb;
  font-weight: 600;
  transition: all 0.2s ease-in-out;
}

.login-link:hover {
  color: #1e40af;
}

/* Media query para responsividade */
@media (min-width: 768px) {
  .register-card {
    padding: 3rem;
  }
}
</style>
