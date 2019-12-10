<template>
	<div v-show="display_error">
		<div class="alert alert-danger" role="alert">{{ error_message }}</div>
	</div>
</template>

<script>
export default {
	name : 'error',
	props : {
		display_error : {
			type    : Boolean,
			default : false,
		},
		error_message : {
			type    : String,
			default : '',
		},
	}
}
</script>

<style>

</style>