<template>
	<div class="itemRecipeBlock" @click="showBigFoto">
		<img :src="path + recipe.img[this.position].path_img">
		<span >
			{{ (recipe.name.length > 30) ? recipe.name.substr(0, 30) + "..." : recipe.name }}
		</span>
		<div class="pen" name="pen" @click.stop="() => { $emit('edit', recipe.id) }">
			<img src="ico/pen.png" title="Редактировать" name="pen">Редактировать 
		</div>
		<a 	class="right" 
				title="Следующее фото"
				@click.stop="setPosition(1)"> ❯ </a>
		<a 	class="left" 
				title="Предыдущее фото" 
				@click.stop="setPosition(-1)"> ❮ </a>
    <div class="menu">
      <img src="ico/HEART.svg" v-if="!recipe.fav" title="В избранное" @click.stop="addFavorite">
      <img src="ico/HEART-red.png" v-else title="Убрать из избранного" @click.stop="addFavorite">
      <span @click.stop="addFavorite">Избранное</span>
    </div>

    <div class="del" @click.stop="deleteRecipe">
			<img src="ico/delete.png" title="Удалить">
		</div>
	</div>
</template>

<script>
	// project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  export default {

		props: {
			recipe: Object,
		},

		data() {
			return {
				path: this.$store.getters.getDomainName,
				position: 0,
			}
		},

		methods: {
			setPosition(pos) {
				if ((this.position + pos) < 0) return;
				if ((this.position + pos) === this.recipe.img.length) return;

				this.position = this.position + pos;
			},
			showBigFoto() {
				this.$store.commit("setShowBigFoto", { show: true, arrayImg: this.recipe.img, position_img: this.position });
      },
      addFavorite() {
        // добавить в избранное
        if (!this.recipe.fav) {
          this.$store.dispatch("addFavoriteFotorecipe", { id_recipe: this.recipe.id, id_user: this.$store.getters.getUser.id})
          .then(() => {
            this.recipe.fav = true;
          });
        } else {
          this.$store.dispatch("rmFavoriteFotorecipe", { id_recipe: this.recipe.id, id_user: this.$store.getters.getUser.id})
          .then(() => {
            this.recipe.fav = false;
          });
        }
      },
      deleteRecipe() {
        if (confirm("Удалить рецепт?")) {
          // удаление рецепта
          this.$store.dispatch("rmFotoRecipe", { id: this.recipe.id })
          .then(() => {
            this.$emit('delete');
          }); 
        }
      }
		},

  }
</script>

<style scoped>
	.itemRecipeBlock {
		position: relative;
		cursor: pointer;
		margin-top: 30px;
		margin-right: 5px;
		width: 272px;
		height: 330px;
		display: inline-block;
		text-align: center;
		/*overflow: hidden;*/
		padding: 5px;
		background-color: #41444a;
	}
	.itemRecipeBlock > img {
		width: calc(100% - 2px);
		height: 300px;
		object-fit: cover;
	}	
	.itemRecipeBlock > span {
		line-height: 20px;
		color: white;
	}

	.pen {
		position: absolute;
		top: 10px;
		right: 0px;
		width: 30px;
		height: 30px;
		background-color: white;
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
		vertical-align: middle;
		line-height: 30px;
		overflow: hidden;
		color: black;
		background-color: #ccd1d9;
	}
	.pen:hover {
		width: 150px;
	}
	.pen > img {
		width: 20px;
		height: 26px;
		float: left;
		padding: 2px;
		object-fit: cover;
	}
	.right {
		position: absolute;
		color: black;
		width: 30px;
		text-decoration: none;
		border-bottom-left-radius: 5px;
		border-top-left-radius: 5px;
		top: calc(50% - 20px);
		right: 0;
		opacity: 0.5;
		background-color: grey;
		font-size: 2em;
		text-align: center;
		vertical-align: middle;
	}
	.right:hover {
		opacity: 1;
	}
	.left {
		position: absolute;
		color: black;
		width: 30px;
		text-decoration: none;
		border-bottom-right-radius: 5px;
		border-top-right-radius: 5px;
		top: calc(50% - 20px);
		left: 0;
		opacity: 0.5;
		background-color: grey;
		font-size: 2em;
		text-align: center;
		vertical-align: middle;
	}
	.left:hover {
		opacity: 1;
	}

  .menu {
    display: flex;
    position: absolute;
    height: 30px;
    top: -25px;
    width: 272px;
    cursor: default;
    align-items: center;
  }
  .menu > img {
    height: 25px;
    width: 25px;
    padding: 2px;
    cursor: pointer;
  }
  .menu > span {
    cursor: pointer;
  }

  .del {
		position: absolute;
		top: 10px;
		left: 10px;
  }
  .del > img {
		width: 30px;
		height: 30px;
		vertical-align: middle;
		cursor: pointer;
  }

</style>