import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/auth/LoginView.vue';
import RegisterView from '../views/auth/RegisterView.vue';
import ProfileListView from '../views/profiles/ProfileListView.vue';
import UserListView from '../views/users/UserListView.vue';
import { useAuthStore } from '../stores/auth';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/profiles',
      name: 'profiles',
      component: ProfileListView,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
    {
      path: '/users',
      name: 'users',
      component: UserListView,
      meta: { requiresAuth: true, requiresAdmin: true }
    },
  ],
})

router.beforeEach((to, from, next) => {

  const authStore = useAuthStore();

  // Garante que os dados do usuário sejam carregados antes de qualquer verificação de rota
  if (!authStore.user && authStore.isAuthenticated && !authStore.loadingUser) {
    await authStore.fetchUser();
  }

  const isAuthenticated = localStorage.getItem('isAuthenticated');
  const isAdmin = authStore.getIsAdmin;

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else if (to.meta.requiresAdmin && !isAdmin) {
    next('/'); // Redireciona para home ou uma página de erro 403
  } else if ((to.name === 'login' || to.name === 'register') && isAuthenticated) {
    next('/');
  } else {
    next();
  }
})

export default router
