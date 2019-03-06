<template>
	<div class="elevation-1">
		<v-toolbar flat>
			<v-toolbar-title>Категории</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-dialog v-model="dialog" width="400" persistent>
				<template v-slot:activator="{ on }">
					<v-btn color="primary" v-on="on">
						<v-icon small class="mr-1">add</v-icon>
						Создать
					</v-btn>
				</template>
				<v-card>
					<v-card-title class="headline pb-0">
						{{ dialogTitle }}
					</v-card-title>

					<v-card-text>
						<v-text-field
								label="Наименование"
								:rules="[rules.title]"
								clearable
								v-model="editedItem.title">
						</v-text-field>
					</v-card-text>

					<v-card-actions>
						<v-spacer></v-spacer>
						<v-btn color="primary" flat @click="close">
							Закрыть
						</v-btn>
						<v-btn color="primary" flat @click="save" :disabled="!saveBtn">
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
				:loading="loading"
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
				saveBtn: false,
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
		            title: value => {
                        this.saveBtn = !!value;
		                return value.length >= 2 || 'Обязательно для заполнения. Минимум 2 символа.';
					}
				},
				message: {
		            show: false,
					color: '',
					text: ''
				},
				loading: true
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
		        this.saveBtn = false;
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
		        this.saveBtn = true;
		        this.dialog = true;
			},
			save: function () {
		        let sendMethod = 'post',
					sendUrl = '/api/v1/categories';
		        if (this.editedItem.id > 0) {
		            sendMethod = 'put';
		            sendUrl += '/' + this.editedItem.id;
				}
		        this.saveBtn = false;
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
					    this.saveBtn = true;
					});
            }
		},

		mounted () {
		    axios
				.get('/api/v1/categories')
				.then(response => {
				    this.items = response.data;
				    this.loading = false;
				});
		}
	}
</script>

<style scoped>

</style>
