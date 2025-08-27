<template>
  <div class="users-container">
    <div class="users-header">
      <h1 class="users-title">Gerenciamento de Usuários</h1>
      <button @click="openCreateModal" class="create-button">
        Criar Novo Usuário
      </button>
    </div>

    <div v-if="loading" class="users-status-message">Carregando usuários...</div>
    <div v-if="error" class="users-error-message">{{ error }}</div>

    <div v-if="users.length > 0" class="users-table-wrapper">
      <table class="users-table">
        <thead>
          <tr>
            <th class="table-header">ID</th>
            <th class="table-header">Nome</th>
            <th class="table-header">Email</th>
            <th class="table-header">Perfis</th>
            <th class="table-header">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="table-row">
            <td class="table-cell">
              <p>{{ user.id }}</p>
            </td>
            <td class="table-cell">
              <p>{{ user.name }}</p>
            </td>
            <td class="table-cell">
              <p>{{ user.email }}</p>
            </td>
            <td class="table-cell">
              <span v-for="profile in user.profiles" :key="profile.id" class="user-profile-badge">
                {{ profile.name }}
              </span>
            </td>
            <td class="table-cell actions-cell">
              <button @click="openEditModal(user)" class="action-button edit-button">Editar</button>
              <button @click="deleteUser(user.id)" class="action-button delete-button">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else-if="!loading" class="users-status-message">Nenhum usuário encontrado.</div>

    <!-- Modal para Criar/Editar Usuário -->
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal-card">
        <h2 class="modal-title">{{ currentUser.id ? 'Editar Usuário' : 'Criar Usuário' }}</h2>
        <form @submit.prevent="saveUser">
          <div class="form-group">
            <label for="userName" class="form-label">Nome:</label>
            <input
              type="text"
              id="userName"
              v-model="currentUser.name"
              class="form-input"
              required
            />
          </div>
          <div class="form-group">
            <label for="userEmail" class="form-label">Email:</label>
            <input
              type="email"
              id="userEmail"
              v-model="currentUser.email"
              class="form-input"
              required
            />
          </div>
          <div class="form-group">
            <label for="userPassword" class="form-label">Senha (deixe em branco para não alterar):</label>
            <input
              type="password"
              id="userPassword"
              v-model="currentUser.password"
              class="form-input"
            />
          </div>
          <div class="form-group" v-if="currentUser.password">
            <label for="userPasswordConfirmation" class="form-label">Confirmar Senha:</label>
            <input
              type="password"
              id="userPasswordConfirmation"
              v-model="currentUser.password_confirmation"
              class="form-input"
            />
          </div>
          <div class="form-group">
            <label for="userProfiles" class="form-label">Perfis:</label>
            <select
              id="userProfiles"
              v-model="selectedProfileIds"
              multiple
              class="form-input form-select"
            >
              <option v-for="profile in allProfiles" :key="profile.id" :value="profile.id">
                {{ profile.name }}
              </option>
            </select>
          </div>

          <div v-if="modalError" class="modal-error-message">{{ modalError }}</div>
          <div class="modal-actions">
            <button
              type="button"
              @click="closeModal"
              class="modal-button cancel-button"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="modal-button save-button"
            >
              Salvar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import apiClient from '@/services/api'; // Importa a nova instância do Axios

const users = ref([]);
const allProfiles = ref([]); // Para preencher o select de perfis
const loading = ref(true);
const error = ref(null);
const isModalOpen = ref(false);
const currentUser = ref({ id: null, name: '', email: '', password: '', password_confirmation: '' });
const selectedProfileIds = ref([]); // IDs dos perfis selecionados para o usuário atual
const modalError = ref(null);

const fetchUsers = async () => {
  loading.value = true;
  error.value = null;
  try {
    const response = await apiClient.get('/api/users');
    users.value = response.data;
  } catch (err) {
    if (err.response && err.response.status === 401) {
      error.value = 'Não autorizado. Por favor, faça login ou você não tem permissão para ver os usuários.';
    } else {
      error.value = 'Erro ao carregar usuários.';
    }
    console.error('Erro ao buscar usuários:', err);
  } finally {
    loading.value = false;
  }
};

const fetchProfiles = async () => {
  try {
    const response = await apiClient.get('/api/profiles');
    allProfiles.value = response.data;
  } catch (err) {
    console.error('Erro ao buscar todos os perfis:', err);
  }
};

const openCreateModal = () => {
  currentUser.value = { id: null, name: '', email: '', password: '', password_confirmation: '' };
  selectedProfileIds.value = [];
  modalError.value = null;
  isModalOpen.value = true;
};

const openEditModal = (user) => {
  currentUser.value = { ...user, password: '', password_confirmation: '' }; // Não preencher senha
  selectedProfileIds.value = user.profiles.map(p => p.id);
  modalError.value = null;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveUser = async () => {
  modalError.value = null;
  const userData = { ...currentUser.value, profile_ids: selectedProfileIds.value };

  // Remove password fields if empty to avoid validation errors on update
  if (!userData.password) {
    delete userData.password;
    delete userData.password_confirmation;
  }

  try {
    if (currentUser.value.id) {
      // Editar usuário existente
      await apiClient.put(`/api/users/${currentUser.value.id}`, userData);
    } else {
      // Criar novo usuário
      await apiClient.post('/api/users', userData);
    }
    closeModal();
    fetchUsers(); // Recarregar a lista de usuários
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
      modalError.value = Object.values(err.response.data.errors).flat().join(' ');
    } else if (err.response && err.response.data && err.response.data.message) {
      modalError.value = err.response.data.message;
    } else {
      modalError.value = 'Erro ao salvar usuário. Verifique os dados.';
    }
    console.error('Erro ao salvar usuário:', err);
  }
};

const deleteUser = async (id) => {
  if (confirm('Tem certeza que deseja excluir este usuário?')) {
    try {
      await apiClient.delete(`/users/${id}`);
      fetchUsers(); // Recarregar a lista de usuários
    } catch (err) {
      if (err.response && err.response.status === 403) {
        // Use uma modal em vez de alert
        console.error('Você não tem permissão para excluir usuários.');
      } else {
        console.error('Erro ao excluir usuário. Tente novamente.');
      }
      console.error('Erro ao excluir usuário:', err);
    }
  }
};

onMounted(() => {
  fetchUsers();
  fetchProfiles();
});
</script>

<style scoped>
/* Estilos CSS Puros */
.users-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Inter', sans-serif;
  background-color: #f7fafc;
  min-height: 100vh;
}

.users-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.users-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1a202c;
}

.create-button {
  background-color: #3b82f6;
  color: #ffffff;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background-color 0.3s ease, transform 0.2s ease;
  border: none;
  cursor: pointer;
}

.create-button:hover {
  background-color: #2563eb;
  transform: translateY(-2px);
}

.users-status-message {
  text-align: center;
  color: #4a5568;
  padding: 2rem;
}

.users-error-message {
  text-align: center;
  color: #e53e3e;
  padding: 2rem;
}

.users-table-wrapper {
  overflow-x: auto;
  background-color: #ffffff;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.users-table {
  width: 100%;
  border-collapse: collapse;
}

.table-header {
  padding: 1.5rem 2rem;
  background-color: #e2e8f0;
  color: #4a5568;
  font-size: 0.75rem;
  font-weight: 600;
  text-align: left;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-bottom: 2px solid #cbd5e0;
}

.table-row {
  transition: background-color 0.3s ease;
}

.table-row:nth-child(even) {
  background-color: #f7fafc;
}

.table-row:hover {
  background-color: #edf2f7;
}

.table-cell {
  padding: 1rem 2rem;
  background-color: transparent;
  color: #2d3748;
  font-size: 0.875rem;
  border-bottom: 1px solid #e2e8f0;
}

.user-profile-badge {
  display: inline-block;
  background-color: #bfdbfe;
  color: #1e40af;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  margin-right: 0.5rem;
}

.actions-cell {
  white-space: nowrap;
}

.action-button {
  font-weight: 600;
  transition: color 0.3s ease, transform 0.2s ease;
  border: none;
  background: none;
  cursor: pointer;
}

.action-button:hover {
  transform: translateY(-1px);
}

.edit-button {
  color: #3b82f6;
  margin-right: 1rem;
}

.edit-button:hover {
  color: #2563eb;
}

.delete-button {
  color: #e53e3e;
}

.delete-button:hover {
  color: #c53030;
}

/* Estilos do Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-card {
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 0.75rem;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 500px;
  animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.modal-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1a202c;
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

.form-select {
  height: auto;
}

.modal-error-message {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.modal-button {
  font-weight: 600;
  padding: 0.625rem 1.25rem;
  border-radius: 0.5rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  border: none;
  cursor: pointer;
}

.modal-button:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cancel-button {
  background-color: #cbd5e0;
  color: #2d3748;
}

.cancel-button:hover {
  background-color: #a0aec0;
}

.save-button {
  background-color: #38a169;
  color: #ffffff;
}

.save-button:hover {
  background-color: #2f855a;
}
</style>