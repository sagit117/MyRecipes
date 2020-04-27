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
            <h5> Фильтры </h5>

            <li>
              <ShowCategory 
                ref='listCategories' 
                @change-group="changeGroup" 
                :id_cat="parseInt(parent_id)" 
                :showAllGroup="true" />
            </li>
            <li>
              <label><input type="checkbox" v-model="diet" @change="loadFotorecipes">Только диетическое</label>
            </li>
            <li>
              <label><input type="checkbox" v-model="onlyFav" @change="onlyFavVisible(false)">Только избранное</label>
            </li>
          </ul>
        </transition>

			</div>

			<div class="listFotoRecipes" v-if="typeShowList === 1"> <!-- строки -->
				<div class="itemRecipe" v-for="(recipe, index) in this.$store.getters.getDataFotoRecipes" :key="recipe.id">
					<a href="#" @click.prevent="setShowRow(index)">{{ recipe.name }}</a>
					<ItemFotoRecipe 
						:recipe="recipe" 
						v-if="showRow.indexOf(index) !== -1" 
						@edit="edit(recipe)" 
            @delete="loadFotorecipes" />
				</div>
        <PageList 
          :totalPage="Math.ceil($store.getters.getTotalFotoRecipes / $store.getters.getLimitFotoRecipes)" 
          @active-page="setPage" />
			</div>

			<div class="listFotoRecipes" v-else> <!-- кубы -->

        <div class="blockGroup" v-if="recipeFav.length !== 0">
          <span>Избранное</span>
          <div class="group">
            <ItemFotoRecipe 
              v-for="recipe in recipeFav" 
              :key="recipe.id" 
              :recipe="recipe" 
              @edit="edit(recipe)" 
              @delete="loadFotorecipes" />
          </div>
          <a href="#" title="показать все" @click.prevent="onlyFavVisible(true)">показать все</a>
        </div>

        <div class="blockGroup" v-if="vilterFotoRecipesNoGroup().length !== 0"> <!--не должно использоваться-->
          <span>Без категории</span>
          <div class="group">
            <ItemFotoRecipe 
              v-for="recipe in vilterFotoRecipesNoGroup()" 
              :key="recipe.id" 
              :recipe="recipe" 
              @edit="edit(recipe)" 
              @delete="loadFotorecipes" />
          </div>
        </div>

        <template v-for="group in this.$store.getters.getCategoriesRecipe">
          <div class="blockGroup" :key="group.id" v-if="vilterFotoRecipesToGroup(group.id).length !== 0">
            <span>{{ group.name }}</span>
            <div class="group">
              <ItemFotoRecipe 
                v-for="recipe in vilterFotoRecipesToGroup(group.id)" 
                :key="recipe.id" 
                :recipe="recipe" 
                @edit="edit(recipe)" 
                @delete="loadFotorecipes" />
            </div>
            <a href="#" title="показать все" @click.prevent="changeGroup(group.id)">показать все</a>
          </div>
        </template>

			</div>

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
  //import lib from '@/lib/lib.js'
	
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
        limit_foto_in_group: 20, // лимит рецептов для отображения в группах
        onlyFav: false,
			}
		},

		created() {
			this.loadFotorecipes();
      //this.typeShowList = (!lib.getCookie('typeShowList')) ? 2 : parseInt(lib.getCookie('typeShowList'));
      this.$store.dispatch('loadCategoriesRecipes', { parent_id: 0, author_id: this.$store.getters.getUser.id });
    },

    watch: {
      typeShowList: function() {
        // если не очищать, при переходе в строки они открыты
        this.showRow = [];
      }
    },

    computed: {
      recipeFav: function() {
        // вычислить рецепты для избранного
        const listFR = this.$store.getters.getDataFotoRecipes;
        if (typeof(listFR) === 'string') return [];

        const result = listFR.filter(filterRec);

        function filterRec(recipe) {
          return recipe.fav;
        }

        if (result.length > this.limit_foto_in_group) {
          result.length = this.limit_foto_in_group;
        }

        return result;
      },
    },

		methods: {
      onlyFavVisible(state) {
        if (state) this.onlyFav = true;

        if (this.onlyFav) {
          this.typeShowList = 1;
        } else {
          this.typeShowList = 2;
        }

        this.loadFotorecipes();
      },
			changeGroup(id) {
        this.parent_id = id;
        if (id > 0) {
          this.typeShowList = 1;
        } else {
          this.typeShowList = 2;
        }
        this.loadFotorecipes();
      },
			loadFotorecipes() {
				this.$store.dispatch('loadFotorecipes', {
					parent_id: this.parent_id,
					page: this.page - 1,
					author_id: this.$store.getters.getUser.id,
					diet: (this.diet) ? 1 : 0,
          fav: (this.onlyFav) ? 1 : 0,
				});
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
      vilterFotoRecipesToGroup(id) {
        const listFR = this.$store.getters.getDataFotoRecipes;
        if (typeof(listFR) === 'string') return [];

        const result = listFR.filter(filterRec);

        function filterRec(recipe) {
          if (recipe.parent_id === id) return true;
          return false;
        }

        if (result.length > this.limit_foto_in_group) {
          result.length = this.limit_foto_in_group;
        }
        
        return result;
      },
      vilterFotoRecipesNoGroup() {
        const listFR = this.$store.getters.getDataFotoRecipes;
        if (typeof(listFR) === 'string') return [];

        const result = listFR.filter(filterRec);

        function filterRec(recipe) {
          if (recipe.parent_id == 0) return true;
          return false;
        }

        if (result.length > this.limit_foto_in_group) {
          result.length = this.limit_foto_in_group;
        }

        return result;
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
		/*line-height: 30px;*/
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

  .blockGroup {
    border-top: 1px solid #42b983;
    margin-top: 25px;
    padding-bottom: 20px;
    position: relative;
  }
  .blockGroup > span {
    position: absolute;
    top: -20px;
  }
  .blockGroup > a {
    float: right;
  }

  .group {
    position: relative;
    white-space: nowrap; 
    overflow-x: scroll;
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


@media (min-width: 100px) and (max-width: 610px) {
	/*.MenuFotoRecipes {
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}*/
}
</style>