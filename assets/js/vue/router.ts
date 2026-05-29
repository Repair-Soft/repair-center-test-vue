import { createRouter, createWebHistory } from 'vue-router'
import RepairOrderList from './pages/repair-orders/RepairOrderList.vue'
import RepairOrderDetailView from './pages/repair-orders/RepairOrderDetailView.vue'

export const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'repair-orders', component: RepairOrderList },
    {
      path: '/repair-orders/:id',
      name: 'repair-order',
      component: RepairOrderDetailView,
      props: true,
    },
  ],
})
