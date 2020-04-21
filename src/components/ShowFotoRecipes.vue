<template>
	<div class="showFotoRecipes">

		<template v-if="state === 1">
			<div class="btnAdd">
				<button title="Загрузить новый рецепт" @click="()=>{ this.state = 2 }">Загрузить новый рецепт</button>
			</div>
			<hr>

			<div class="MenuFotoRecipes"> 
        <div class="menuItem">
          <img src="ico/HAMBURGER MENU.svg" title="Фильтры" @click.stop="showFilterMenu = !showFilterMenu">
        </div>

        <transition name="slide-fade">
        <ul class="menuFilter" v-if="showFilterMenu">
          <div class="close" title="Закрыть" @click="showFilterMenu = !showFilterMenu">
            &#215;
          </div>
          <h3> Фильтры </h3>

          <li>
            <ShowCategory ref='listCategories' @change-group="changeGroup" :id_cat="parseInt(parent_id)" />
          </li>
          <li>
            <label><input type="checkbox" v-model="diet" @change="clickDiet">Только диетическое</label>
          </li>
          <li v-if="typeShowList === 2"
							@click="setTypeShowList(1)">
            <button>Показать в виде строк</button>
          </li>
          <li v-else
							@click="setTypeShowList(2)">
            <button>Показать в виде кубов</button>
          </li>
        </ul>
        </transition>

			</div>

			<div class="listFotoRecipes" v-if="typeShowList === 1">
				<div class="itemRecipe" v-for="(recipe, index) in this.$store.getters.getDataFotoRecipes" :key="recipe.id">
					<a href="#" @click.prevent="setShowRow(index)">{{ recipe.name }}</a>
					<ItemFotoRecipe 
						:recipe="recipe" 
						v-if="showRow.indexOf(index) !== -1" 
						@edit="edit(recipe)" />
				</div>
			</div>
			<div class="listFotoRecipes" v-else>
				<ItemFotoRecipe 
					v-for="recipe in this.$store.getters.getDataFotoRecipes" 
					:key="recipe.id" 
					:recipe="recipe" 
					@edit="edit(recipe)" />
			</div>

      <PageList :totalPage="Math.ceil($store.getters.getTotalFotoRecipes / $store.getters.getLimitFotoRecipes)" 
                @active-page="setPage"/>

		</template>

		<LoadFotoRecipes v-if="state === 2" @close="winUpdate(1)"/>
		<EditFotoRecipe v-if="state === 3" :recipe="edit_recipe" @close="winUpdate(1)"/>

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
  import PageList from '@/components/PageList.vue'
  import lib from '@/lib/lib.js'
	
	export default {
		components: {
			ShowCategory,
			ItemFotoRecipe,
			LoadFotoRecipes,
      EditFotoRecipe,
      PageList,
		},

		data() {
			return {
				diet: false,
				typeShowList: 2,
				page: 1,
				showRow: [],
				state: 1,
        edit_recipe: {},
        showFilterMenu: false,
        parent_id: 0,
			}
		},

		created() {
			this.loadFotorecipes(0);
			this.typeShowList = (!lib.getCookie('typeShowList')) ? 2 : parseInt(lib.getCookie('typeShowList'));
		},

		methods: {
			changeGroup(id) {
        this.parent_id = id;
        this.loadFotorecipes();
			},
			setTypeShowList(val) {
				this.typeShowList = val;
				this.$store.dispatch('setCookie', { name: 'typeShowList', value: val, delCookie: false } );
			},
			loadFotorecipes() {
				this.$store.dispatch('loadFotorecipes', {
					parent_id: this.parent_id,
					page: this.page - 1,
					author_id: this.$store.getters.getUser.id,
					diet: (this.diet) ? 1 : 0
				});
			},
			clickDiet() {
				// фильтр по диет питанию
				this.loadFotorecipes();
			},
			setShowRow(index) {
				if (this.showRow.indexOf(index) === -1) {
					this.showRow.push(index);
				} else {
					this.showRow.splice(this.showRow.indexOf(index), 1);
				}
			},
			edit(recipe) {
				this.state = 3;
				this.edit_recipe = recipe;
			},
			winUpdate(state) {
				this.state = state;
				this.loadFotorecipes();
      },
      setPage(index) {
        this.page = index;
        this.loadFotorecipes();
      },

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
		height: 40px;
	}

	.MenuFotoRecipes {
		display: flex;
		margin-right: 15px;
		margin-left: 15px;
    position: relative;
	}

  .menuItem {
		display: flex;
		/*justify-content: center;*/
		align-items: center;
	}
  .menuItem > img {
    cursor: pointer;
    width: 50px;
    height: 50px;
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

  .listFotoRecipes {
		margin-left: 15px;
		margin-right: 15px;
	}

  .menuFilter {
    position: absolute;
    top: 30px;
    list-style: none;
    background-color: white;
    z-index: 99;
    text-align: left;
    box-shadow: 2px 2px 4px #000;
  }
  .menuFilter > li {
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 15px;
    margin-bottom: 15px;
    padding-right: 5px;
  }
  .menuFilter > li:last-child {
    border-bottom: none;
    padding-bottom: 15px;
    margin-bottom: 15px;
  }
  .menuFilter > li > button {
    margin-left: 0px;
  }

	input[type=checkbox] {
		cursor: pointer;
		margin-right: 5px;
		/*margin-left: 15px;*/
		width: 20px;
		height: 20px;
	}

	label {
		display: flex;
		/*justify-content: center;*/
		align-items: center;
		cursor: pointer;
	}

	button {
		margin-left: 15px;
		height: auto;
	}
	
  hr {
		margin-left: 15px;
		margin-right: 15px;
	}

  .slide-fade-enter-active {
    transition: all .3s ease;
  }
  .slide-fade-leave-active {
    transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
  }

@media (min-width: 100px) and (max-width: 610px) {
	/*.MenuFotoRecipes {
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}*/
}
</style>