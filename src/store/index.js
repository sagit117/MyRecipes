import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // app
    domainName: "https://www.axel-dev.ru.com/", // Имя домена для обращение к api
    // wait
    showWait: false,                            // показать окно ожидания
    // alert
    alert: {
      show: false,                              // показать окно с сообщением
      caption: '',                              // заголовок окна
      text: '',                                 // текст окна
      type: 0,                                  // тип сообщения 1- error, 2 - alert, 3 - success
    },
    // user
    user: {                                     // текущий пользвоатель
      id: 0,
      post: '',
      name: '',
      surname: '',
      patronymic: '',
      datereg: '',
      rule: '',
    },
    // категории рецептов
    categoriesRecipes: '',                      // JSON категории рецептов по родителю, автору
    // фото рецепты
    fotoRecipes: '',                            // JSON фото рецептов по страницам, авторам и родителю
    limit_foto_recipes: 30,                     // лимит вывода на страницу 
    // BigFoto
    bigFoto: {
      show: false,                              // Показать див для просмотра изображений
      arrayImg: [],                             // Массив картинок
      position_img: 0,                          // Позиция фото
    },
  },

  mutations: {
    setShowWait(state, data) { // показ ожидания
      state.showWait = data;
    },
    setShowAlert(state, data) { // показать окно сообщений
      state.alert.show = data.show;
      state.alert.caption = data.caption;
      state.alert.text = data.text;
      state.alert.type = data.type;
    },
    setUserData(state, data) { // установка значений пользователя
      if (data === null) {
        state.user.id = 0;
        state.user.post = '';
        state.user.name = '';
        state.user.surname = '';
        state.user.patronymic = '';
        state.user.datereg = '';
        state.user.rule = '';
      } else {
        state.user.id = data.id;
        state.user.post = data.post;
        state.user.name = (data.name !== null) ? data.name : '';
        state.user.surname = (data.surname !== null) ? data.surname : '';
        state.user.patronymic = (data.patronymic !== null) ? data.patronymic : '';
        state.user.datereg = data.datereg;
        state.user.rule = data.rule;
      }
    },
    setNameUser(state, data) { // установить ФИО пользователя
      state.user.name = data.name;
      state.user.surname = data.surname;
      state.user.patronymic = data.patronymic;
    },
    setCategoriesRecipes(state, data) { // установка категорий рецептов
      state.categoriesRecipes = data;
    },
    setDataFotoRecipes(state, data) { // установка данных фото рецептов
      state.fotoRecipes = data;
    },
    setShowBigFoto(state, data) { // показать окно большого фото
      state.bigFoto.show = data.show;
      state.bigFoto.arrayImg = data.arrayImg;
      state.bigFoto.position_img = data.position_img;
    },
    delFoto(state, data) { // удаление фото
      for (let idx in state.fotoRecipes) {
        if (state.fotoRecipes[idx].id == data.rec_id) {
          state.fotoRecipes[idx].img.splice(data.index, 1);
        }
      }
    },
  },

  actions: {
    async sendLogin(context, data) { // login
      return new Promise((resolve, reject) => {
        let formData = new FormData();
        formData.append("login", data.login);
        formData.append("pass", data.pass);

        context.commit("setShowWait", true);

        axios.post(this.state.domainName + 'api/login.php', formData)
        .then(function (response) {
          // Вошел
          context.commit("setShowWait", false);
          //context.commit('setBusDataLogin', response.data);
          if (response.data.id > 0) {
            context.commit('setUserData', response.data);
            resolve('ok');
            context.dispatch('setCookie', { name: 'id', value: response.data.id , delCookie: false });
          } else {
            resolve(response.data);
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          reject(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
        });
      });
    },
    async changePass(context, data) { // смена пароля из формы забыли пароль
      return new Promise((resolve, reject) => {
        context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/changePass.php?user=' + data)
        .then(function (response) {
          context.commit("setShowWait", false);
        
          if (response.data.errorCode == 0) {
            // Пароль изменен
            let alert = {show: true, caption: "Успешно", text: "Новый пароль выслан Вам на указанный e-mail, возможно письмо попало в папку СПАМ!", type: 3};
            context.commit('setShowAlert', alert);
          } 
          resolve(response.data);
        })
        .catch(function (error) {
          // Проблемы на линии
          reject(error);
          context.commit("setShowWait", false);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          console.log(error);
        });
      });
    },
    async sendCodeReg(context, data) { // выслать код 
      return new Promise((resolve, reject) => {
        context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/sendCode.php?user=' + data)
        .then(function (response) {
          // Код отправлен
          context.commit("setShowWait", false);

          if (response.data.errorCode == 0) {
            let alert = {show: true, caption: "Успешно", text: "Код отправлен на указанный e-mail, возможно письмо попало в папку СПАМ!", type: 3};
            context.commit('setShowAlert', alert);
          } 
          resolve(response.data);
        })
        .catch(function (error) {
          // Проблемы на линии
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          console.log(error);
          context.commit("setShowWait", false);
          reject(error);
        });
      });
    },
    async regUser(context, userData) { // регистрация пользователя
      return new Promise((resolve, reject) => {
        let formData = new FormData();
        formData.append("login", userData.login);
        formData.append("pass", userData.pass);

        context.commit("setShowWait", true);

        axios.post(this.state.domainName + 'api/reg.php', formData)
        .then(function (response) {
          context.commit("setShowWait", false);

          if (response.data.id > 0) {
            // Успешная
            let alert = {show: true, caption: "Успешно", text: "Поздравляем! Вы зарегистрированы!", type: 3};
            context.commit('setShowAlert', alert);
          } else {
            // не успешная
            let alert = {show: true, caption: "Не удалось зарегистрировать пользователя!", text: response.data, type: 1};
            context.commit('setShowAlert', alert);
            console.log(response);
          }
          resolve(response.data);
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
          reject(error);
        });
      });
    },
    setCookie(context, data) { // установить куки
      // если флаг удалить куки, тогда дата действия куки - 30 дней, иначе + 30 дней
      let date = (data.delCookie) ? new Date(Date.now() - 30*86400e3) : new Date(Date.now() + 30*86400e3);
      date = date.toUTCString(); // преобразует дату в строку, используя часовой пояс UTC.
      // установить куки
      document.cookie = encodeURIComponent(data.name) + '=' + encodeURIComponent(data.value) + "; expires=" + date + "; samesite=lax";
    },
    async loginedUserHash(context, id) { // логин по хэш коду и ид
      context.commit("setShowWait", true); // включаем компонент ожидания

      axios.get(this.state.domainName + 'api/login.php?userID=' + id)
      .then(function (response) {
        // залогинен
        context.commit("setShowWait", false); // выключаем компонент ожидания
        // если пользователь получен меням данные 
        if (response.data === null) return;
        if (parseInt(response.data.id) > 0) context.commit('setUserData', response.data);
        //console.log('loginedUserHash');
      })
      .catch(function (error) {
        // Проблемы на линии
        console.log(error);
        let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
        context.commit('setShowAlert', alert);
        context.commit("setShowWait", false);
      });
    },
    async changeDataUser(context, userData) { // смена данных пользователя (ФИО)
      let formData = new FormData();
      formData.append("name", userData.name);
      formData.append("surname", userData.surname);
      formData.append("patronymic", userData.patronymic);
      formData.append("id", userData.id);

      context.commit("setShowWait", true);

      axios.post(this.state.domainName + 'api/saveDataUser.php', formData)
      .then(function (response) {
        // Сохранено
        context.commit("setShowWait", false);
        
        if (response.data.errorCode == 0) {
          let alert = {show: true, caption: "Успешно", text: "Данные сохранены.", type: 3};
          context.commit('setShowAlert', alert);
          context.commit('setNameUser', { userData });
        } else {
          let alert = {show: true, caption: "Не удалось внести изменения.", text: response.data, type: 1};
          context.commit('setShowAlert', alert);
        } 
      })
      .catch(function (error) {
        // Проблемы на линии
        console.log(error);
        let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
        context.commit('setShowAlert', alert);
        context.commit("setShowWait", false);
      });
    },
    async changePassAccount(context, userData) {
      // change pass
      return new Promise((resolve, reject) => {let formData = new FormData();
        formData.append("newPass", userData.newPass);
        formData.append("oldPass", userData.oldPass);
        formData.append("user_id", userData.id);

        context.commit("setShowWait", true);

        axios.post(this.state.domainName + 'api/changePass.php', formData)
        .then(function (response) {
          // Сохранено
          context.commit("setShowWait", false);

          if (response.data.errorCode == 0) {
            let alert = {show: true, caption: "Успешно", text: "Данные сохранены.", type: 3};
            context.commit('setShowAlert', alert);
          }
          resolve(response.data);
        })
        .catch(function (error) {
          // Проблемы на линии
          reject(error);
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
        });
      });
    },
    async loadCategoriesRecipes(context, data) { // загрузка категорий
      context.commit("setShowWait", true);

      axios.get(this.state.domainName + 'api/getCategoriesRecipe.php?parent_id=' + data.parent_id + "&author_id=" + data.author_id)
      .then(function (response) {
        // успешно
        context.commit("setShowWait", false);
        context.commit('setCategoriesRecipes', response.data);
      })
      .catch(function (error) {
        // Проблемы на линии
        console.log(error);
        let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
        context.commit('setShowAlert', alert);
        context.commit("setShowWait", false);
      });
    },
    async addCategorie(context, data) { // добавление новой категории
      return new Promise((resolve, reject) => {context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/addCategoryRecipe.php?id_parent=' + data.parent_id + '&name=' + data.name + '&author_id=' + data.author_id)
        .then(function (response) {
          // Сохранено
          context.commit("setShowWait", false);
          if (response.data.errorCode == 0) {
            resolve(response.data.id);
            context.dispatch('loadCategoriesRecipes', { parent_id: data.parent_id, author_id: data.author_id });
          } else {
            let alert = { show: true, caption: "Не удалось сохранить!", text: response.data.errorText, type: 1 };
            context.commit('setShowAlert', alert);
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          reject(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
        });
      });
    },
    async loadFotorecipes(context, data) { // загрузить данные фоторецепта по фильтрам
      let formData = new FormData(data.form);
      formData.append('parent_id', data.parent_id);
      formData.append('limit', this.state.limit_foto_recipes);
      formData.append('author_id', data.author_id);
      formData.append('page', data.page);
      formData.append('diet', data.diet);

      context.commit("setShowWait", true);

      axios.post(this.state.domainName + 'api/loadFotoRecipes.php', formData)
      .then(function (response) {
        // Загружено
        context.commit("setShowWait", false);
        if (response.data.errorCode > 0) {
          let alert = {show: true, caption: "Не удалось загрузить списки!", text: response.data.errorText, type: 1};
          context.commit('setShowAlert', alert);
        } else {
          context.commit('setDataFotoRecipes', response.data);
        }
      })
      .catch(function (error) {
        // Проблемы на линии
        console.log(error);
        let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
        context.commit('setShowAlert', alert);
        context.commit("setShowWait", false);
      });
    },
    async saveFotoRecipes(context, data) { // сохранить фото рецепт
      return new Promise((resolve, reject) => {
        let formData = new FormData();
        formData.append("name", data.name);
        formData.append("parent_id", data.parent_id);
        formData.append("diet", data.diet);
        formData.append("author_id", data.author_id);
        
        for (let i=0; i < data.images.length; i++) {
          formData.append(`fileFoto[${i}]`, data.images[i]);
        }

        context.commit("setShowWait", true);

        axios.post(this.state.domainName + 'api/saveFotoRecipes.php', formData, 
          /*{ headers: {'Content-Type': 'multipart/form-data'}*/
          { headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function (response) {
          resolve(response);
          // Сохранено
          context.commit("setShowWait", false);

          if (response.data.count_error_img == 0 && response.data.errorText == '') {
            let alert = {show: true, caption: "Успешно", text: "Данные сохранены.", type: 3};
            context.commit('setShowAlert', alert);
          } else {
            let alert = {show: true, caption: "Прошло не совсем гладко", text: response.data, type: 2};
            context.commit('setShowAlert', alert);
            console.log(response.data);
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          reject(error);
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
        });
      });
    },
    async deleteImg(context, data) { // удалить фото из фоторецепта
      return new Promise((resolve, reject) => {
        context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/deleteFoto.php?id=' + data.id)
        .then(function(response) {
          context.commit("setShowWait", false);

          if (response.data.errorCode == 0) {
            context.commit('delFoto', data);
            resolve();
          } else {
            console.log(response.data);
            let alert = {show: true, caption: "Ошибка!", text: response.data.errorText, type: 1};
            context.commit('setShowAlert', alert);
            reject(response.data);
          }
        })
        .catch(function(error) {
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
          reject(error);
        });
      });
    },
    async updateFotoRecipe(context, data) { // изменить фото рецепт
      return new Promise((resolve, reject) => {
        let formData = new FormData();
        formData.append('id', data.id);
        formData.append("name", data.name);
        formData.append("parent_id", data.parent_id);
        formData.append("diet", data.diet);
        formData.append('img', JSON.stringify(data.images));

        context.commit("setShowWait", true);
      
        axios.post(this.state.domainName + 'api/updateFotoRecipes.php', formData, 
          /*{ headers: {'Content-Type': 'multipart/form-data'}*/
          { headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        })
        .then(function(response) {
          context.commit("setShowWait", false);
          if (response.data.errorText === '') {
            let alert = {show: true, caption: "Успешно", text: "Данные сохранены.", type: 3};
            context.commit('setShowAlert', alert);
            resolve();
          } else {
            let alert = {show: true, caption: "Ошибка!", text: response.data.errorText, type: 1};
            context.commit('setShowAlert', alert);
            reject();
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
          reject();
        });
      });
    },
    async addFavoriteFotorecipe(context, data) { // добавить фото рецепт в избранное
      return new Promise((resolve, reject) => {
        context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/addFavoriteFotoRecipe.php?id_user=' + data.id_user + '&id_recipe=' + data.id_recipe)
        .then(function(response) {
          context.commit("setShowWait", false);
          if (response.data.errorCode == 0) {
            resolve();
          } else {
            let alert = {show: true, caption: "Ошибка!", text: response.data.errorText, type: 1};
            context.commit('setShowAlert', alert);
            reject();
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
          reject();
        });
      });
    },
    async rmFavoriteFotorecipe(context, data) { // удалить рецепт из избранного
      return new Promise((resolve, reject) => {
        context.commit("setShowWait", true);

        axios.get(this.state.domainName + 'api/rmFavoriteFotoRecipe.php?id_user=' + data.id_user + '&id_recipe=' + data.id_recipe)
        .then(function(response) {
          context.commit("setShowWait", false);
          if (response.data.errorCode == 0) {
            resolve();
          } else {
            let alert = {show: true, caption: "Ошибка!", text: response.data.errorText, type: 1};
            context.commit('setShowAlert', alert);
            reject();
          }
        })
        .catch(function (error) {
          // Проблемы на линии
          console.log(error);
          let alert = {show: true, caption: "Проблемы на линии!", text: error, type: 1};
          context.commit('setShowAlert', alert);
          context.commit("setShowWait", false);
          reject();
        });
      });
    },
  },

  getters: {
    getShowWait: state => {
      return state.showWait;
    },
    getShowAlert: state => {
      return state.alert;
    },
    getUser: state => {
      return state.user;
    },
    getCategoriesRecipe: state => {
      return state.categoriesRecipes;
    },
    getDomainName: state => {
      return state.domainName;
    },
    getDataFotoRecipes: state => {
      return state.fotoRecipes;
    },
    getBigFotoData: state => {
      return state.bigFoto;
    },
  },


})
