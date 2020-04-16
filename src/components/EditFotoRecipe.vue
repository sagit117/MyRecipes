<template>
	<div class="editFotoRecipe">

    <div class="imgList">
			<div v-for="(img, index) in recipe.img" :key="img.id" class="imgItem">
				<img :src="path + img.path_img" ref="imgView">
        <LoadFoto
          :autoLoad="false" 
          @input-file="(arr) => { getImage(arr, index) }"
          ref="img" 
          :multiple="false"
          :hiddenBtn="true" 
          :showImg="false" />
				<div class="del" @click.stop="delFoto(img.id, index)">
					<img src="ico/delete.png" title="Удалить">
				</div>
				<div class="edit" @click.stop="editFoto(index)" >
					<img src="ico/pen.png" title="Изменить" >
				</div>
			</div>

			<div class="close" title="Закрыть" @click="$emit('close')">
				&#215;
			</div>
		</div>

    <LoadFoto
      :autoLoad="false" 
      @input-file="getImageMultiple"
      :multiple="true"
      ref="loadFoto" 
      :showImg="true" />

		<ShowCategory ref="listCategories" :showAddBtn="true" :id_cat="parseInt(recipe.parent_id)"/>

		<div class="item">
			<label>Название рецепта: </label>
			<input 
        type="text" 
				v-model.trim="nameRecipe" 
				:class="{ error: isError }"
        @input="minLength">
		</div>
		<div class="item">
			<div class="diet">
				<label><input type="checkbox" v-model="diet">Диетическое</label>
			</div>
		</div>
  
		<span class="errorText">{{ errorText }}</span>

		<div class='item'>
			<button>Сохранить</button>
		</div>
	</div>
</template>

<script>
	// project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  import ShowCategory from '@/components/ShowCategory.vue'
  import LoadFoto from '@/components/LoadFoto.vue'
	
	export default {

		components: {
      ShowCategory,
      LoadFoto,
		},

		props: {
			recipe: Object,
		},

		data() {
			return {
				diet: false,
				nameRecipe: '',
				isError: false,
				errorText: '',
        path: this.$store.getters.getDomainName,
        newImg: [],
			}
		},

		methods: {
			delFoto(id, index) {
        //  удалить фото
        if (this.recipe.img.length == 1) {
					let alert = {show: true, caption: "Ошибка!", text: "Должно быть миннимум одно фото!", type: 1};
          this.$store.commit('setShowAlert', alert);
          return;
        }

				if (confirm("Удалить фото?")) {
          //let self = this;
          this.$store.dispatch("deleteImg", { id: id, index: index, rec_id: this.recipe.id })
          .then(() => {
            // удалить фото из массива новых фото
            if (this.newImg.length > index) {
              this.newImg.splice(index, 1);
              console.log(this.newImg);
            }
          })
          .catch(() => {
            return;
          });
				}
      },
      editFoto(index) {
        this.$refs.img[index].$refs.btnLoad.click();
      },
      minLength() {
        if (this.nameRecipe.length === 0) {
          this.isError = true;
          this.errorText = "Название рецепта не должно быть пустым!"
        } else {
          this.isError = false;
          this.errorText = "";
        }
      },
      getImage(arr, index) {
        // изменение фото
        this.$refs.imgView[index].src = arr[0]; // изменение src для отображения нового фото
        this.newImg[index] = arr[0];            // добавление фото в массив новых фото
      },
      getImageMultiple(arr) {
        console.log(arr);
      },
    },


	}
</script>

<style scoped>
	.editFotoRecipe {
		margin-right: 15px;
		margin-left: 15px;
  }

	.imgItem {
		position: relative;
		display: inline-block;
		width: 300px;
		height: 400px;
    box-shadow: 2px 2px 4px #000;
		margin-right: 5px;
		margin-top: 5px;
	}
	.imgItem > img {
    width: 300px;
    height: 400px;
    object-fit: cover;
  }

  .del {
		position: absolute;
		top: 10px;
		right: 10px;
  }
  .del > img {
		width: 30px;
		height: 30px;
		vertical-align: middle;
		cursor: pointer;
  }

  .edit {
		position: absolute;
		top: 10px;
		left: 0px;
		width: 40px;
		height: 25px;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
		line-height: 30px;
		overflow: hidden;
		color: black;
		background-color: #ccd1d9;
    cursor: pointer;
  }
  .edit > img {
		width: 20px;
		height: 20px;
		float: right;
		padding: 2px;
		vertical-align: middle;
  }

  .diet {
    display: flex;
    align-items: center;
  }
  .diet > label {
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
  }

  .errorText {
    color: red;
  }

  .error {
    border-color: red;
  }

  .item {
		padding-top: 5px;
		padding-bottom: 5px;
  }

  input[type=checkbox] {
    cursor: pointer;
    margin-right: 5px;
    margin-left: 0px;
    padding-left: 0px;
    width: 20px;
    height: 20px;
  }

  input[type=file] {
    display: none;
  }

</style>