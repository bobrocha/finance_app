<template>
	<tbody>
		<tr v-for="(row, row_index) in rows" :key="row.id">
			<!-- input and data -->
			<td v-if="editing_id === row.id" class="input-td">
				<input
					type="text"
					v-model.trim="title"
					name="title"
				>
			</td>
			<td v-else class="added-item">{{ row.title }}</td>

			<td v-if="editing_id === row.id" class="input-td">
				<input
					type="text"
					v-model.trim="description"
					name="description"
				>
			</td>
			<td v-else class="added-item">{{ row.description }}</td>

			<!-- controls -->
			<td v-if="editing_id === row.id">
				<button
					type="button"
					class="btn btn-success"
					@click="$emit('updateRow', row_index, row.id)"
					:disabled="disable_input"
				>Save</button>
				<button
					type="button"
					class="btn btn-danger"
					@click="$emit('cancelEdit')"
					:disabled="disable_input"
				>Cancel</button>
			</td>
			<td v-else>
				<button
					type="button"
					class="btn btn-danger"
					@click="$emit('deleteRow', row_index, row.id)"
					:disabled="disable_input"
				>Delete</button>
				<button
					type="button"
					class="btn btn-secondary"
					@click="$emit('editRow', row_index, row.id); setRecordIndex(row_index)"
					:disabled="disable_input"
				>Edit</button>
			</td>
		</tr>
	</tbody>
</template>

<script>
export default {
	name : 'categories-data-table-body',
	props : {
		rows          : Array,
		disable_input : Boolean,
		editing_id    : Number,
	},
	data() {
		return {
			title        : '',
			description  : '',
			record_index : null,
		}
	},
	methods : {
		setRecordIndex(index) {
			this.record_index = index;
		}
	},
	watch : {
		editing_id(new_id, old_id) {
			/**
			 * Using null strict comparison in case id is actually 0.
			 * If new_val is null clear out values, this means user clicked
			 * on the cancel button
			 */
			if(new_id === null) {
				this.title       = '';
				this.description = '';
			}
			/**
			 * If the new id and old id are not
			 * the same this means the user first
			 * clicked on the edit button of one row
			 * but then clicked on the edit button of
			 * a different row deactivating the edit
			 * of the orginal row. This mainly sets the default
			 * value of the input element so a user can see
			 * what he is updating
			 */
			else if(new_id !== old_id) {
				this.title       = this.rows[this.record_index].title;
				this.description = this.rows[this.record_index].description;
			}
		},
		/**
		 * When a user updates the title emit
		 * an input event and send its value up
		 * to the parent component
		 */
		title(val) {
			this.$emit('updateTitle', val);
		},
		/**
		 * When a user updates the description emit
		 * an input event and send its value up
		 * to the parent component
		 */
		description(val) {
			this.$emit('updateDescription', val);
		},
	}
}
</script>

<style>
.table-container .added-item,
.table-container .input-td {
	vertical-align: middle;
}

.input-td input {
	width: 100%;
}
</style>