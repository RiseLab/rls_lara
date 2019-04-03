<template>
	<div class="elevation-1">
		<v-toolbar flat>
			<v-toolbar-title>Товары</v-toolbar-title>
			<v-spacer></v-spacer>
			<v-dialog v-model="dialog" width="600" persistent content-class="overflow-y-visible">
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
							<v-text-field
									label="Источник"
									:rules="[]"
									v-model="editedItem.sourceLink">
							</v-text-field>
							<v-textarea
									label="Краткое описание"
									auto-grow
									rows="2"
									v-model="editedItem.info">
							</v-textarea>
							<v-textarea
									label="Полное описание"
									auto-grow
									rows="2"
									v-model="editedItem.description">
							</v-textarea>
							<v-layout class="pb-1">
								<v-flex class="mr-2">
									<v-text-field
											label="Цена"
											type="number"
											min="0"
											v-model="editedItem.price">
									</v-text-field>
								</v-flex>
								<v-flex class="mx-2">
									<v-text-field
											label="Старая цена"
											type="number"
											min="0"
											v-model="editedItem.priceOld">
									</v-text-field>
								</v-flex>
								<v-flex class="ml-2">
									<v-text-field
											label="Остаток"
											type="number"
											min="0"
											v-model="editedItem.stock">
									</v-text-field>
								</v-flex>
							</v-layout>
							<vue-upload-multiple-image
									@upload-success="uploadImageSuccess"
									@before-remove="beforeRemove"
									@edit-image="editImage"
									@data-change="dataChange"
									drag-text="Drag pictures (multiple)"
									browse-text="(or) Select"
									primary-text="Default"
									mark-is-primary-text="Set as default"
									popup-text="This image will be displayed as default"
									drop-text="Drop your files here ..."
									:data-images="photos">
							</vue-upload-multiple-image>
							{{ photos }}
							<v-checkbox
									label="Опубликован"
									color="primary"
									v-model="editedItem.available">
							</v-checkbox>
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
	import VueUploadMultipleImage from 'vue-upload-multiple-image';

    export default {
	    components: { VueUploadMultipleImage },

        name: "Product",

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
                    { text: 'Товар', value: 'title' },
                    { text: 'Ссылка', value: 'alias' },
                    { text: 'Действия', sortable: false }
                ],
                items: [],
				photos: [],
                editedItem: {
                    id: 0,
                    title: '',
					sourceLink: '',
                    info: '',
					description: '',
					price: 0,
					priceOld: 0,
					stock: 0,
					available: false,
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
                return this.editedItem.id === 0 ? 'Новый товар' : 'Редактирование товара';
            }
        },

        methods: {
            close: function () {
                this.editedItem = {
                    id: 0,
                    title: '',
                    sourceLink: '',
                    info: '',
                    description: '',
                    price: 0,
                    priceOld: 0,
                    stock: 0,
                    available: false,
                    alias: ''
                };
                this.editedIndex = -1;
                //this.$refs.form.reset();
                this.dialog = false;
            },
            del: function (item) {
                let index = this.items.indexOf(item);
                if (confirm(`Вы уверены, что хотите удалить товар '${item.title}'?`)) {
                    axios
                        .delete('/api/v1/products/' + item.id)
                        .then(response => {
                            this.items.splice(index, 1);
                            this.message = {
                                show: true,
                                color: 'success',
                                text: 'Товар успешно удален.'
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
                    sendUrl = '/api/v1/products';
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
                            text: 'Товар успешно сохранен.'
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
            },
            uploadImageSuccess(formData, index, fileList) {
            	formData.append('path', 'temp');
                axios.post('/api/v1/uploads', formData)
					.then(response => {
						fileList[index].path = `/storage/temp/${response.data}`;
						this.photos = fileList;
					});
            },
            beforeRemove (index, done, fileList) {
            	if (confirm('Remove image?')) {
	                axios
						.delete('/api/v1/uploads', {
							data: {
								path: fileList[index].path.replace('/storage/', '')
							}
						})
						.then(response => {
							done();
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
            editImage (formData, index, fileList) {
                console.log('edit data', formData, index, fileList);
            },
            dataChange (data) {
                console.log(data);
            }
        },

        mounted () {
            axios
                .get('/api/v1/products')
                .then(response => {
                    this.items = response.data;
                    this.loading.table = false;
                });
        }
    }
</script>

<style scoped>

</style>
