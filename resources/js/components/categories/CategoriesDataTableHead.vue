<template>
	<thead>
		<tr>
			<th>Title</th>
			<th>Description</th>
			<th></th>
		</tr>
		<tr @keyup.enter="$emit('addRow', title, description)" @keydown="$emit('newInput')">
			<th>
				<input
					type="text"
					name="title"
					v-model.trim="title"
					:disabled="disable_input"
					ref="input"
				></th>
			<th>
				<input
					type="text"
					name="description"
					v-model.trim="description"
					:disabled="disable_input"
				></th>

			<!-- control buttons for adding rows -->
			<th>
				<button
					class="btn btn-success"
					@click="$emit('addRow', title, description)"
					:disabled="disable_input"
				>Add</button>
				<button
					class="btn btn-secondary"
					@click="clear"
					:disabled="disable_input"
				>Clear</button>
			</th>
		</tr>
	</thead>
</template>

<script>
export default {
	name : 'categories-data-table-head',
	props : {
		disable_input : Boolean,
	},
	data() {
		return {
			title       : null,
			description : null,
		}
	},
	methods : {
		clear() {
			this.title       = null;
			this.description = null;
		},
		// Used to clear the inputs, executed by parent component
		clearInputs() {
			this.title       = null;
			this.description = null;
		},
		// Used to focus on input, executed by parent component
		focus() {
			this.$refs.input.focus();
		},
	},
}
</script>

<style>
.table-container thead {
	text-align: center;
}

.table-container thead input {
	width : 100%;
}
</style>