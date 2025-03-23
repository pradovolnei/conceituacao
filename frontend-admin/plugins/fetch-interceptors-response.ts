import { ofetch } from "ofetch"

export default defineNuxtPlugin((_nuxtApp) => {
    globalThis.c = ofetch.create({
        onRequest({ request, options }) {
            if (typeof localStorage !== "undefined") {
                options.headers = { Authorization: `Bearer 13213` };
            }
        },
    })
})
