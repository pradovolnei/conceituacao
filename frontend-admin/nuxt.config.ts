// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,

  devServer: {
    port: 9020
  },

  typescript: {
    shim: false
  },

  runtimeConfig: {
    public: {
      API_URL: process.env.NUXT_API_URL,
    },
  },

  build: {
    transpile: ["vuetify"],
  },

  vite: {
    define: {
      "process.env.DEBUG": false,
    },
  },

  nitro: {
    serveStatic: true,
  },

  modules: ['@pinia/nuxt'],

  pinia: {
    storesDirs: ['./store/**']
  },

  devServerHandlers: [],
  hooks: {  },
  compatibilityDate: "2025-03-23",
})