<template>
  <div class="modal d-block" tabindex="-1" style="background: rgba(0, 0, 0, 0.5)">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Nouvel ordre de réparation</h5>
          <button class="btn-close" @click="emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Description *</label>
            <textarea class="form-control" rows="3" v-model="form.description"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Statut initial</label>
            <select class="form-select" v-model="form.status">
              <option v-for="opt in STATUS_OPTIONS" :key="opt.value" :value="opt.value">
                {{ opt.label }}
              </option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Client</label>
            <div class="row g-2">
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Nom *" v-model="form.customer.name" />
              </div>
              <div class="col-md-4">
                <input type="email" class="form-control" placeholder="Email" v-model="form.customer.email" />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Téléphone" v-model="form.customer.phone" />
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="emit('close')">Annuler</button>
          <button class="btn btn-primary" @click="submit" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
            Créer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import axios from 'axios'
import { STATUS_OPTIONS } from './status'

const emit = defineEmits<{
  close: []
  created: []
}>()

const loading = ref(false)
const form = reactive({
  description: '',
  status: 'PENDING',
  customer: { name: '', email: '', phone: '' },
})

async function submit(): Promise<void> {
  if (!form.description) {
    alert('La description est obligatoire')
    return
  }
  loading.value = true
  try {
    const payload: Record<string, unknown> = {
      description: form.description,
      status: form.status,
    }
    if (form.customer.name) {
      payload.customer = {
        name: form.customer.name,
        email: form.customer.email || null,
        phone: form.customer.phone || null,
      }
    }
    await axios.post('/api/repair-orders', payload)
    emit('created')
  } catch (e: any) {
    alert(e.response?.data?.error ?? 'Erreur lors de la création')
  } finally {
    loading.value = false
  }
}
</script>
