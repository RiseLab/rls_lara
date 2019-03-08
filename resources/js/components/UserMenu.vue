<template>
	<v-menu bottom left :close-on-content-click="false" v-model="show">
		<template v-slot:activator="{ on }">
			<span>{{ user.data.email }}</span>
			<v-btn flat icon v-on="on">
				<v-icon>account_circle</v-icon>
			</v-btn>
		</template>
		<v-card width="300">
			<v-card-title class="headline">
				Account
			</v-card-title>
			<v-card-text class="py-0">
				Welcome, {{ user.data.name }}!
			</v-card-text>
			<v-card-actions class="pt-0">
				<v-spacer></v-spacer>
				<v-btn
						color="primary"
						:loading="loading"
						@click="logout">
					Logout
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-menu>
</template>

<script>
    export default {
        name: "UserMenu",

		data () {
            return {
                show: false,
				loading: false,
                username: '',
				password: {
                    show: false,
                    value: ''
                }
			}
		},

		props: {
            user: Object
		},

		methods: {
			logout () {
                this.loading = true;

                axios.post('/api/logout')
                    .then(({data}) => {
                        auth.logout();
                        this.$router.push('/');
                    })
                    .catch(error => {
                        console.log(error);
                    })
					.finally(() => {
					    this.loading = false;
					});
			}
		}
    }
</script>

<style scoped>

</style>
