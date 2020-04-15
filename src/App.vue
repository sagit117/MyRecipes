<template>
  <div id="app">
    <div id="topPanel">
      <LoginControl :state="showLoginState" @close="(state)=>{ this.showLoginState = state }"/>
    </div>

    <div class="panelMenu">
      <div class="itemMenu">
        <router-link to="/"><div class="home_black" title="Домой"><img src="ico/home.png"></div></router-link>
      </div>
      <div class="itemMenu">
        <a href="#" @click.prevent="openMyRecipes">Мои рецепты</a>
      </div>
    </div>

    <router-view></router-view>

    <Wait v-if="this.$store.getters.getShowWait" />
    <Alert v-if="this.$store.getters.getShowAlert.show" />
    <UpScroll v-if="visibleUpScroll" />
    <BigFoto  v-if="this.$store.getters.getBigFotoData.show"/>

  </div>
</template>

<script>
  // project: MyRecipes
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com 

  import LoginControl from './components/LoginControl.vue'
  import Wait from './components/Wait.vue'
  import Alert from './components/Alert.vue'
  import UpScroll from './components/UpScroll.vue'
  import BigFoto from './components/BigFoto.vue'

  export default {
    name: 'app',

    components: {
      LoginControl,
      Wait,
      Alert,
      UpScroll,
      BigFoto,
    },

    data() {
      return {
        visibleUpScroll: false,
        showLoginState: 0,
      }
    },

    created() {
      window.addEventListener('scroll', this.handleScroll);

      let id = this.getCookie('id');
      if (id > 0) this.$store.dispatch('loginedUserHash', id);
    },

    methods: {
      handleScroll() {
        // видимость дива с кнопкой на верх
        this.visibleUpScroll = (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) ? true : false;
      },
      getCookie(name) {
        let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + "=([^;]*)"));
        return matches ? decodeURIComponent(matches[1]) : undefined;
      },
      openMyRecipes() { // проверяет можно ли открыть мои рецепты, да - если залогинен, нет - показать форму логина
        if (this.$router.history.current.path === '/myrecipes') {
          return;
        } else {
          (this.$store.getters.getUser.id > 0) ? this.$router.push('/myrecipes') : this.showLoginState = 1;
        }
      }
    },

    destroyed: function () {
      window.removeEventListener('scroll', this.handleScroll);
    },

  }
</script>

<style>

  body {
    margin: 0;
  }

  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
  }

  #topPanel {
    text-align: right;
    padding-right: 5px;
  }

  .close {
    position: absolute;
    top: 0px;
    right: 10px;
    color: grey;
    font-weight: 700;
    font-size: 1.5em;
    cursor: pointer;
  }

  .panelMenu {
    display: flex;
    height: 100px;
    background-color: #41444a;
  }

  .itemMenu {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 15px;
  } 
  .itemMenu > a{
    color: white;
  }

  .home_black {
    display: block;
    height: 60px;
    width: 60px;
    margin: auto;
    border-radius: 50%;
    box-shadow: 1px 1px 3px black;
    cursor: pointer;
    background-size: cover;
    overflow: hidden;
    z-index: 0;
  }
  .home_black:active {
    box-shadow: none;
  }
  .home_black > img {
    width: 100%;
    height: 100%;
    z-index: 0;
  }

  button {
    border-radius: 5px;
    box-shadow: 2px 2px 6px #000;
    border: none;
    width: auto;
    height: 30px;
    font-size: 1.1em;
    cursor: pointer;  
    outline: 0;
    color: white;
    background-color: #42b983;  
  }
  button:focus {
    box-shadow: none;
  }
  button:active {
    box-shadow: none;
  }

  input {
    border: none;
    border-bottom: 1px solid #42b983;
    font-size: 14px;
    height: 25px;
    outline: 0;
    border-radius: 0;
  }
  input:hover {
    border-bottom: 1px solid #42b983;
  }
  input:focus {
    border-bottom: 2px solid #42b983;
  }

  a {
    font-weight: bold;
    color: #2c3e50;
  }
  a:hover {
    color: #42b983;
  }

  select {
    font-size: 16px;
    height: 30px;
    cursor: pointer;  
    outline: 0;
    color: white;
    background-color: #42b983;   
  }

  .itemMenu > a.router-link-exact-active, .itemMenu > a.router-link-active {
    color: white;
  }

</style>
