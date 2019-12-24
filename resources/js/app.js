require('./bootstrap');

window.Vue = require('vue');


import CategoriesDataTable from './components/categories/CategoriesDataTable.vue';

const app = new Vue({
	el: '#app',
	components : {
		CategoriesDataTable,
	},
});
