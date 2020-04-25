<template>
  <div class="loadFoto">
    <div class="btn" :class="{ isVisible: hiddenBtn }">
      <button title="Загрузить фото" 
              @click="clickLoad" 
              ref="btnLoad">Загрузить</button>
    </div>
    <input  type="file" 
            name="fileFoto" 
            :multiple="multiple"
            ref="inputFile"
            accept="image/jpeg, image/png, image/jpg" 
            v-on:change="upLoad"
            :class="{ isVisible : isHidden }" />
    <div class="imgList" v-if="showImg">
      <div v-for="(img, index) in images" :key="index" class="imgDiv">
        <img :src="img" @click="setOrder(index)" />
        <div v-if="order.indexOf(index) !== -1" class="order">{{ order.indexOf(index)+1 }}</div>
      </div>
    </div>
  </div>
</template>

<script>
  // component: LoadFoto
  // version: 0.0.2
  // date: 04.2020
  // sagit117@gmail.co
  
  export default {

    props: {
      autoLoad: Boolean,  // активирует автозагрузку
      hiddenBtn: Boolean, // скрывает кнопку загрузки фото
      multiple: Boolean,  // загрузка одного файла, если true загрузка множества файлов multiple
      showImg: Boolean,   // скрывает отображение загруженных фото
    },

    data() {
      return {
        isHidden: true,   // скрывает кнопку по умолчанию для загрузки фото
        images: [],
        compressImg: [],
        order: [],
      }
    },

    mounted() {
      // если автозагрузка тогда сразу показать окно выбора файлов
      if (this.autoLoad) this.clickLoad();
    },

    methods: {
      clickLoad() {
        // имитация клика по элементу загрузки файлов
        this.$refs.inputFile.click();
      },
      upLoad(event) {
        let input = event.target;
        this.images = [];
        this.compressImg = [];

        if (input.files && input.files[0]) {
          for (let i=0; i<input.files.length; i++) {
            let reader = new FileReader();

            reader.onload = (e) => {
              this.images.push(e.target.result);
              this.compress(e.target.result);
            }

            if (/\.(jpe?g|png|gif)$/i.test(input.files[i].name)) {
              reader.readAsDataURL(input.files[i]);
            } else {
              console.log(input.files[i].name + " не соответствует формату!");
            }
          }
        } 
      },
      compress(file) {
        let img = new Image();
        let self = this;

        img.onload = function () {
          // расчет высоты ширины 
          let int = setInterval(() => {
            self.$store.commit("setShowWait", true);
            let val = (this.width < this.height) ? Math.floor(this.width / 300) : Math.floor(this.height / 300);
                        
            if (this.width == 0) this.width = 270;
            if (this.height == 0) this.height = 300; 
            if (val == 0) val = 1;

            const elem = document.createElement('canvas');
            elem.width = this.width / val;
            elem.height = this.height / val;
                    
            const ctx = elem.getContext('2d');
            ctx.drawImage(img, 0, 0, elem.width, elem.height);
                        
            if (elem.toDataURL("image/png", 1.0).length > 6074) {
              self.compressImg.push(elem.toDataURL("image/png", 1.0)); 
              clearInterval(int);
            } else {
              console.log('фото не получилось сжать! ');
              self.compressImg.push(file);
              clearInterval(int);
            }

            if (self.compressImg.length === self.images.length) {
              self.$store.commit("setShowWait", false);
              self.$emit("input-file", self.compressImg);
            }
          }, 1000);
        };

        img.src = file;
      },
      setOrder(index) {
        let ind = this.order.indexOf(index);
        (ind == -1) ? this.order.push(index) : this.order.splice(ind, 1);         
      },
    },

  }
</script>

<style scoped>
  
  .isVisible {
    display: none;
  }

  .btn {
    padding-left: 0;
  }

  .imgDiv {
    position: relative;
    width: 290px;
    height: 400px;
    box-shadow: 2px 2px 4px #000;
    margin: 4px;
    display: inline-block;
  }
  .imgDiv > img {
    width: 290px;
    height: 400px;
    object-fit: cover;
  }

  .order {
    position: absolute;
    right: 10px;
    top: 10px;
    width: 20px;
    height: 20px;
    font-weight: 700;
    border-radius: 50%;
    text-align: center;
    background-color: white;
    box-shadow: 2px 2px 2px #000;
  }
</style>