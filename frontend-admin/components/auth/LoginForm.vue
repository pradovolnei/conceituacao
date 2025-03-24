<script setup lang="ts">
import { ref, reactive } from 'vue'
import { useAuthStore } from '~/store/auth'
const { $toast } = useNuxtApp()

const authStore = useAuthStore()
const formRef = ref()
const form: any = reactive({
  email: '',
  password: '',
})

async function handleFormSubmit() {
  const { valid } = await formRef.value.validate()
  if (valid) {
    const logged = await authStore.authenticate(form)

    if (logged) {
      window.location.href = '/'
      return
    }
  }

  $toast.warning('Erro ao fazer o login!')
}
</script>

<template>
  <v-form ref="formRef">
      <v-row class="d-flex mb-3">
          <v-col cols="12">
              <v-label class="font-weight-bold mb-1">Email *</v-label>
              <v-text-field v-model="form.email" :rules="[v => !!v || 'campo requirido']" variant="outlined"  color="primary" />
          </v-col>
          <v-col cols="12">
              <v-label class="font-weight-bold mb-1">Senha *</v-label>
              <v-text-field v-model="form.password" :rules="[v => !!v || 'campo requirido']" variant="outlined" type="password" color="primary" />
          </v-col>
          <v-col cols="12" class="pt-0">
              <v-btn @click="handleFormSubmit" color="primary" size="large" block   flat>Login</v-btn>
          </v-col>
      </v-row>
    </v-form>
</template>
