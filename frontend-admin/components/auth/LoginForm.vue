<script setup lang="ts">
import { useAuthStore } from '~/store/auth'

const authStore = useAuthStore()
const form: any = reactive({
  email: '',
  password: '',
})

const rules = [
  value => {
    if (value) return true
    return 'campo requirido'
  },
]

async function handleFormSubmit() {
  const logged = await authStore.authenticate(form)
  if (logged) {
    navigateTo('/')
    return
  }


}
</script>

<template>
  <v-form @submit.prevent="handleFormSubmit">
      <v-row class="d-flex mb-3">
          <v-col cols="12">
              <v-label class="font-weight-bold mb-1">Email *</v-label>
              <v-text-field v-model="form.email" :rules="rules" variant="outlined"  color="primary"></v-text-field>
          </v-col>
          <v-col cols="12">
              <v-label class="font-weight-bold mb-1">Senha *</v-label>
              <v-text-field v-model="form.password" :rules="rules" variant="outlined" type="password" color="primary"></v-text-field>
          </v-col>
          <v-col cols="12" class="pt-0">
              <v-btn type="submit"  color="primary" size="large" block   flat>Login</v-btn>
          </v-col>
      </v-row>
    </v-form>
</template>
