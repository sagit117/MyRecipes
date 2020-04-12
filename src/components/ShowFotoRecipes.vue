<template>
	<div class="showFotoRecipes">
		<ShowCategory ref='listCategories' />
		<input type="checkbox"><label>Только диетическое</label>
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