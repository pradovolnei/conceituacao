import { UseFetchOptions } from "nuxt/dist/app/composables";
const token = useCookie('token')
import { toast } from 'vue3-toastify'

export const useFetchApi = (url: string, options?: UseFetchOptions<object>) => {
    return useFetch(url, {
        ...options,
        async onRequest({ request, options }) {
            console.log("[fetch request]");

            const headers = new Headers(options.headers);
            headers.set("Authorization", `Bearer ${token.value}`);
            headers.set("Accept", `application/json`);
            options.headers = headers;
        },
        async onRequestError({ request, options, error }) {
            console.log("[fetch request error]");
        },
        async onResponse({ request, response, options }) {
            console.log("[fetch response]");
        },
        async onResponseError({ request, response, options }) {
           if (response.status === 401) {
               token.value = null
               return navigateTo('/auth/login')
           }
            if (response.status === 403) {
                toast.warning(response._data?.message)
            }
        },
    });
};