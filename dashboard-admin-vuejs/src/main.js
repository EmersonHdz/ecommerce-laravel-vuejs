import { createApp } from 'vue';
import store from './store';
import router from './router';
import './index.css';
import App from './App.vue';
import {currencyUSD} from './filters/currencyUSD.js'
import {currencyGBP} from './filters/currencyUSD.js'
import CKEditor from '@ckeditor/ckeditor5-vue'

const app = createApp(App);

// Configuración de otros plugins
app.use(store);
app
.use(router)
.use(CKEditor)

app.mount('#app');


app.config.globalProperties.$filters = {
    currencyUSD,
    currencyGBP

}

