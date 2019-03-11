<template>
	<div class="elevation-1">
		<v-toolbar flat>
			<v-toolbar-title>Категории</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-dialog v-model="dialog" width="400" persistent>
				<template v-slot:activator="{ on }">
					<v-btn color="primary" v-on="on">
						<v-icon small class="mr-1">add</v-icon>
						Добавить
					</v-btn>
				</template>
				<v-card>
					<v-card-title class="headline pb-0">
						{{ dialogTitle }}
					</v-card-title>

					<v-card-text>
						<v-form ref="form" v-model="valid" @submit.prevent>
							<v-text-field
									label="Наименование"
									:counter="40"
									:rules="[rules.required,rules.minLength,rules.maxLength]"
									v-model="editedItem.title">
							</v-text-field>
						</v-form>
					</v-card-text>

					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="primary" flat @click="close" :disabled="loading.form">
							Закрыть
						</v-btn>
						<v-btn color="primary" flat @click="save" :disabled="!valid" :loading="loading.form">
							Сохранить
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-dialog>
		</v-toolbar>
		<v-data-table
				v-model="selected"
				:headers="headers"
				:items="items"
				:loading="loading.table"
				:rows-per-page-items="[10,25,{'text':'$vuetify.dataIterator.rowsPerPageAll','value':-1}]"
				item-key="id"
				select-all>
			<v-progress-linear v-slot:progress color="blue" indeterminate></v-progress-linear>
			<template v-slot:items="props">
				<td>
					<v-checkbox
							v-model="props.selected"
							color="primary"
							hide-details>
					</v-checkbox>
				</td>
				<td>{{ props.item.id }}</td>
				<td>{{ props.item.title }}</td>
				<td>{{ props.item.alias }}</td>
				<td>
					<v-icon small class="mr-2" @click="edit(props.item)">edit</v-icon>
					<v-icon small @click="del(props.item)">delete</v-icon>
				</td>
			</template>
		</v-data-table>
		<v-snackbar v-model="message.show" top :color="message.color" :timeout="5000">
			{{ message.text }}
			<v-btn flat icon @click="message.show = false">
				<v-icon>close</v-icon>
			</v-btn>
		</v-snackbar>
	</div>
</template>

<script>
	export default {
		name: "Category",

		data () {
		    return {
		        dialog: false,
				valid: false,
				loading: {
		            table: true,
					form: false
				},
		        selected: [],
		        headers: [
					{ text: 'id', value: 'id' },
					{ text: 'Категория', value: 'title' },
					{ text: 'Ссылка', value: 'alias' },
					{ text: 'Действия', sortable: false }
				],
				items: [],
				editedItem: {
		            id: 0,
					title: '',
					alias: ''
				},
				editedIndex: -1,
				rules: {
		            required: v => !!v || 'Required field.',
					minLength: v => v.length >= 2 || 'Length must be 2 chars at least.',
					maxLength: v => v.length <= 40 || 'Length must be less than 30 chars.'
				},
				message: {
		            show: false,
					color: '',
					text: ''
				}
			}
		},

		computed: {
		    dialogTitle () {
		        return this.editedItem.id === 0 ? 'Новая категория' : 'Редактирование категории';
			}
		},

		methods: {
		    close: function () {
		        this.editedItem = {
                    id: 0,
                    title: '',
                    alias: ''
				};
		        this.editedIndex = -1;
		        this.$refs.form.reset();
				this.dialog = false;
			},
			del: function (item) {
		        let index = this.items.indexOf(item);
		        if (confirm(`Вы уверены, что хотите удалить категорию '${item.title}'?`)) {
                    axios
                        .delete('/api/v1/categories/' + item.id)
                        .then(response => {
                            this.items.splice(index, 1);
                            this.message = {
                                show: true,
                                color: 'success',
                                text: 'Категория успешно удалена.'
                            };
                        })
                        .catch(error => {
                            this.message = {
                                show: true,
                                color: 'error',
                                text: error.response.data.message
                            };
                        });
				}
			},
			edit: function (item) {
		        this.editedIndex = this.items.indexOf(item);
		        this.editedItem = Object.assign({}, item);
		        this.dialog = true;
			},
			save: function () {
		        let sendMethod = 'post',
					sendUrl = '/api/v1/categories';
		        if (this.editedItem.id > 0) {
		            sendMethod = 'put';
		            sendUrl += '/' + this.editedItem.id;
				}
		        this.loading.form = true;
		        axios({
                    method: sendMethod,
                    url: sendUrl,
                    data: {title: this.editedItem.title}
                })
					.then(response => {
                        if (this.editedIndex >= 0) {
                            Object.assign(this.items[this.editedIndex], response.data);
                        } else {
                            this.items.push(response.data);
                        }
					    this.message = {
					        show: true,
							color: 'success',
							text: 'Категория успешно сохранена.'
						};
					    this.close();
					})
					.catch(error => {
                        this.message = {
                            show: true,
							color: 'error',
                            text: error.response.data.message
                        };
					})
					.finally(() => {
					    this.loading.form = false;
					});
            }
		},

		mounted () {
		    axios
				.get('/api/v1/categories')
				.then(response => {
				    this.items = response.data;
				    this.loading.table = false;
				});
		}
	}
</script>

<style scoped>

</style>
