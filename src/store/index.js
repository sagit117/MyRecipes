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
      state.user.id = data.id;
      state.user.post = data.post;
      state.user.name = (data.name !== null) ? data.name : '';
      state.user.surname = (data.surname !== null) ? data.surname : '';
      state.user.patronymic = (data.patronymic !== null) ? data.patronymic : '';
      state.user.datereg = data.datereg;
      state.user.rule = data.rule;
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
