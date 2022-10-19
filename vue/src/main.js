import { createApp } from 'vue'
import router from './router'
// import { createPinia } from 'pinia'

import './index.css';

import App from './App.vue'

const app = createApp(App)

app.use(router)
// app.use(createPinia())

app.mount('#app')
