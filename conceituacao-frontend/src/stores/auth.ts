import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const isAuthenticated = ref(false);
  const user = ref(null);

  function setUser(userData) {
    user.value = userData;
    isAuthenticated.value = !!userData;
  }

  function clearUser() {
    user.value = null;
    isAuthenticated.value = false;
  }
  
  // Exemplo de uma propriedade computada para verificar se é admin
  const userIsAdmin = computed(() => {
    if (!user.value || !user.value.profiles) {
      return false;
    }
    return user.value.profiles.some(profile => profile.name === 'Administrador');
  });

  return { isAuthenticated, user, setUser, clearUser, userIsAdmin };
});
