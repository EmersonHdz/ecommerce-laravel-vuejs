import { createApp } from 'vue';
import store from './store';
import router from './router';
import './index.css';
import App from './App.vue';
import { currencyUSD, currencyGBP } from './filters/currencyS';
import CKEditor from '@ckeditor/ckeditor5-vue'
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const app = createApp(App);

// Configuración de otros plugins
app.use(store);
app
.use(router)
.use(CKEditor)

app.mount('#app');
window.Pusher = Pusher;


app.config.globalProperties.$filters = {
    currencyUSD,
    currencyGBP

}


window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'cc652d381cf2f6774164',
  cluster: 'eu',
  forceTLS: true
});

