import { createApp } from 'vue'
import VueMask from '@devindex/vue-mask'
import 'reset-css';
import './style.scss'
import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(router)

app.use(VueMask)

app.mount('#app')
