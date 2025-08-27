<template>
  <div class="profiles-container">
    <div class="profiles-header">
      <h1 class="profiles-title">Gerenciamento de Perfis</h1>
      <button @click="openCreateModal" class="create-button">
        Criar Novo Perfil
      </button>
    </div>

    <div v-if="loading" class="profiles-status-message">Carregando perfis...</div>
    <div v-if="error" class="profiles-error-message">{{ error }}</div>

    <div v-if="profiles.length > 0" class="profiles-table-wrapper">
      <table class="profiles-table">
        <thead>
          <tr>
            <th class="table-header">ID</th>
            <th class="table-header">Nome do Perfil</th>
            <th class="table-header">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="profile in profiles" :key="profile.id" class="table-row">
            <td class="table-cell">
              <p>{{ profile.id }}</p>
            </td>
            <td class="table-cell">
              <p>{{ profile.name }}</p>
            </td>
            <td class="table-cell actions-cell">
              <button @click="openEditModal(profile)" class="action-button edit-button">Editar</button>
              <button @click="deleteProfile(profile.id)" class="action-button delete-button">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else-if="!loading" class="profiles-status-message">Nenhum perfil encontrado.</div>

    <!-- Modal para Criar/Editar Perfil -->
    <div v-if="isModalOpen" class="modal-overlay">
      <div class="modal-card">
        <h2 class="modal-title">{{ currentProfile.id ? 'Editar Perfil' : 'Criar Perfil' }}</h2>
        <form @submit.prevent="saveProfile">
          <div class="form-group">
            <label for="profileName" class="form-label">Nome do Perfil:</label>
            <input
              type="text"
              id="profileName"
              v-model="currentProfile.name"
              class="form-input"
              required
            />
            <p v-if="modalError" class="modal-error-message">{{ modalError }}</p>
          </div>
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
import { ref, onMounted } from 'vue';
import apiClient from '@/services/api'; // Importa a nova instância do Axios

const profiles = ref([]);
const loading = ref(true);
const error = ref(null);
const isModalOpen = ref(false);
const currentProfile = ref({ id: null, name: '' });
const modalError = ref(null);

const fetchProfiles = async () => {
  loading.value = true;
  error.value = null;
  try {
    // Usa a instância 'apiClient' para a requisição
    const response = await apiClient.get('/api/profiles');
    profiles.value = response.data;
  } catch (err) {
    if (err.response && err.response.status === 401) {
        error.value = 'Não autorizado. Por favor, faça login ou você não tem permissão para ver os perfis.';
    } else {
      error.value = 'Erro ao carregar perfis.';
    }
    console.error('Erro ao buscar perfis:', err);
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  currentProfile.value = { id: null, name: '' };
  modalError.value = null;
  isModalOpen.value = true;
};

const openEditModal = (profile) => {
  currentProfile.value = { ...profile };
  modalError.value = null;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const saveProfile = async () => {
  modalError.value = null;
  try {
    if (currentProfile.value.id) {
      await apiClient.put(`/api/profiles/${currentProfile.value.id}`, currentProfile.value);
    } else {
      await apiClient.post('/api/profiles', currentProfile.value);
    }
    closeModal();
    fetchProfiles();
  } catch (err) {
    if (err.response && err.response.data && err.response.data.errors) {
      modalError.value = Object.values(err.response.data.errors).flat().join(' ');
    } else if (err.response && err.response.data && err.response.data.message) {
      modalError.value = err.response.data.message;
    } else {
      modalError.value = 'Erro ao salvar perfil. Verifique os dados.';
    }
    console.error('Erro ao salvar perfil:', err);
  }
};

const deleteProfile = async (id) => {
  if (confirm('Tem certeza que deseja excluir este perfil?')) {
    try {
      await apiClient.delete(`/api/profiles/${id}`);
      fetchProfiles();
    } catch (err) {
      if (err.response && err.response.status === 403) {
        alert('Você não tem permissão para excluir perfis.');
      } else {
        alert('Erro ao excluir perfil. Tente novamente.');
      }
      console.error('Erro ao excluir perfil:', err);
    }
  }
};

onMounted(() => {
  fetchProfiles();
});
</script>


<style scoped>
/* Estilos CSS Puros */
.profiles-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Inter', sans-serif;
  background-color: #f7fafc;
  min-height: 100vh;
}

.profiles-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.profiles-title {
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

.profiles-status-message {
  text-align: center;
  color: #4a5568;
  padding: 2rem;
}

.profiles-error-message {
  text-align: center;
  color: #e53e3e;
  padding: 2rem;
}

.profiles-table-wrapper {
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

.profiles-table {
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