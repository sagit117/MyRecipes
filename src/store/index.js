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
  },

  mutations: {
    setShowWait(state, data) {
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
    }
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
        if (response !== null) return;
        if (parseInt(response.data.id) > 0) context.commit('setUserData', response.data);
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
    }
  },


})
