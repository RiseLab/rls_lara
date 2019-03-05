import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/components/Home';
import Category from '@/js/components/Category';
import About from '@/js/components/About';

Vue.use(VueRouter);

const router = new VueRouter({
	mode: 'history',
	routes: [
		{
			path: '/',
			name: 'home',
			component: Home
		},
		{
			path: '/category',
			name: 'category',
			component: Category
		},
		{
			path: '/about',
			name: 'about',
			component: About
		}
	]
});

export default router;
