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
                color="warning"
                prepend-icon="mdi-open-in-new"
                text="Associar Perfils"
                class="text-none"
                size="small"
                variant="flat"
                @click="associateProfileRef.openDialog(item)"
            />
            <v-btn
                color="success"
                prepend-icon="mdi-delete"
                class="text-none"
                size="small"
                variant="flat"
                @click="removeUser(item.id)"
            />
          </div>
        </template>
      </v-data-table>
    </v-col>
  </v-row>

  <!--Edit/Create -->
  <v-dialog v-model="dialog" max-width="500">
    <v-card :title="`${isEditing ? 'Editar' : 'Novo' } Usuário`">
      <template v-slot:text>
        <v-row>
          <v-col cols="12">
            <v-text-field v-model="record.name" label="Nome"></v-text-field>
          </v-col>
          <v-col cols="12" md="12">
            <v-text-field v-model="record.email" label="Email"></v-text-field>
          </v-col>
          <v-col cols="12" md="12" v-if="!isEditing">
            <v-text-field v-model="record.password" label="Senha"></v-text-field>
          </v-col>
        </v-row>
      </template>
      <v-divider />
      <v-card-actions class="bg-surface-light">
        <v-btn text="Cancelar" variant="plain" @click="dialog = false"></v-btn>
        <v-spacer></v-spacer>
        <v-btn class="bg-primary" text="Savar" @click="formSubmit"></v-btn>
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
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import { UsersService } from '~/services/user.service'
import { ProfileService } from '~/services/profiles.service'
import AssociateProfile from '~/components/user/AssociateProfile.vue'

definePageMeta({
  middleware: ['authenticated'],
})

const associateProfileRef = ref(null)
const dialog = shallowRef(false)
const dialogRemove = shallowRef(false)
const isEditing = shallowRef(false)
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
  if (isEditing.value) {
    await UsersService.update(record.value?.id, record.value)
  } else {
    await UsersService.create(record.value)
  }

  await fetchUsers()
  record.value = { name: '', email: '', password: '' }
  dialog.value = false
}

const handleRemoveUser = async () => {
  await UsersService.delete(record.value?.id)
  await fetchUsers()
  record.value = { name: '', email: '', password: '' }
  dialogRemove.value = false
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
  { title: 'Data Criaçao', value: 'created_at' },
  { title: '', key: 'actions', align: 'end', sortable: false },
]

</script>
