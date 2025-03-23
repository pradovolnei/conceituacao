import { defineStore } from 'pinia'
import { ref } from 'vue'
const runtimeConfig = useRuntimeConfig()

export interface UserPayloadInterface {
    username: string;
    password: string;
}

export const useAuthStore = defineStore('auth', () => {
    const isAuthenticate = ref(false)
    const loading = ref(false)

    const authenticate = async (payload: UserPayloadInterface) => {
        const { data } = await useFetch(`${runtimeConfig.public.API_URL}/auth/login`, {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: {
                ...payload
            },
        })

        if (data.value) {
            const token = useCookie('token') // useCookie new hook in nuxt 3
            const user = useCookie('user') // useCookie new hook in nuxt 3
            token.value = data?.value?.token // set token to cookie
            user.value = data?.value?.user // set token to cookie
            isAuthenticate.value = true
            return true
        }

        return false

    }

    return { authenticate, isAuthenticate }
})