import './bootstrap';

import { createApp } from 'vue';
import toastr from '@meforma/vue-toaster';
import Tasks from './tasks.vue';


// // Инициализация Vue 
const app = createApp(Tasks);


app.use(toastr);


app.mount('#app');
