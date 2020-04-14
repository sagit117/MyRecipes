<template>
	<div class="showFotoRecipes">

		<template v-if="state === 1">
			<div class="btnAdd">
				<button title="Загрузить новый рецепт" @click="() => { this.state = 2 }">Загрузить новый рецепт</button>
			</div>
			<hr>

			<div class="MenuFotoRecipes">
				<div class="menuItem">
					<ShowCategory ref='listCategories' @change-group="changeGroup" />
				</div>
				<div class="menuItem">
					<label><input type="checkbox" v-model="diet" @change="clickDiet">Только диетическое</label>
				</div>
				<div 	class="menuItem" 
							v-if="typeShowList === 2"
							@click="setTypeShowList(1)">
					<button>Показать в виде строк</button>
				</div>
				<div 	class="menuItem" 
							v-else
							@click="setTypeShowList(2)">
					<button>Показать в виде кубов</button>
				</div>
			</div>

			<div class="listFotoRecipes" v-if="typeShowList === 1">
				<div class="itemRecipe" v-for="(recipe, index) in this.$store.getters.getDataFotoRecipes" :key="recipe.id">
					<a href="#" @click.prevent="setShowRow(index)">{{ recipe.name }}</a>
					<ItemFotoRecipe 
						:recipe="recipe" 
						v-if="showRow.indexOf(index) !== -1" 
						@edit="edit" />
				</div>
			</div>
			<div class="listFotoRecipes" v-else>
				<ItemFotoRecipe 
					v-for="recipe in this.$store.getters.getDataFotoRecipes" 
					:key="recipe.id" 
					:recipe="recipe" 
					@edit="edit" />
			</div>
		</template>

		<LoadFotoRecipes v-if="state === 2" @close="state = 1"/>
		<EditFotoRecipe v-if="state === 3" :id_fotorecipes="edit_id" @close="closeEdit"/>

	</div>
</template>

<script>
	// project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  import ShowCategory from '@/components/ShowCategory.vue'
  import ItemFotoRecipe from '@/components/ItemFotoRecipe.vue'
  import LoadFotoRecipes from '@/components/LoadFotoRecipes.vue'
  import EditFotoRecipe from '@/components/EditFotoRecipe.vue'
	
	export default {
		components: {
			ShowCategory,
			ItemFotoRecipe,
			LoadFotoRecipes,
			EditFotoRecipe,
		},

		data() {
			return {
				diet: false,
				typeShowList: 2,
				page: 1,
				showRow: [],
				state: 1,
				edit_id: 0,
			}
		},

		mounted() {
			this.loadFotorecipes(0);
			this.typeShowList = (!this.getCookie('typeShowList')) ? 2 : parseInt(this.getCookie('typeShowList'));
		},

		methods: {
			changeGroup(id) {
				this.loadFotorecipes(id);
			},
			setTypeShowList(val) {
				this.typeShowList = val;
				this.$store.dispatch('setCookie', { name: 'typeShowList', value: val, delCookie: false } );
			},
			getCookie(name) {
				let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + "=([^;]*)"));
				return matches ? decodeURIComponent(matches[1]) : undefined;
			},
			loadFotorecipes(parent_id) {
				this.$store.dispatch('loadFotorecipes', {
					parent_id: parent_id,
					page: this.page - 1,
					author_id: this.$store.getters.getUser.id,
					diet: (this.diet) ? 1 : 0
				});
			},
			clickDiet() {
				// фильтр по диет питанию
				this.loadFotorecipes(this.$refs.listCategories.$refs.listCategories.value);
			},
			setShowRow(index) {
				if (this.showRow.indexOf(index) === -1) {
					this.showRow.push(index);
				} else {
					this.showRow.splice(this.showRow.indexOf(index), 1);
				}
			},
			edit(id) {
				this.state = 3;
				this.edit_id = id;
			}

		},

	}
</script>

<style scoped>
	.showFotoRecipes {
		text-align: left;
		position: relative;
	}

	.btnAdd {
		display: block;
	}
	.btnAdd > button {
		background-color: royalblue;
		height: 40px;
	}

	hr {
		margin-left: 15px;
		margin-right: 15px;
	}

	.MenuFotoRecipes {
		display: flex;
		margin-right: 15px;
		margin-left: 15px;
	}

	.itemRecipe {
		padding-left: 10px;
		border-left: 5px solid #42b983;
		margin: 4px;
		min-height: 30px;
		line-height: 30px;
		vertical-align: middle;
	}
	.itemRecipe > a {
		display: block;
	}

	.menuItem {
		display: flex;
		justify-content: center;
		align-items: center;
	}

	input[type=checkbox] {
		cursor: pointer;
		margin-right: 5px;
		margin-left: 15px;
		width: 20px;
		height: 20px;
	}

	label {
		display: flex;
		justify-content: center;
		align-items: center;
		cursor: pointer;
	}

	button {
		margin-left: 15px;
		height: auto;
	}

	.listFotoRecipes {
		margin-left: 15px;
		margin-right: 15px;
	}

@media (min-width: 100px) and (max-width: 320px) {
	.MenuFotoRecipes {
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}
}
</style>