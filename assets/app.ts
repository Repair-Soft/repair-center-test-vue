import './styles/app.scss'
import { createApp } from 'vue'
import App from './js/vue/App.vue'
import { router } from './js/vue/router'

createApp(App).use(router).mount('#app')
