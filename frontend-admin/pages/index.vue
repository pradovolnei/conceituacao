<script setup lang="ts">
import UiParentCard from '@/components/shared/UiParentCard.vue';
definePageMeta({
  middleware: ['authenticated'],
})
</script>
<template>
    <v-row>
        <v-col cols="12" md="12">
            <UiParentCard title="Dashboard">
                <div class="pa-7 pt-1"><p class="text-body-1">Página dashboard</p></div>
            </UiParentCard>
        </v-col>
    </v-row>
</template>
