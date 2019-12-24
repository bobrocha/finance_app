<template>
	<div v-show="active">
		<div class="progress fade-in">
			<div
				class="progress-bar progress-bar-striped progress-bar-animated"
				role="progressbar"
				style="width: 100%"
			></div>
		</div>
	</div>
</template>

<script>
export default {
	name  : 'progress-bar',
	props : {
		active : {
			type    : Boolean,
			default : false,
		},
	}
}
</script>

<style>
@keyframes fadeIn {
	0% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

.fade-in {
	opacity: 1;
	animation-name: fadeIn;
	animation-iteration-count: 1;
	animation-timing-function: ease-in;
	animation-duration: 0.5s;
}
</style>