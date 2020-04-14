<template>
	<div>
		<button title="Загрузить новое фото" >Загрузить фото</button>
		<div class="imgList">
			<div v-for="(img, index) in getRecipe.img" :key="index" class="imgItem">
				<img :src="path + img.path_img" >
				<div class="del">
					<img src="ico/delete.png" title="Удалить" @click="delFoto(img.id, index)">
				</div>
				<div class="edit">
					<img src="ico/pen.png" title="Изменить">
				</div>
			</div>

			<div class="close" title="Закрыть" @click="close">
				&#215;
			</div>
		</div>

		<ShowCategory ref="listCategories" :view_add="true" :id_cat="getRecipe.parent_id"/>

		<div class="categories">
			<label>Название рецепта: </label>
			<input  type="text" 
							v-model="nameRecipes" 
							:class="{ error: isError }"
							@input="minLengthRec"/>
		<div class="diet">
				<label><input type="checkbox" v-model="diet">Диетическое</label>
			</div>
		</div>
  
		<span class="errorText">{{ error }}</span>
		<button @click="saveRecipe">Сохранить</button>

		<div class="close" title="Закрыть" @click="close()">
			&#215;
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
		},

		props: {
			id_fotorecipes: Number,
		},

		data() {
			return {
				diet: false,
				nameRecipes: '',
				isError: false,
				
			}
		},


	}
</script>