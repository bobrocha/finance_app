<template>
	<tfoot>
		<tr>
			<td colspan="3" class="pagination-buttons">
				<nav aria-label="Cateogry resutls">
					<ul class="pagination justify-content-center">
						<li
							v-show="many_links" v-for="(link, index) in links"
							:key="index"
							class="page-item"
						><a class="page-link" href="#">{{ index }}</a></li>
						<!-- <li class="page-item"><a class="page-link" href="#">Previous</a></li>
						<li class="page-item"><a class="page-link" href="#">1</a></li>
						<li class="page-item"><a class="page-link" href="#">2</a></li>
						<li class="page-item"><a class="page-link" href="#">3</a></li>
						<li class="page-item"><a class="page-link" href="#">Next</a></li> -->
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
		page_quantity : Number,
	},
	data() {
		return {
		}
	},
	computed : {
		links() {
			return [...Array(this.page_quantity).keys()];
		},
		many_links() {
			return this.page_quantity > 0;
		}
	}
}
</script>

<style>
.table-container .pagination {
	padding-top: 1rem;
}
</style>