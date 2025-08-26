<template>
  <div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Gerenciamento de Usuários</h1>

    <div class="mb-6 flex justify-end">
      <button @click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300">
        Criar Novo Usuário
      </button>
    </div>

    <div v-if="loading" class="text-center text-gray-600">Carregando usuários...</div>
    <div v-if="error" class="text-center text-red-600">{{ error }}</div>

    <div v-if="users.length > 0" class="overflow-x-auto bg-white rounded-lg shadow-md">
      <table class="min-w-full leading-normal">
        <thead>
          <tr>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              ID
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Nome
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Email
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Perfis
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Ações
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ user.id }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ user.name }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ user.email }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <span v-for="profile in user.profiles" :key="profile.id" class="inline-block bg-blue-200 text-blue-800 text-xs px-2 rounded-full mr-2">
                {{ profile.name }}
              </span>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <button @click="openEditModal(user)" class="text-blue-600 hover:text-blue-900 mr-3">Editar</button>
              <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div v-else-if="!loading" class="text-center text-gray-600">Nenhum usuário encontrado.</div>

    <!-- Modal para Criar/Editar Usuário -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ currentUser.id ? 'Editar Usuário' : 'Criar Usuário' }}</h2>
        <form @submit.prevent="saveUser">
          <div class="mb-4">
            <label for="userName" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
            <input
              type="text"
              id="userName"
              v-model="currentUser.name"
              class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            />
          </div>
          <div class="mb-4">
            <label for="userEmail" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input
              type="email"
              id="userEmail"
              v-model="currentUser.email"
              class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            />
          </div>
          <div class="mb-4">
            <label for="userPassword" class="block text-gray-700 text-sm font-bold mb-2">Senha (deixe em branco para não alterar):</label>
            <input
              type="password"
              id="userPassword"
              v-model="currentUser.password"
              class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
          </div>
          <div class="mb-4" v-if="currentUser.password">
            <label for="userPasswordConfirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirmar Senha:</label>
            <input
              type="password"
              id="userPasswordConfirmation"
              v-model="currentUser.password_confirmation"
              class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
          </div>
          <div class="mb-6">
            <label for="userProfiles" class="block text-gray-700 text-sm font-bold mb-2">Perfis:</label>
            <select
              id="userProfiles"
              v-model="selectedProfileIds"
              multiple
              class="block w-full text-gray-700 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
              <option v-for="profile in allProfiles" :key="profile.id" :value="profile.id">
                {{ profile.name }}
              </option>
            </select>
          </div>

          <div v-if="modalError" class="text-red-500 text-xs italic mb-4">{{ modalError }}</div>
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
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

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
    const response = await axios.get('/api/users');
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
    const response = await axios.get('/api/profiles');
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
      await axios.put(`/api/users/${currentUser.value.id}`, userData);
    } else {
      // Criar novo usuário
      await axios.post('/api/users', userData);
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
      await axios.delete(`/api/users/${id}`);
      fetchUsers(); // Recarregar a lista de usuários
    } catch (err) {
      if (err.response && err.response.status === 403) {
        alert('Você não tem permissão para excluir usuários.');
      } else {
        alert('Erro ao excluir usuário. Tente novamente.');
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