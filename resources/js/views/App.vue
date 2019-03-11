<template>
	<v-app>
		<navigation :navigation="navigation" />

		<v-toolbar clipped-left fixed app>
			<v-toolbar-side-icon @click="navigation.show = !navigation.show"></v-toolbar-side-icon>
			<v-toolbar-title class="headline text-uppercase ml-0">
				<span>RL</span><span class="font-weight-light">STORE.MGMT</span>
			</v-toolbar-title>
			<v-spacer></v-spacer>
			<user-menu :user="user" v-if="user.authenticated && user.data" />
		</v-toolbar>

		<v-content>
			<v-container fluid>
				<router-view></router-view>
			</v-container>
		</v-content>

		<login v-if="!(user.authenticated && user.data)" />

		<v-footer inset fixed app>
			<v-spacer></v-spacer>
			<span class="px-4">RiseLab &copy; {{ new Date().getFullYear() }}</span>
		</v-footer>
	</v-app>
</template>

<script>
	import Navigation from "@/js/components/Navigation";
    import UserMenu from "@/js/components/UserMenu";
    import Login from "@/js/components/Login";
	export default {
		components: {Login, UserMenu, Navigation},
		data () {
			return {
				navigation: {
					show: true,
					items: [
						{ title: 'Home', icon: 'store', to: '/' },
						{ title: 'Category', icon: 'category', to: '/category' },
						{ title: 'Product', icon: 'extension', to: '/product' },
						{ title: 'About', icon: 'info', to: '/about' },
					]
				},
				user: {
                    authenticated: auth.check(),
                    data: auth.user
                }
			}
		},

		mounted() {
		    Event.$on('userLoggedIn', () => {
		        this.user = {
                    authenticated: true,
                	data: auth.user
				}
			});
            Event.$on('userLoggedOut', () => {
                this.user = {
                    authenticated: false,
                    data: auth.user
                }
            });
        }
    }
</script>

<style scoped>

</style>
