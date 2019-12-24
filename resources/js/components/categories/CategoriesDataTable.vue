<template>
	<section class="table-container">
		<header>
			<h1>Categories</h1>
			<error
				:display_error="display_error"
				:error_message="error_message"
			/>
			<progress-bar
				:active="active"
			/>
		</header>
		<table class="table table-bordered table-sm">
			<categories-data-table-head
				@addRow="addRow"
				@newInput="newInput"
				ref="table_head"
				:disable_input="disable_input"
			/>
			<categories-data-table-body
				@deleteRow="deleteRow"
				@editRow="editRow"
				@cancelEdit="cancelEdit"
				@updateTitle="updateTitle"
				@updateDescription="updateDescription"
				@updateRow="updateRow"
				:disable_input="disable_input"
				:rows="rows"
				:editing_id="editing_id"
			/>
			<categories-data-table-foot
				:page_quantity="page_quantity"
			/>
		</table>
	</section>

</template>

<script>
import CategoriesDataTableHead from './CategoriesDataTableHead.vue';
import CategoriesDataTableBody from './CategoriesDataTableBody.vue';
import CategoriesDataTableFoot from './CategoriesDataTableFoot.vue';
import ProgressBar             from '../PorgressBar.vue';
import Error                   from '../Error.vue';

export default {
	components : {
		CategoriesDataTableHead,
		CategoriesDataTableBody,
		CategoriesDataTableFoot,
		ProgressBar,
		Error,
	},
	name : 'categories-data-table',
	data() {
		return {
			rows            : [],
			active          : false,
			error_message   : '',
			display_error   : false,
			disable_input   : false,
			editing_id      : null,
			updated_row     : {
				title       : '',
				description : '',
			},
			limit         : 10,
			page_quantity : null,
		}
	},
	methods : {
		getRows() {
			this.active = true;
			this.lockTable();

			this.request('get', `${controller_url}/get`,
			(response) => {console.log(response)
				this.rows = response.data;
				this.page_quantity = Math.ceil(this.rows.length / this.limit);
			},
			(error) => {
				console.log(error);
			},
			() => {
				this.unlockTable();
				this.active = false;

				this.$nextTick(() => {
					this.$refs.table_head.focus();
				});
			});
		},
		addRow(title, description) {
			this.display_error = false;

			if(!title || !description) {
				this.display_error = true;
				this.error_message = 'Please enter a Title and Description.';
				return;
			}

			this.active = true;
			this.lockTable();

			this.request('post', `${controller_url}/create/${title}/${description}`,
			(response) => {
				if(response.data.error){
					this.display_error = true;
					this.error_message = response.data.error;

					return;
				}

				this.rows.unshift(response.data);
				this.$refs.table_head.clearInputs();
			},
			(error) => {
				console.log(error);
			},
			() => {
				this.unlockTable();
				this.active = false;

				this.$nextTick(() => {
					this.$refs.table_head.focus();
				});
			});
		},
		deleteRow(row_index, id) {
			this.rows.splice(row_index, 1);

			this.active = true;

			this.lockTable();
			this.request('post', `${controller_url}/destroy/${id}`,
			(response) => {
				console.log(response)
			},
			(error) => {
				console.log(error);
			},
			() => {
				this.active = false;
				this.unlockTable();
			});
		},
		editRow(row_index, id) {
			this.editing_id = id;
		},
		updateRow(row_index, id) {
			if(!this.updated_row.title || !this.updated_row.description) {
				this.display_error = true;
				this.error_message = 'Please enter a Title and Description.';

				return;
			}

			this.active = true;
			this.lockTable();

			this.request('post', `${controller_url}/update/${id}/${this.updated_row.title}/${this.updated_row.description}`,
			(response) => {
				this.display_error = false;

				if(response.data.error) {
					this.display_error = true;
					this.error_message = response.data.error;

					return;
				}

				this.rows[row_index].title       = response.data.title;
				this.rows[row_index].description = response.data.description;
				this.editing_id                  = null;
				this.updated_row                 = {
					title       : '',
					description : '',
				};
			},
			(error) => {
				console.log(error)
			},
			() => {
				this.active = false;
				this.unlockTable();
			});
		},
		cancelEdit() {
			this.editing_id = null;
		},
		lockTable() {
			this.disable_input = true;
		},
		unlockTable() {
			this.disable_input = false;
		},
		newInput() {
			this.display_error = false;
		},
		updateTitle(title) {
			this.updated_row.title = title;
		},
		updateDescription(description) {
			this.updated_row.description = description;
		},
		request(type, url, success, fail, always) {
			axios[type](url)
			.then(response => success(response))
			.catch(error => fail(error))
			.finally(() => always());
		},
	},
	mounted() {
		this.getRows();
	}
}
</script>

<style>
.table-container {
	max-width: 600px;
	margin: 1rem auto;
	box-shadow: 0 0 5px 1px #ccc;
}

.table-container table td {
	text-align: center;
}

.table-container header {
	text-align: center;
	padding: 1rem;
}
</style>