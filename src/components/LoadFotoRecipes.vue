<template>
  <div class="loadFotoRecipes">
    <LoadFoto :autoLoad="true" 
              @input-file="getImage"
              ref="loadFoto" />
    <div class="item">
      <ShowCategory ref="listCategories" :showAddBtn="true" />
    </div>
    <div class="item">
      <label>Название рецепта: </label>
      <input  type="text" 
              name="nameRecipes" 
              v-model="nameRecipes" 
              :class="{ error: isError }"
              @input="minLengthRec"/>
    </div>
    <div class="item">
      <div class="diet">     
        <label>Диетическое<input type="checkbox" v-model="diet"></label>
      </div>
    </div>
    <div class="item">
      <span class="errorText">{{ errorText }}</span>
      <button @click="saveRecipe" :disabled="block">Сохранить</button>
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

<style scoped>
  .loadFotoRecipes {
    display: block;
    margin-left: 15px;
    margin-right: 15px;
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

  input[type=checkbox] {
    cursor: pointer;
    margin-right: 5px;
    margin-left: 15px;
    width: 20px;
    height: 20px;
  }

  .item {
    float: left;
    display: block;
    width: 100%;
    margin-top: 10px;
    position: relative;
  }
</style>