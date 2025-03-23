const runtimeConfig = useRuntimeConfig()

class UserServiceHttpService {
    protected resource = '/users'

    async getAll() {
        const route = runtimeConfig.public.API_URL + this.resource
        return await useFetchApi(route, {
            method: 'get'
        })
    }

    async create(payload: object) {
        const route = runtimeConfig.public.API_URL + this.resource
        return  await useFetchApi(route, {
            method: 'post',
            body: {
                ...payload
            },
        })
    }

    async update(id: number, payload: object) {
        const route = runtimeConfig.public.API_URL + this.resource
        return  await useFetchApi(`${route}/${id}`, {
            method: 'put',
            body: {
                ...payload
            },
        })
    }

    async delete(id: number) {
        const route = runtimeConfig.public.API_URL + this.resource
        return  await useFetchApi(`${route}/${id}`, {
            method: 'delete',
        })
    }
}

// singleton instance
export const UsersService = new UserServiceHttpService()
