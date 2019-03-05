<template>
	<v-app>
		<navigation :navigation="navigation" />

		<v-toolbar clipped-left fixed app>
			<v-toolbar-side-icon @click="navigation.show = !navigation.show"></v-toolbar-side-icon>
			<v-toolbar-title class="headline text-uppercase ml-0">
				<span>RL</span><span class="font-weight-light">STORE.MGMT</span>
			</v-toolbar-title>
		</v-toolbar>

		<v-content>
			<v-container fluid>
				<router-view></router-view>
			</v-container>
		</v-content>

		<v-footer inset fixed app>
			<v-spacer></v-spacer>
			<span class="px-4">RiseLab &copy; {{ new Date().getFullYear() }}</span>
		</v-footer>
	</v-app>
</template>

<script>
	import Navigation from "@/js/components/Navigation";
	export default {
		components: {Navigation},
		data () {
			return {
				navigation: {
					show: true,
					items: [
						{ title: 'Home', icon: 'store', to: '/' },
						{ title: 'Category', icon: 'category', to: '/category' },
						{ title: 'About', icon: 'info', to: '/about' },
					]
				}
			}
		}
	}
</script>

<style scoped>

</style>