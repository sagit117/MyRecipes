<template>
  <div class="home">
    <h3 class="mt-3"> My Recipes </h3>

    <!--<div class="MenuFotoRecipes"> 
        <div class="menuItem">
          <img src="ico/HAMBURGER MENU.svg" title="Фильтры" @click.stop="showFilterMenu = !showFilterMenu">
        </div>

        <transition name="slide-fade">
          <ul class="menuFilter" v-if="showFilterMenu">
            <div class="close" title="Закрыть" @click.stop="showFilterMenu = !showFilterMenu">
              &#215;
            </div>
            <h5 class="mt-1"> Фильтры </h5>

            <li>
              <ShowCategory 
                ref='listCategories'  
                :showAllGroup="true" 
                :showUserGroup="true" />
            </li>
            <li>
              <label><input type="checkbox" v-model="diet" >Только диетическое</label>
            </li>
            <li>
              <label><input type="checkbox" v-model="onlyFav" >Только избранное</label>
            </li>
          </ul>
        </transition>

			</div>

      <div class="listFotoRecipes" >
        <template v-for="group in this.$store.getters.getCategoriesUserGroup">
          <div class="blockGroup" :key="group.id" >
            <span>{{ group.name }}</span>
            <div class="group">
              <ItemFotoRecipe 
                v-for="recipe in vilterFotoRecipesToGroup(group.id)" 
                :key="recipe.id" 
                :recipe="recipe" />
            </div>
            <a href="#" title="показать все" >показать все</a>
          </div>
        </template>
      </div> -->
  </div>
</template>

<script>
  // project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com 

  //import ShowCategory from '@/components/ShowCategory.vue'

  export default {
    components: {
      //ShowCategory,
    },

    data() {
      return {
        //diet: false,
        //onlyFav: false,
        //showFilterMenu: false,
      }
    },

    created() {
      //this.$store.dispatch('loadCategoriesUserGroup');
    },
  
  }
</script>

<style scoped>
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

  .menuFilter {
    position: absolute;
    top: 30px;
    list-style: none;
    background-color: white;
    z-index: 99;
    text-align: left;
    box-shadow: 2px 2px 4px #000;
    max-width: 100%;
    overflow-x: auto;
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

  .listFotoRecipes {
		margin-left: 15px;
		margin-right: 15px;
	}

  .blockGroup {
    border-top: 1px solid #42b983;
    margin-top: 25px;
    padding-bottom: 20px;
    position: relative;
    text-align: left;
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

</style>
