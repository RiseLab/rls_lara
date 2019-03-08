<template>
	<v-dialog v-model="show" persistent width="300">
		<v-card>
			<v-card-title
					class="headline grey lighten-2"
					primary-title>
				Authorization
			</v-card-title>

			<v-card-text>
				<v-form>
					<v-text-field label="E-mail" append-icon="person" v-model="username"></v-text-field>
					<v-text-field
							label="Password"
							:append-icon="password.show ? 'visibility' : 'visibility_off'"
							:type="password.show ? 'text' : 'password'"
							v-model="password.value"
							@click:append="password.show = !password.show">
					</v-text-field>
				</v-form>
			</v-card-text>

			<v-divider></v-divider>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn flat color="primary" :loading="loading" @click="login">
					Login
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
    export default {
        name: "Login",

		data () {
            return {
                show: true,
                loading: false,
                username: '',
                password: {
                    show: false,
                    value: ''
                }
			}
		},
        methods: {
            login() {
                let data = {
                    username: this.username,
                    password: this.password.value
                };

                this.loading = true;

                axios.post('/api/login', data)
                    .then(({data}) => {
                        auth.login(data.token, data.user);
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
