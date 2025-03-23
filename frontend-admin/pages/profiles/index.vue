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
              Listagem de Perfil
            </v-toolbar-title>
            <v-spacer />
            <v-btn
                class="me-2"
                prepend-icon="mdi-plus"
                rounded="lg"
                text="Novo Perfíl"
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
    <v-card :title="`${isEditing ? 'Editar' : 'Novo' } Perfíl`">
      <template v-slot:text>
        <v-row>
          <v-col cols="12">
            <v-text-field v-model="record.name" label="Nome"></v-text-field>
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
        <span>Deseja remover este perfíl</span>
      </template>
      <v-divider />
      <v-card-actions class="bg-surface-light">
        <v-btn text="Cancelar" variant="plain" @click="dialogRemove = false"></v-btn>
        <v-spacer></v-spacer>
        <v-btn class="bg-primary" text="Confirmar" @click="handleRemoveUser"></v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue'
import { ProfileService } from '~/services/profiles.service'
import { toast } from 'vue3-toastify'
definePageMeta({
  middleware: ['authenticated'],
})

const dialog = shallowRef(false)
const dialogRemove = shallowRef(false)
const isEditing = shallowRef(false)
const record = ref({ name: '' })
const dataTable = reactive({
  rows: [],
  total: 0,
})

onMounted(async () => {
  await fetchProfiles()
})

function createUser () {
  record.value = { name: '' }
  dialog.value = true
}

const editUser = async (id) => {
  isEditing.value = true

  const user = dataTable.rows.find(item => item.id === id)

  record.value = {
    id: user.id,
    name: user.name,
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
    await ProfileService.update(record.value?.id, record.value)
  } else {
    await ProfileService.create(record.value)
  }

  await fetchProfiles()
  record.value ={ name: '' }
  dialog.value = false
}

const handleRemoveUser = async () => {
  try {
    await ProfileService.delete(record.value?.id)
    await fetchProfiles()
    record.value = { name: '' }
    dialogRemove.value = false
  } catch (e) {
    console.log(e.toString())
  }
}

const fetchProfiles = async () => {
  const { data } = await ProfileService.getAll()
  dataTable.rows = data.value.data
}

const headers = [
  { title: 'Perfíl', value: 'name' },
  { title: 'Data Criaçao', value: 'created_at' },
  { title: '', key: 'actions', align: 'end', sortable: false },
]

</script>
