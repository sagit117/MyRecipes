<template>
	<div class="showFotoRecipes">
		<div class="MenuFotoRecipes">
			<div class="menuItem">
				<ShowCategory ref='listCategories' :showAddBtn="true" />
			</div>
			<div class="menuItem">
				<input type="checkbox"><label>Только диетическое</label>
			</div>
		</div>
	</div>
</template>

<script>
	// project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  import ShowCategory from '@/components/ShowCategory.vue'
	
	export default {
		components: {
			ShowCategory,
		}


	}
</script>

<style scoped>
	.showFotoRecipes {
		text-align: left;
	}

	.MenuFotoRecipes {
		display: flex;
	}

	.menuItem {
		display: inline-flex;
	}

@media (min-width: 100px) and (max-width: 320px) {
	.MenuFotoRecipes {
		flex-wrap: wrap;
		justify-content: center;
	}
}
</style>