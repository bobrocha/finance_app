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
				:page_limit="page_limit"
				:page_index="page_index"
				:page_quantity="page_quantity"
				:start_row_index="start_row_index"
				:end_row_index="end_row_index"
				:page_rows="page_rows"
			/>
			<categories-data-table-foot
				:page_quantity="page_quantity"
				:disable_input="disable_input"
				:start_row_index="start_row_index"
				:end_row_index="end_row_index"
				:page_limit="page_limit"
				@switchPage="switchPage"
				@switchPageByNumber="switchPageByNumber"
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
			rows             : [],
			page_limit       : null,
			page_quantity    : null,
			active           : false,
			error_message    : '',
			display_error    : false,
			disable_input    : false,
			editing_id       : null,
			page_index       : 0,
			updated_row      : {
				title        : '',
				description  : '',
			},
			start_row_index  : 0,
			end_row_index    : 0,
			page_rows        : [],
		}
	},
	methods : {
		switchPage(switch_type) {
			this[switch_type]();
		},
		nextPage() {
			this.start_row_index += this.page_limit;
			this.end_row_index    = this.start_row_index + this.page_limit;
			this.page_rows        = this.rows.slice(this.start_row_index, this.end_row_index);
		},
		previousPage() {
			this.start_row_index -= this.page_limit;
			this.end_row_index    = this.start_row_index + this.page_limit;
			this.page_rows        = this.rows.slice(this.start_row_index, this.end_row_index);
		},
		lastPage() {
			this.start_row_index = Math.floor((this.rows.length -1) / this.page_limit) * this.page_limit;
			this.page_rows       = this.rows.slice(this.start_row_index);
		},
		firstPage() {
			this.start_row_index  = 0;
			this.end_row_index    =  this.page_limit;
			this.page_rows        = this.rows.slice(this.start_row_index, this.end_row_index);
		},
		switchPageByNumber(number) {console.log(`NUmber is ${number}`)
			console.log(`Page limit is ${this.page_limit}`);
			console.log(`Page start * limit is ${Number(number) * this.page_limit}`);

			this.start_row_index = Number(number) * this.page_limit;
			this.end_row_index   = this.start_row_index + this.page_limit;
			this.page_rows       = this.rows.slice(this.start_row_index, this.end_row_index);
		},
		setPageQuantity() {
			const page_count   = Math.ceil(this.rows.length / this.page_limit);
			this.page_quantity = page_count;
		},
		getRows() {
			this.page_limit       = 10;
			this.start_row_index  = 0;
			this.end_row_index    = this.page_limit;
			this.pagination_limit = 5;
			this.active           = true;

			this.lockTable();

			this.request('get', `${controller_url}/get`,
			(response) => {
				// this.rows = [...Array(22).keys()].map((el, index) => {
				// 	return {
				// 		id : index + 1,
				// 		title : `title-${index + 1}-${Math.random().toString(36).substring(7)}`,
				// 		description : `description-${index + 1}-${Math.random().toString(36).substring(7)}`,
				// 	}
				// });
				this.rows      = response.data;
				this.page_rows = this.rows.slice(this.start_row_index, this.end_row_index);

				this.setPageQuantity();
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
				this.page_rows.unshift(response.data);
				this.page_rows.pop();
				this.$refs.table_head.clearInputs();

				this.setPageQuantity();
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
				this.setPageQuantity();
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
			this.lockTable();console.log(id);console.log(this.updated_row)

			this.request('post', `${controller_url}/update/${id}/${this.updated_row.title}/${this.updated_row.description}`,
			(response) => {
				this.display_error = false;
				this.error_message = '';

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
			this.editing_id    = null;
			this.display_error = false;
			this.error_message = '';
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