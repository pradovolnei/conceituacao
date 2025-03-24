import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
const runtimeConfig = useRuntimeConfig()
const jwtTokenCookie = useCookie('token')
const userCookie = useCookie('user')

export interface UserPayloadInterface {
    username: string;
    password: string;
}

export const useAuthStore = defineStore('auth', () => {
    const token = ref(jwtTokenCookie.value)
    const user = ref(userCookie.value)


    const isAuthenticate = computed(() => {
        return (token.value && token.value?.length > 0)
    })

    const isAdministrator = computed(() => {
        const filtered = user.value?.profiles?.filter((profile) => profile.name === 'Administrador')
        return (filtered && filtered.length > 0)
    })


    const authenticate = async (payload: UserPayloadInterface) => {
        const { data } = await useFetchApi(`${runtimeConfig.public.API_URL}/auth/login`, {
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: {
                ...payload
            },
        })

        if (data.value?.token) {
            const token = useCookie('token') // useCookie new hook in nuxt 3
            const user = useCookie('user') // useCookie new hook in nuxt 3
            token.value = data?.value?.token // set token to cookie
            user.value = data?.value?.user // set token to cookie
            return true
        }

        return false

    }

    const logout = () => {
        token.value = null
        user.value = null
        jwtTokenCookie.value = null
        userCookie.value = null

        navigateTo('/auth/login')
    }

    return { authenticate, logout,  isAuthenticate, isAdministrator, token, user }
})