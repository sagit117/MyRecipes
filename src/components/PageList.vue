<template>
    <div class="pageList">
      <div 
        class="page" v-for="index in totalPage" 
        :key="index" 
        :class="{ pageActive: index === activePage }"
        @click="setActivePage(index)"> 
        {{ index }} 
      </div>
    </div>
</template>

<script>
  // component: PageList
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com

  export default {
    props: {
      totalPage: Number,
    },

    data() {
      return {
        activePage: 1,
      }
    },

    methods: {
      setActivePage(index) {
        this.activePage = index;
        this.$emit("active-page", index);
      }
    },

  }
</script>

<style scoped>
  .pageList {
    display: flex;
    /*width: 100%;*/
    justify-content: center;
    align-items: center;
    margin: 15px;
    flex-wrap: wrap;
  }
  .page {
    border-radius: 50%;
    height: 40px;
    width: 40px;
    font-weight: bold;
    box-shadow: 2px 2px 4px #000;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: white;
    cursor: pointer;
    margin: 2px;
  }
  .pageActive {
    background-color: #42b983;
    color: white;
  }

</style>