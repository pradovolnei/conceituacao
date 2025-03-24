<template>
  <v-dialog v-model="dialog" max-width="500">
    <v-card title="Desassociar perfis">
      <template v-slot:text>
        <v-col cols="12">
          <v-combobox
              v-model="selectProfiles"
              :items="profilesUser"
              item-title="name"
              item-value="id"
              label="Perfis para desassociar"
              chips
              multiple
          />
        </v-col>
      </template>
      <v-divider />
      <v-card-actions class="bg-surface-light">
        <v-btn text="Cancelar" variant="plain" @click="dialog = false"></v-btn>
        <v-spacer></v-spacer>
        <v-btn class="bg-primary" text="Confimar" :disabled="!selectProfiles.length" @click="formSubmit"></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, shallowRef, computed } from 'vue'
import { UsersService } from '~/services/user.service'

const emit = defineEmits(['formSubmit'])

const dialog = shallowRef(false)
const user = ref(null)
const selectProfiles = ref([])

const profilesUser = computed(() => {
  return user.value.profiles
})

const formSubmit = async () => {
  const { $toast } = useNuxtApp()

  const profilesArrayId = selectProfiles.value.map((profile) => profile.id)
  await UsersService.detachProfiles({ user_id: user.value.id, profiles_id: profilesArrayId })
  dialog.value = false
  $toast.info('Perfils desassociado com sucesso!')
  selectProfiles.value = []
  emit('formSubmit', true)
}


const openDialog = (row) => {
  user.value = row
  dialog.value = true
}

defineExpose({ openDialog })
</script>