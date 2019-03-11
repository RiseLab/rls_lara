import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/components/Home';
import Category from '@/js/components/Category';
import Product from '@/js/components/Product';
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
			path: '/product',
			name: 'product',
			component: Product
		},
		{
			path: '/about',
			name: 'about',
			component: About
		}
	]
});

router.beforeEach((to, from, next) => {
	if (!auth.check() && to.name !== 'home') {
		next({
			name: 'home'
		});

		return;
	}

	next();
});

export default router;
