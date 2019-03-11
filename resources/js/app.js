import './bootstrap';
import Vue from 'vue';
import Vuetify from 'vuetify';
import VueLazyload from 'vue-lazyload';

// Route information for Vue Router
import Routes from '@/js/routes.js';

// Api class
import Api from '@/js/api.js';
window.api = new Api();

// Auth class
import Auth from '@/js/auth.js';
window.auth = new Auth();
window.Event = new Vue();

// Component file
import App from '@/js/views/App';

Vue.use(Vuetify);
Vue.use(VueLazyload);

const app = new Vue({
	el: '#app',
	router: Routes,
	render: h => h(App)
});

export default app;
