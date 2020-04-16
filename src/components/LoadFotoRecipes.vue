<template>
  <div class="loadFotoRecipes">
    <LoadFoto :autoLoad="true" 
              @input-file="getImage"
              ref="loadFoto" 
              :multiple="true"
              :showImg="true" />
    <div class="item">
      <ShowCategory ref="listCategories" :showAddBtn="true" :cat_id="0" />
    </div>
    <div class="item">
      <label>Название рецепта: </label>
      <input  type="text" 
              v-model.trim="nameRecipes" 
              :class="{ error: isError }"
              @input="minLengthName"
              placeholder="Введите название"
              @keyup.enter="saveRecipe">
    </div>
    <div class="item">
      <div class="diet">     
        <label>Диетическое<input type="checkbox" v-model="diet"></label>
      </div>
    </div>
    <div class="item">
      <span class="errorText">{{ errorText }}</span>
    </div>
    <div class="item">
      <button :disabled="block" @click="saveRecipe">Сохранить</button>
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
        isError: false,
      }
    },

    methods: {
      getImage(data) {
        this.images = data; 
      },
      minLengthName() {
        if (this.nameRecipes.trim() === '') {
          this.errorText = "Название не должно быть пустым!";
          this.isError = true;
        } else {
          this.errorText = "";
          this.isError = false;
        }
      },
      saveRecipe() {
        // сохранить рецепт
        this.minLengthName();
        if (this.images.length === 0) this.errorText = 'Необходимо загрузить миниммум одно фото!';

        if (!this.isError && this.images.length !== 0) {
          this.block = true;

          let imgOrder = [];
          let order = this.$refs.loadFoto.order;

          for (let i=0; i<order.length; i++) {
            imgOrder.push(this.images[order[i]]);
          }

          for (let i=0; i<this.images.length; i++) {
            if (order.indexOf(i) == -1) imgOrder.push(this.images[i]);
          }

          this.$store.dispatch('saveFotoRecipes', { 
            name: this.nameRecipes, 
            parent_id: this.$refs.listCategories.$refs.listCategories.value,
            images: imgOrder,
            diet: (this.diet) ? 1 : 0,
            author_id: this.$store.getters.getUser.id,
          }).then(() => {
            this.images = [];
            this.block = false;
            this.$emit("close");
          });
        }
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

  .error {
    border-color: red;
  }

  .item {
    float: left;
    display: block;
    width: 100%;
    margin-top: 10px;
    position: relative;
  }

  .errorText {
    color: red;
  }

  input[type=checkbox] {
    cursor: pointer;
    margin-right: 5px;
    margin-left: 15px;
    width: 20px;
    height: 20px;
  }

@media (min-width: 100px) and (max-width: 415px) {
  input[type=text] {
    width: 100%;
  }
}
</style>