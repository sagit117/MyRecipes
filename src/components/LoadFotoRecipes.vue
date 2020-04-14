<template>
  <div class="loadFotoRecipes">
    <LoadFoto :autoLoad="true" 
              @input-file="getImage"
              ref="loadFoto" />
    <div class="categories">
      <ShowCategory ref="listCategories" :showAddBtn="true" />
    </div>
    <div class="categories">
      <label>Название рецепта: </label>
      <input  type="text" 
              name="nameRecipes" 
              v-model="nameRecipes" 
              :class="{ error: isError }"
              @input="minLengthRec"/>
      <div class="diet">
        <label><input type="checkbox" v-model="diet">Диетическое</label>
      </div>
  </div>
    <span class="errorText">{{ errorText }}</span>
    <div class="categories">
      <button class="all" @click="saveRecipe" :disabled="block">Сохранить</button>
    </div>

    <div class="close" title="Закрыть" @click="() => { this.$emit('close', false) }">
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
  import LoadFoto from '@/components/LoadFoto.vue'

  export default {

    components: {
      ShowCategory,
      LoadFoto,
    },

    data() {
      return {
        diet: false,
        errorText: '',
        images: [],
        nameRecipes: '',
        block: false,
      }
    },

    methods: {
      getImage(data) {
        this.images = data; 
      },

    },

  }
</script>