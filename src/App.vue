<template>
  <div id="app">
    <div id="topPanel">
      <LoginControl />
    </div>

    <router-view></router-view>

    <Wait v-if="this.$store.getters.getShowWait" />
    <Alert v-if="this.$store.getters.getShowAlert.show" />
    <UpScroll v-if="visibleUpScroll" />

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

  export default {
    name: 'app',

    components: {
      LoginControl,
      Wait,
      Alert,
      UpScroll,
    },

    data() {
      return {
        visibleUpScroll: false,
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
</style>
