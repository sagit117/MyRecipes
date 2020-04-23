<template>
  <div class="bigFoto" ref="div">

    <img 
      :src="this.$store.getters.getDomainName + this.$store.getters.getBigFotoData.arrayImg[pos].path_img" 
      ref="img" >

    <div class="close" title="Закрыть" @click="close()">
      &#215;
    </div>

    <a  class="right" 
        title="Следующее фото"
        @click="setPosition(1)"> ❯ </a>
    <a  class="left" 
        title="Предыдущее фото"
        @click="setPosition(-1)"> ❮ </a>
    <div class="score">
      {{ pos + 1 }} / {{ this.$store.getters.getBigFotoData.arrayImg.length }} 
      <img src="ico/rotate.png" class="rotate" title="Поворт на 90 градусов" @click="rotate()">
    </div>
  </div>
</template>

<script>
  // component: BigFoto
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.com
  
  export default {
    data() {
      return {
        pos: this.$store.getters.getBigFotoData.position_img,
        deg: 0,
        startX: 0,
      }
    },

    mounted() {
      let img = this.$refs.img;
      img.onload = () => {
        this.matchPos();
      }

      document.body.style.overflow = 'hidden';

      img.addEventListener("touchstart", this.startHandler, false);
      img.addEventListener("touchend", this.endHandler, false);
    },

    methods: {
      close() {
        document.body.style.overflow = 'visible';
        this.$store.commit('setShowBigFoto', { show: false, position_img: 0, arrayImg: [] })
      },
      setPosition(pos) {
        if ((this.pos + pos) < 0) return;
        if ((this.pos + pos) === this.$store.getters.getBigFotoData.arrayImg.length) return;

        this.pos = this.pos + pos;
        this.deg = 0;
        this.matchPos();
      },
      matchPos() {
        // расчитать max размеры фото и поместить в центр
        let img = new Image();
        img.src = this.$refs.img.src;

        img.onload = () => {
          let imgWidth = img.width;
          let imgHeigth = img.height;
          let parentWidth = this.$refs.div.clientWidth;
          let parentHeigth = this.$refs.div.clientHeight - 50;
          let X = parentWidth / imgWidth; 
          let Y = parentHeigth / imgHeigth;

          let val = (X<Y) ? X : Y;

          imgWidth = imgWidth * val;
          imgHeigth = imgHeigth * val;
            
          let top = imgHeigth / 2;
          let left = imgWidth / 2;

          this.$refs.img.style = "top: calc(50% - " + top + "px); left: calc(50% -" + left + "px); height: " + imgHeigth + "px; width: " + imgWidth + "px;";
        }
      },
      rotate() {
        this.deg += 90;
        if (this.deg == 360) this.deg = 0;

        this.$refs.img.style.transform = "rotate(" + this.deg + "deg)";
      },
      startHandler(e) {
        this.startX = e.changedTouches[0].clientX;
        e.preventDefault();
      },
      endHandler(e) {
        if ((e.changedTouches[0].clientX - this.startX) > 30) this.setPosition(-1);
        if ((e.changedTouches[0].clientX - this.startX) < -30) this.setPosition(1);
      }, 
    },

    beforeDestroy: function () {
      let img = this.$refs.img;

      img.removeEventListener("touchstart", this.startHandler);
      img.removeEventListener("touchend", this.endHandler);
    },

  }
</script>

<style scoped>
    .bigFoto {
        position: fixed;
        top: 1%;
        left: 1%;
        width: 98%;
        height: 98%;
        text-align: center;
        background-color: grey;
        z-index: 100;
        overflow: hidden;
    }   
    .bigFoto > img {
        position: relative;
        max-width: 100%;
        max-height: 100%;
        margin: 0 auto;
        opacity: 1;
        top: 0;
    }

    .close {
        position: absolute;
        top: 0;
        right: 15px;
        padding: 5px;
        color: black;
        font-weight: 700;
        font-size: 2em;
        cursor: pointer;
    }

    .right {
        position: absolute;
        color: black;
        width: 30px;
        text-decoration: none;
        border-bottom-left-radius: 5px;
        border-top-left-radius: 5px;
        top: calc(50% - 20px);
        right: 0;
        opacity: 0.5;
        background-color: white;
        font-size: 2.2em;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        padding-top: 3px;
    }
    .right:hover {
        opacity: 1;
    }
    .left {
        position: absolute;
        color: black;
        width: 30px;
        text-decoration: none;
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px;
        top: calc(50% - 20px);
        left: 0;
        opacity: 0.5;
        background-color: white;
        font-size: 2.2em;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        padding-top: 3px;
    }
    .left:hover {
        opacity: 1;
    }

    .score {
        position: absolute;
        bottom: 0;
        width: 100px;
        margin: 0 auto;
        left: calc(50% - 50px);
        text-align: center;
        vertical-align: middle;
        padding-bottom: 3px;
        background-color: rgb(0, 0, 0);
        color: white;
        font-weight: bold;
        border-radius: 15px;
    }

    .rotate {
        width: 20px;
        height: 20px;
        vertical-align: middle;
        cursor: pointer;
    }
  
    .fade-enter-active, .fade-leave-active {
      transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to {
      opacity: 0;
    }

@media (min-width: 100px) and (max-width: 415px) {
  .left, .right {
    font-size: 3em;
  }

  .score {
    bottom: 10px;
  }

  .bigFoto {
    position: fixed;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 20px;
    width: auto;
    height: auto;
  } 
  .bigFoto > img {
    max-width: 100%;
    max-height: 100%;
  }
}

</style>
