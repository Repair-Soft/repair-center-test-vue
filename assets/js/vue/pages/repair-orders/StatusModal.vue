<template>
  <div class="modal d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5)">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Changer le statut — {{ order.reference }}</h5>
          <button class="btn-close" @click="emit('close')"></button>
        </div>
        <div class="modal-body">
          <select class="form-select" v-model="newStatus">
            <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="emit('close')">Annuler</button>
          <button class="btn btn-primary" @click="submit">Valider</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'
import { STATUS_OPTIONS } from './status'

const props = defineProps<{
  order: { id: number; reference: string; status: string }
}>()

const emit = defineEmits<{
  close: []
  updated: []
}>()

const newStatus = ref(props.order.status)

async function submit(): Promise<void> {
  try {
    await axios.patch(`/api/repair-orders/${props.order.id}/status`, { status: newStatus.value })
    emit('updated')
  } catch (e: any) {
    alert(e.response?.data?.error ?? 'Erreur lors de la mise à jour du statut')
  }
}
</script>
