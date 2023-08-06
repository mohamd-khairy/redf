import Vue from "vue";

// For full framework
import Vuetify from "vuetify/lib/framework";
// For a-la-carte components - https://vuetifyjs.com/en/customization/a-la-carte/
// import Vuetify from 'vuetify/lib'

import * as directives from "vuetify/lib/directives";
import i18n from "./vue-i18n";
import config from "../configs";

/**
 * Vuetify Plugin
 * Main components library
 *
 * https://vuetifyjs.com/
 *
 */
Vue.use(Vuetify, {
  directives
});

function getThemeConfigs() {
  if (!localStorage.getItem("themeConfigs")) {
    return false;
  }
  let data = JSON.parse(localStorage.getItem("themeConfigs"));
  const configs = {};
  data.forEach(({ key, value }) => {
    const newKey = key
      .split("_")
      .map((word, index) => {
        if (index !== 0) {
          return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        }
        return word.toLowerCase();
      })
      .join("");
    configs[newKey] = value;
  });
}

// getThemeConfigs();

export default new Vuetify({
  rtl: config.theme.isRTL,
  theme: {
    dark: config.theme.globalTheme === "dark",
    options: {
      customProperties: true
    },
    themes: {
      dark: config.theme.dark,
      light: config.theme.light
    }
  },
  lang: {
    current: config.locales.locale,
    t: (key, ...params) => i18n.t(key, params)
  }
});

// export default function createVuetify(configPromise) {
//   return configPromise.then(data => {
//     const { isRTL, globalTheme, dark, light, locale } = data
//     return new Vuetify({
//       rtl: isRTL,
//       theme: {
//         dark: globalTheme === 'dark',
//         options: {
//           customProperties: true
//         },
//         themes: {
//           dark,
//           light
//         }
//       },
//       lang: {
//         current: locale,
//         t: (key, ...params) => i18n.t(key, params)
//       }
//     })
//   })
// }
