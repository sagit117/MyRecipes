<template>
  <div class="myRecipes">
    <div class="menu">
      <p>
        <a href="#" class="{ isActive : page === 0 }" @click.prevent="">Фото рецепты</a>
      </p>
      <hr>
    </div>
    
    <ShowFotoRecipes v-if="page === 0" />

  </div>
</template>

<script>
  // project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  import ShowFotoRecipes from '@/components/ShowFotoRecipes.vue'
  
  export default {

    components: {
      ShowFotoRecipes,
    },

    data() {
      return {
        page: 0,
      }
    },
  }
</script>

<style scoped>
  .menu {
    text-align: right;
    margin-left: 15px;
    margin-right: 15px;
  }

  .isActive {
    border-bottom: 14px solid #42b983;
  }


</style>

