const runtimeConfig = useRuntimeConfig()


export interface IUserAttachProfilePayload {
    profiles_id: number[]
    user_id: number
}

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

    async attachProfiles(payload: IUserAttachProfilePayload) {
        const route = runtimeConfig.public.API_URL + '/profiles/attach'
        return  await useFetchApi(route, {
            method: 'post',
            body: {
                ...payload
            },
        })
    }

    async detachProfiles(payload: IUserAttachProfilePayload) {
        const route = runtimeConfig.public.API_URL + '/profiles/attach'
        return  await useFetchApi(route, {
            method: 'post',
            body: {
                ...payload
            },
        })
    }

}

// singleton instance
export const UsersService = new UserServiceHttpService()
