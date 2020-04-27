<template>
  <div class="showCategory">
    <div class="showCategoryItems">
      <label>Выбрать категорию: </label>

      <div class="option">
        <select ref="listCategories" 
          @change="()=>{ this.$emit('change-group', this.$refs.listCategories.value) }" >
          <option value="0" v-if="showAllGroup">Все</option>
          <option value="0" v-else>Нет группы</option>
          <option v-for="item in this.$store.getters.getCategoriesRecipe" 
            :key="item.id"
            :value="item.id"
            :selected="parseInt(item.id) === valueSel"
            >{{ item.name }}</option>
        </select>

        <div class="addDiv" @click="()=>{ this.showAddDiv = !this.showAddDiv }" v-if="showAddBtn">
          <img src="ico/add.png" 
            class="add"  
            title="Добавить категорию">
        </div>
      </div>
    </div>
    <div class="newCategory" v-if="showAddDiv">
      <input type="text" 
        autofocus
        placeholder="Введите название"
        v-model.trim="nameCategory"
        @input="inputName"
        :class="{ error: errName }"
        @keyup.enter="saveCat">
      <div>
        <button @click="()=>{ this.showAddDiv = false }">Отмена</button>
        <button @click="saveCat">Сохранить</button>
      </div>
    </div>
  </div>
</template>

<script>
  
  export default {
    props: {
      showAddBtn: Boolean,
      id_cat: Number,
      showAllGroup: Boolean,
    },

    data() {
      return {
        showAddDiv: false,
        nameCategory: '',
        errName: false,
        valueSel: this.id_cat,        // ID для выбранной категории
      }
    },

    mounted() {
      this.$store.dispatch('loadCategoriesRecipes', { parent_id: 0, author_id: this.$store.getters.getUser.id });
    },

    methods: {
      inputName() {
        (this.nameCategory !== '') ? this.errName = false : this.errName = true;
      },
      saveCat() {
        this.inputName();
        if (!this.errName) {
          this.$store.dispatch('addCategorie', { parent_id: 0, name: this.nameCategory, author_id: this.$store.getters.getUser.id })
          .then((resolve) => {
            this.showAddDiv = false;
            this.nameCategory = '';
            if (resolve > 0) this.valueSel = resolve;
          });
        } 
      },

    },

  }
</script>

<style scoped>
  .showCategoryItems {
    /*padding-left: 15px;*/
    display: flex;
    align-items: center;
  }

  .add {
    vertical-align: middle;
    width: 34px;
    height: 34px;
    cursor: pointer;
  }

  .addDiv {
    display: inline-block;
    position: relative;
  }

  .newCategory {
    box-shadow: 2px 2px 3px #000;
    padding: 10px;
    background-color: white;
    max-width: 400px;
  }

  .error {
    border-color: red;
  }

  .option {
    display: flex;
  }

  input {
    width: calc(100% - 10px);
    margin-bottom: 10px;
  }

  button {
    margin-right: 5px;
  }

@media (min-width: 100px) and (max-width: 670px) {
  .showCategory {
    /*flex-wrap: wrap;*/
    justify-content: center;
  }
}

@media (min-width: 100px) and (max-width: 325px) {
  .showCategoryItems {
    flex-wrap: wrap;
    justify-content: center;
  }
}

</style>

