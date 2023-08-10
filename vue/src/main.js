import Vue from "vue";
import App from "./App.vue";
import axios from "@/plugins/axios";
import "@/plugins/socketio";

// VUEX - https://vuex.vuejs.org/
import store from "./store";

// VUE-ROUTER - https://router.vuejs.org/
import router from "./router";

// PLUGINS
// import $ from 'jquery'
import vuetify from "./plugins/vuetify";
import i18n from "./plugins/vue-i18n";
import "./plugins/vue-google-maps";
import "./plugins/vue-shortkey";
import "./plugins/vue-head";
import "./plugins/vue-gtag";
import "./plugins/apexcharts";
import "./plugins/echarts";
import "./plugins/animate";
import "./plugins/clipboard";
import "./plugins/moment";
import "./plugins/ckeditor";
import "./plugins/highmap";
import "./plugins/echo";
import "./plugins/globals";
import '@mdi/font/css/materialdesignicons.min.css'

import { TabPlugin } from '@syncfusion/ej2-vue-navigations';
Vue.use(TabPlugin);

import { DropDownButtonPlugin } from "@syncfusion/ej2-vue-splitbuttons";
import { enableRipple } from '@syncfusion/ej2-base';

enableRipple(true);
Vue.use(DropDownButtonPlugin);


// FILTERS
import "./filters/capitalize";
import "./filters/lowercase";
import "./filters/uppercase";
import "./filters/formatCurrency";
import "./filters/formatDate";
import "./filters/formatTime";

// STYLES
import "bootstrap"
import "bootstrap/dist/css/bootstrap.min.css"

// Main Theme SCSS
import "./assets/scss/theme.scss";

// Animation library - https://animate.style/
import "animate.css/animate.min.css";

// Set this to false to prevent the production tip on Vue startup.
Vue.config.productionTip = false;
import VueToast from "vue-toast-notification";
// Import one of the available themes
//import 'vue-toast-notification/dist/theme-default.css';
import "vue-toast-notification/dist/theme-sugar.css";

Vue.use(VueToast);
// Laravel Echo

/*
import Echo from 'laravel-echo'

window.Pusher = require('pusher-js')

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: 'ABCDEFG',
  //encrypted: true,
  wsHost: '192.168.1.7',
  wsPort: 6001,
  wssport: 6001,
  transports: ['websocket'],
  enabledTransports: ['ws', 'wss'],
  forceTLS: false,
  disableStats: true
})
*/

/*
|---------------------------------------------------------------------
| Main Vue Instance
|---------------------------------------------------------------------
|
| Render the vue application on the <div id="app"></div> in index.html
|
| https://vuejs.org/v2/guide/instance.html
|
*/
// console.log(axios);

// export default new Vue({
//   i18n,
//   vuetify,
//   router,
//   store,
//   async created() {
//     // await store.dispatch('app/getThemeConfig')
//   },
//   render: h => h(App)
// }).$mount("#app");

axios
  .get("general-setting")
  .then(response => {
    // let configData = response.data?.data;
    // let primaryColor = configData.filter(elm => elm.key === "primary_color")[0][
    //   "value"
    // ];
    // console.log(configData);
    // store.commit("app/setThemeConfigs", configData);
    store.dispatch("app/setThemeConfig", response);
    document.title = store?.state?.app?.websiteName || " ";
  })
  .catch(err => {
    console.log(err);
  })
  .finally(_ => {
    new Vue({
      i18n,
      vuetify,
      router,
      store,
      sockets: {
        connect: function () {
          // console.log('socket connected')
        },
        itemAdded: function (data) {
          // console.log(`item added ${data}`)
        }
      },
      async created() {
        // await store.dispatch('app/getThemeConfig')
      },
      render: h => h(App)
    }).$mount("#app");
  });
