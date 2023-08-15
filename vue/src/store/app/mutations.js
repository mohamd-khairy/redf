import Vuetify from "../../plugins/vuetify";

export default {
  /**
   * Main Toast
   */
  showToast: (state, toast) => {
    const { color, timeout, message } = toast;

    state.toast = {
      message,
      color,
      timeout,
      show: true,
    };
  },
  hideToast: (state) => {
    state.toast.show = false;
  },
  resetToast: (state) => {
    state.toast = {
      show: false,
      color: "black",
      message: "",
      timeout: 3000,
    };
  },

  /**
   * Theme and Layout
   */

  setThemeConfigs: (state, configs) => {
    for (let config in configs) {
      state[config] = configs[config] ? configs[config] : state[config];
    }

    localStorage.setItem("themeConfigs", JSON.stringify(state));
    state.isToolbarDetached = configs.toolbarStyle === "1" ? true : false;
    Vuetify.framework.theme.dark = state.globalTheme === "dark";
    Vuetify.framework.rtl = state.isRTL;
    Vuetify.framework.theme.themes.dark.primary = state.primaryColor;
    Vuetify.framework.theme.themes.light.primary = state.primaryColor;
  },

  setGlobalTheme: (state, theme) => {
    Vuetify.framework.theme.dark = theme === "dark";
    state.globalTheme = theme;
  },
  setRTL: (state, isRTL) => {
    Vuetify.framework.rtl = isRTL;
    state.isRTL = isRTL;
  },
  setContentBoxed: (state, isBoxed) => {
    state.isContentBoxed = isBoxed;
  },
  setMenuTheme: (state, theme) => {
    state.menuTheme = theme;
  },
  setPrimaryColor: (state, color) => {
    state.primaryColor = color;
    Vuetify.framework.theme.themes.dark.primary = color;
    Vuetify.framework.theme.themes.light.primary = color;
  },
  setToolbarTheme: (state, theme) => {
    state.toolbarTheme = theme;
  },
  setTimeZone: (state, zone) => {
    state.time.zone = zone;
  },
  setTimeFormat: (state, format) => {
    state.time.format = format;
  },
  setCurrency: (state, currency) => {
    state.currency = currency;
  },
  setToolbarDetached: (state, isDetached) => {
    state.isToolbarDetached = isDetached;
  },
  setNotifications: (state, value) => {
    let { data, current_page, last_page, count, moreItems } = value;
    state.notifications.data = [...state.notifications.data, ...data];
    state.notifications.current_page = current_page;
    state.notifications.last_page = last_page;
    state.notifications.count = count;
    state.notifications.moreItems = moreItems;
  },
  addNotification: (state, value) => {
    state.notifications.count = state.notifications.count + 1;
    state.notifications.data = [value, ...state.notifications.data];
  },

  resetCounter: (state) => {
    state.notifications.count = 0;
  },

  readNotification: (state, ids) => {
    state.notifications.data.forEach((notification) => {
      if (ids.includes(notification.id)) {
        notification.read_at = new Date().toISOString();
      }
    });
  },
  setPageTitle: (state, title) => {
    state.pageTitle = title;
  },
  setBreadCrumb: (state, breadcrumbs) => {
    state.breadcrumbs = breadcrumbs;
  },
  SET_NAV_TEMPLATE: (state, data) => {
    state.navTemplates = data;
  },
};
