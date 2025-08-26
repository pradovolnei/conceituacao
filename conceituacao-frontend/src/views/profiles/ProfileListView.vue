<template>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Gerenciamento de Perfis</h1>

    <div class="mb-6 flex justify-end">
      <button @click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300">
        Criar Novo Perfil
      </button>
    </div>

    <div v-if="loading" class="text-center text-gray-600">Carregando perfis...</div>
    <div v-if="error" class="text-center text-red-600">{{ error }}</div>

    <div v-if="profiles.length > 0" class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full leading-normal">
        <thead>
          <tr>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              ID
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Nome do Perfil
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Ações
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="profile in profiles" :key="profile.id">
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ profile.id }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ profile.name }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <button @click="openEditModal(profile)" class="text-blue-600 hover:text-blue-900 mr-3">Editar</button>
              <button @click="deleteProfile(profile.id)" class="text-red-600 hover:text-red-900">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else-if="!loading" class="text-center text-gray-600">Nenhum perfil encontrado.</div>

    <!-- Modal para Criar/Editar Perfil -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ currentProfile.id ? 'Editar Perfil' : 'Criar Perfil' }}</h2>
        <form @submit.prevent="saveProfile">
          <div class="mb-4">
            <label for="profileName" class="block text-gray-700 text-sm font-bold mb-2">Nome do Perfil:</label>
            <input
              type="text"
              id="profileName"
              v-model="currentProfile.name"
              class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            />
            <p v-if="modalError" class="text-red-500 text-xs italic mt-2">{{ modalError }}</p>
          </div>
          <div class="flex justify-end space-x-3">
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition duration-300"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300"
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
import axios from 'axios';

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
    const response = await axios.get('/api/profiles');
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
      // Editar perfil existente
      await axios.put(`/api/profiles/${currentProfile.value.id}`, currentProfile.value);
    } else {
      // Criar novo perfil
      await axios.post('/api/profiles', currentProfile.value);
    }
    closeModal();
    fetchProfiles(); // Recarregar a lista de perfis
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
      await axios.delete(`/api/profiles/${id}`);
      fetchProfiles(); // Recarregar a lista de perfis
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