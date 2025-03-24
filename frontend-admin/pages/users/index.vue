<template>
  <v-row>
    <v-col cols="12" md="12">
      <v-data-table
          :headers="headers"
          :items="dataTable.rows"
          item-key="name"
          hide-default-footer
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>
              <v-icon color="medium-emphasis" icon="mdi-book-multiple" size="x-small" start />
              Listagem de Usuários
            </v-toolbar-title>
            <v-spacer />
            <v-btn
                class="me-2"
                prepend-icon="mdi-plus"
                rounded="lg"
                text="Novo Usuário"
                border
                @click="createUser"
            ></v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.profiles="{ item }">
          <div class="">
            <v-chip>
              {{ item.profiles.length }}
            </v-chip>
             associado(s)
          </div>
        </template>
        <template v-slot:item.actions="{ item }">
          <div class="d-flex gap-2 justify-end">
            <v-btn
                color="primary"
                prepend-icon="mdi-pencil"
                class="text-none"
                size="small"
                variant="flat"
                @click="editUser(item.id)"
            />
            <v-btn
                color="success"
                prepend-icon="mdi-delete"
                class="text-none"
                size="small"
                variant="flat"
                @click="removeUser(item.id)"
            />
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-btn icon="mdi-dots-vertical" variant="flat" size="small" v-bind="props"></v-btn>
              </template>
              <v-list>
                <v-list-item>
                  <v-btn
                      color="primary"
                      prepend-icon="mdi-open-in-new"
                      text="Associar perfis"
                      class="text-none"
                      style="width: 100%"
                      size="small"
                      variant="flat"
                      @click="associateProfileRef.openDialog(item)"
                  />
                </v-list-item>
                <v-list-item>
                  <v-btn
                      color="warning"
                      prepend-icon="mdi-open-in-new"
                      text="Desassociar perfis"
                      class="text-none maxWidth"
                      size="small"
                      variant="flat"
                      @click="detachProfileRef.openDialog(item)"
                  />
                </v-list-item>
              </v-list>
            </v-menu>
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>

  <!--Edit/Create -->
  <v-dialog v-model="dialog" max-width="500">
    <v-card :title="`${isEditing ? 'Editar' : 'Novo' } Usuário`">
      <template v-slot:text>
        <v-form ref="formRef">
          <v-row>
            <v-col cols="12">
              <v-text-field v-model="record.name" label="Nome" :rules="[v => !!v || 'campo requirido']" />
            </v-col>
            <v-col cols="12" md="12">
              <v-text-field v-model="record.email" label="Email" :rules="[v => !!v || 'campo requirido']" />
            </v-col>
            <v-col cols="12" md="12" v-if="!isEditing">
              <v-text-field v-model="record.password" label="Senha" :rules="[v => !!v || 'campo requirido']"/>
            </v-col>
          </v-row>
        </v-form>
      </template>
      <v-divider />
      <v-card-actions class="bg-surface-light">
        <v-btn text="Cancelar" variant="plain" @click="dialog = false"></v-btn>
        <v-spacer></v-spacer>
        <v-btn class="bg-primary" text="Salvar" @click="formSubmit"></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <!--Remove-->
  <v-dialog v-model="dialogRemove" max-width="500">
    <v-card title="Remove usuário">
      <template v-slot:text>
        <span>Deseja remover este usuário</span>
      </template>
      <v-divider />
      <v-card-actions class="bg-surface-light">
        <v-btn text="Cancelar" variant="plain" @click="dialogRemove = false"></v-btn>
        <v-spacer></v-spacer>
        <v-btn class="bg-primary" text="Confirmar" @click="handleRemoveUser"></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <AssociateProfile :profiles="profiles" ref="associateProfileRef" @formSubmit="fetchUsers" />
  <DetachProfile  ref="detachProfileRef" @formSubmit="fetchUsers" />
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import { UsersService } from '~/services/user.service'
import { ProfileService } from '~/services/profiles.service'
import AssociateProfile from '~/components/user/AssociateProfile.vue'
import DetachProfile from '~/components/user/DetachProfile.vue'

definePageMeta({
  middleware: ['authenticated'],
})

const { $toast } = useNuxtApp()

const associateProfileRef = ref(null)
const detachProfileRef = ref(null)
const dialog = shallowRef(false)
const dialogRemove = shallowRef(false)
const isEditing = shallowRef(false)
const formRef = ref()
const record = ref({ name: '', email: '', password: '' })
const dataTable = reactive({
  rows: [],
  total: 0,
})
const profiles = ref([])

onMounted(async () => {
  await fetchUsers()
  await fetchProfiles()
})

function createUser () {
  isEditing.value = false
  record.value = { name: '', email: '', password: '' }
  dialog.value = true
}

const editUser = async (id) => {
  isEditing.value = true

  const user = dataTable.rows.find(item => item.id === id)

  record.value = {
    id: user.id,
    name: user.name,
    email: user.email,
  }
  dialog.value = true
}

const removeUser = async (id) => {
  const user = dataTable.rows.find(item => item.id === id)
  record.value = { id: user.id }
  dialogRemove.value = true
}

const formSubmit = async () => {
  const { valid } = await formRef.value.validate()
  if (!valid) return false

  if (isEditing.value) {
    await UsersService.update(record.value?.id, record.value)
  } else {
    await UsersService.create(record.value)
  }

  await fetchUsers()
  $toast.info('Dados salvo sucesso!')
  record.value = { name: '', email: '', password: '' }
  dialog.value = false
}

const handleRemoveUser = async () => {
  await UsersService.delete(record.value?.id)
  await fetchUsers()
  record.value = { name: '', email: '', password: '' }
  dialogRemove.value = false
  $toast.info('usuário removido com sucesso!')
}

const fetchUsers = async () => {
  const { data } = await UsersService.getAll()
  dataTable.rows = data.value.data
}

const fetchProfiles = async () => {
  const { data } = await ProfileService.getAll()
  profiles.value = data.value.data
}

const headers = [
  { title: 'Usuário', value: 'name' },
  { title: 'Email', value: 'email' },
  { title: 'perfis', value: 'profiles' },
  { title: 'Data Criaçao', value: 'created_at' },
  { title: '', key: 'actions', align: 'end', sortable: false },
]

</script>
