<template>
  <v-dialog v-model="dialog" max-width="500">
    <v-card title="Associar Perfil">
      <template v-slot:text>
        <v-col cols="12">
          <v-combobox
              v-model="selectProfiles"
              :items="profilesAvailable"
              item-title="name"
              item-value="id"
              label="Perfis para associação"
              chips
              multiple
          />
        </v-col>
        <v-col cols="12">
          <v-combobox
              v-model="selectProfilesUser"
              label="Perfis associado ao Usuário"
              :items="profiles"
              item-title="name"
              item-value="id"
              chips
              multiple
              readonly
          ></v-combobox>
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

const props = defineProps({
  profiles: Array
});

const emit = defineEmits(['formSubmit'])

const dialog = shallowRef(false)
const user = ref(null)
const selectProfiles = ref([])

const profilesAvailable = computed(() => {
  return props.profiles.filter((profile) => !profilesUserIds.value.includes(profile.id) )
})

const profilesUserIds = computed(() => {
  return user.value.profiles.map((profile) => profile.id)
})

const selectProfilesUser = computed(() => {
  return user.value.profiles
})

const formSubmit = async () => {
  const { $toast } = useNuxtApp()

  const profilesArrayId = selectProfiles.value.map((profile) => profile.id)
  await UsersService.attachProfiles({ user_id: user.value.id, profiles_id: profilesArrayId })
  dialog.value = false
  $toast.info('Perfils associado com sucesso!')
  selectProfiles.value = []
  emit('formSubmit', true)
}


const openDialog = (row) => {
  user.value = row
  dialog.value = true
}

defineExpose({ openDialog })
</script>