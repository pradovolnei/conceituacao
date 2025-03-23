import { useAuthStore } from '~/store/auth'

export default defineNuxtRouteMiddleware(() => {
    // redirect the user to the login screen if they're not authenticated
    const { isAuthenticate } = useAuthStore()

    if (!isAuthenticate) {
        return navigateTo('/auth/login')
    }
})