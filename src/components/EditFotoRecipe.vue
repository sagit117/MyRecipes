<template>
	<div class="editFotoRecipe">

    <div class="imgList">
			<div v-for="(img, index) in recipe.img" :key="img.id" class="imgItem">
				<img :src="path + img.path_img" ref="imgView" @click.stop="setOrder(index)">
        <div v-if="order.indexOf(index) !== -1" class="order">
          {{ order.indexOf(index)+1 }}
        </div>
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
      :showImg="false" />

    <div class="imgList">
			<div v-for="(img, index) in addImg" :key="index" class="imgItem">
        <img :src="img" ref="imgView" @click="setOrder(index+recipe.img.length)">
        <div v-if="order.indexOf(index+recipe.img.length) !== -1" class="order">
          {{ order.indexOf(index+recipe.img.length)+1 }}
        </div>
			</div>
    </div>

		<ShowCategory 
      ref="listCategories" 
      :showAddBtn="true" 
      :id_cat="parseInt(recipe.parent_id)" 
      :showAllGroup="false" />

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
			<button @click="save">Сохранить</button>
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
				diet: (parseInt(this.recipe.diet) === 1) ? true: false,
				nameRecipe: this.recipe.name,
        isError: false,
        isErrorCat: false,
				errorText: '',
        path: this.$store.getters.getDomainName,
        newImg: [],
        addImg: [],
        order: [],
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
        this.addImg = arr;
      },
      save() {
        // сохранить
        this.minLength();

        if (this.$refs.listCategories.$refs.listCategories.value == 0) {
          this.isErrorCat = true;
          this.errorText = "Нужно выбрать категорию!";
        }

        if (this.isError || this.isErrorCat) return;
        /*  
          собрать массив из изменненных фото и id от старых
          добавить в массив новый массив из новых фото с id 0
          отсортировать по order
          полученный массив передать в бэкенд
        */

        // Собрать массив из всех фото
        let arr = [];
        for (let i=0; i<this.recipe.img.length; i++) {
          if (this.newImg[i] !== undefined) {
            arr.push({ id: this.recipe.img[i].id, img: this.newImg[i], new: true });
          } else {
            arr.push({ id: this.recipe.img[i].id, img: this.recipe.img[i].path_img, new: false });
          }
        }
        for (let i=0; i<this.addImg.length; i++) {
          arr.push({ id: 0, img: this.addImg[i], new: true });
        }

        // сортировать полученный массив по массиву order
        let orderImg = [];
        for (let i=0; i<this.order.length; i++) {
          orderImg.push(arr[this.order[i]]);
        }

        for (let i=0; i<arr.length; i++) {
          if (orderImg.indexOf(arr[i]) === -1) orderImg.push(arr[i]);
        }

        this.$store.dispatch('updateFotoRecipe', { 
          id: this.recipe.id,
          name: this.nameRecipe,
          diet: (this.diet) ? 1 : 0,
          parent_id: this.$refs.listCategories.$refs.listCategories.value,
          images: orderImg
         })
         .then(() => {
           this.$emit('close');
         })
      },
      setOrder(index) {
        let ind = this.order.indexOf(index);
        (ind == -1) ? this.order.push(index) : this.order.splice(ind, 1);
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
		left: 10px;
  }
  .del > img {
		width: 30px;
		height: 30px;
		vertical-align: middle;
		cursor: pointer;
  }

  .edit {
		position: absolute;
		top: 12px;
		right: 0px;
		width: 40px;
		height: 25px;
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
		line-height: 30px;
		overflow: hidden;
		color: black;
		background-color: #ccd1d9;
    cursor: pointer;
  }
  .edit > img {
		width: 25px;
		height: 25px;
		float: left;
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

  .order {
    position: absolute;
    right: calc(50% - 10px);
    top: 10px;
    width: 20px;
    height: 20px;
    font-weight: 700;
    border-radius: 50%;
    text-align: center;
    background-color: white;
    box-shadow: 2px 2px 2px #000;
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