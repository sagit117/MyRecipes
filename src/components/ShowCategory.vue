<template>
	<div class="showCategory">
		<label>Выбрать категорию: </label>

    <select ref="listCategories">
			<option value="0">Все</option>
      <option v-for="item in this.$store.getters.getCategoriesRecipe" 
              :key="item.id"
              :value="item.id"
              :selected="parseInt(item.id) === valueSel">{{ item.name }}</option>
    </select>

    <template v-if="showAddBtn">
      <div class="addDiv" @click="()=>{ this.showAddDiv = !this.showAddDiv }">
        <img  src="ico/add.png" 
              class="add"  
              title="Добавить категорию">
      </div>
      <div class="newCategory" v-if="showAddDiv">
          <input  type="text" 
                  autofocus
                  placeholder="Введите название"
                  v-model="nameCategory"
                  @input="inputName"
                  :class="{ error: errName }">
          <div>
            <button @click="()=>{ this.showAddDiv = false }">Отмена</button>
            <button @click="saveCat">Сохранить</button>
          </div>
      </div>
    </template>
	</div>
</template>

<script>
  
  export default {
    props: {
      showAddBtn: Boolean,
    },

    data() {
      return {
        showAddDiv: false,
        nameCategory: '',
        errName: false,
        valueSel: 0,        // ID для выбранной категории
      }
    },

    mounted() {
      this.$store.dispatch('loadCategoriesRecipes', { parent_id: 0, author_id: this.$store.getters.getUser.id });
    },

    methods: {
      inputName() {
        (this.nameCategory.trim() !== '') ? this.errName = false : this.errName = true;
      },
      saveCat() {
        this.inputName();
        if (!this.errName) {
          this.$store.dispatch('addCategorie', { parent_id: 0, name: this.nameCategory.trim(), author_id: this.$store.getters.getUser.id })
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
  .showCategory {
    padding-left: 10px;
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
  }

  .error {
    border-color: red;
  }
  input {
    width: 100%;
    margin-bottom: 10px;
  }

</style>

