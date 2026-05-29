<template>
  <div>
    <router-link to="/" class="btn btn-link px-0 mb-3 text-decoration-none">
      ← Retour à la liste
    </router-link>

    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border" role="status"></div>
    </div>

    <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

    <div v-else-if="order" class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ order.reference }}</h5>
        <span :class="'status-badge status-' + order.status">{{ statusLabel(order.status) }}</span>
      </div>
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-md-6"><strong>Client :</strong> {{ order.customer?.name ?? '—' }}</div>
          <div class="col-md-6"><strong>Total :</strong> {{ order.totalAmount.toFixed(2) }} €</div>
        </div>
        <p class="mb-3"><strong>Description :</strong> {{ order.description ?? '—' }}</p>

        <hr />

        <p class="text-muted mb-0">Le devis de cet ordre est à construire.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { statusLabel } from './status'

const props = defineProps<{ id: string }>()

interface RepairOrder {
  id: number
  reference: string
  status: string
  totalAmount: number
  description: string | null
  customer: { name: string; email: string | null; phone?: string | null } | null
}

const order = ref<RepairOrder | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)

async function load(): Promise<void> {
  loading.value = true
  error.value = null
  try {
    const { data } = await axios.get<RepairOrder>(`/api/repair-orders/${props.id}`)
    order.value = data
  } catch {
    error.value = 'Ordre de réparation introuvable'
  } finally {
    loading.value = false
  }
}

onMounted(load)
</script>
