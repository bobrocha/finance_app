<template>
	<tfoot>
		<tr>
			<td colspan="3" class="pagination-buttons">
				<nav aria-label="Cateogry resutls">
					<ul class="pagination justify-content-center pagination-sm" v-show="pagesExist">
						<li class="page-item">
							<button
								class="page-link"
								@click="firstPage"
							>First</button>
						</li>
						<li class="page-item">
							<button
								class="page-link"
								:disabled="isPreviousBtnDisabled"
								@click="previousPage"
							>Previous</button>
						</li>
						<li class="page-item pagination-range" @keyup.enter="$emit('switchPageByNumber', current_page_index - 1)">
							<input
								type="number"
								class="page-link pagination-input"
								v-model.trim="current_page_index"
								:max="page_quantity"
								min="1"
								:disabled="disable_input"
							> / <span> {{ Number(page_quantity).toLocaleString() }} </span>
						</li>
						<li class="page-item">
							<button
								class="page-link"
								:disabled="disable_input"
								@click="$emit('switchPageByNumber', current_page_index - 1)"
							>Enter</button>
						</li>
						<li class="page-item">
							<button
								class="page-link"
								:disabled="isNextBtnDisabled"
								@click="nextPage"
							>Next</button>
						</li>
						<li class="page-item">
							<button
								class="page-link"
								@click="lastPage"
							>Last</button>
						</li>
					</ul>
				</nav>
			</td>
		</tr>
	</tfoot>
</template>

<script>
export default {
	name  : 'categories-data-table-foot',
	props : {
		page_quantity    : Number,
		pagination_limit : Number,
		disable_input    : Boolean,
		start_row_index  : Number,
		end_row_index    : Number,
		page_limit       : Number,
	},
	data() {
		return {
			current_page_index : 1,
		}
	},
	methods: {
		nextPage() {
			this.current_page_index++;
			this.$emit('switchPage', 'nextPage');
		},
		previousPage() {
			this.current_page_index--;
			this.$emit('switchPage', 'previousPage');
		},
		lastPage() {
			this.current_page_index = this.page_quantity;
			this.$emit('switchPage', 'lastPage');
			//this.$emit('switchPage', this.page_quantity - 1);
		},
		firstPage() {
			this.current_page_index = 1;
			this.$emit('switchPage', 'firstPage');
			//this.$emit('switchPage', 0);
		},
	},
	computed : {
		/**
		 * Determines if more than one page
		 * exist so that pagination is initiated.
		 */
		pagesExist() {
			return this.page_quantity > 1;
		},
		/**
		 * Disable using passed down property of if user
		 * has reached the last page.
		 */
		isNextBtnDisabled() {
			return this.disable_input || (this.page_quantity <= this.current_page_index);
		},
		/**
		 * Disable using passed down property of if user
		 * has reached the first page.
		 */
		isPreviousBtnDisabled() {
			return this.disable_input || (this.current_page_index === 1);
		}
	},
}
</script>

<style>
.table-container .pagination {
	padding-top: 1rem;
}

.table-container .pagination-input {
	display: inline-block;
	margin : 0 0.5rem;
}

.table-container .pagination-range {
	margin: 0 1rem;
}
</style>