import configs from "../../configs";
import actions from "./actions";
import mutations from "./mutations";

const { product, time, theme, currencies } = configs;

const { globalTheme, menuTheme } = theme;
const { currency, availableCurrencies } = currencies;

const {
  primaryColor = theme.primaryColor,
  isRTL = theme.isRTL,
  isContentBoxed = theme.isContentBoxed,
  isToolbarDetached = theme.isToolbarDetached,
  toolbarTheme = theme.toolbarTheme,
} = localStorage.getItem("themeConfigs")
  ? JSON.parse(localStorage.getItem("themeConfigs"))
  : {};
// state initial values
const state = {
  pageTitle: "",
  breadcrumbs: [],
  product,
  navTemplates: [],

  time,

  // currency
  currency,
  availableCurrencies,

  // themes and layout configurations
  globalTheme,
  menuTheme,
  toolbarTheme,
  isToolbarDetached,
  isContentBoxed,
  isRTL,
  primaryColor,
  notifications: {
    data: [],
    current_page: 1,
    last_page: 0,
    count: 0,
    moreItems: false,
  },

  // App.vue main toast
  toast: {
    show: false,
    color: "black",
    message: "",
    timeout: 3000,
  },
};

export default {
  namespaced: true,
  state,
  actions,
  mutations,
};
