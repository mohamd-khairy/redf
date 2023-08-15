import axios from "@/plugins/axios";
import { makeToast } from "@/helpers";

const actions = {
  showToast({ state, commit }, message) {
    if (state.toast.show) commit("hideToast");

    setTimeout(() => {
      commit("showToast", {
        color: "black",
        message,
        timeout: 3000,
      });
    });
  },
  showError({ state, commit }, { message = "Failed!", error }) {
    if (state.toast.show) commit("hideToast");

    setTimeout(() => {
      commit("showToast", {
        color: "error",
        message: message + " " + error.message,
        timeout: 10000,
      });
    });
  },
  showSuccess({ state, commit }, message) {
    if (state.toast.show) commit("hideToast");

    setTimeout(() => {
      commit("showToast", {
        color: "success",
        message,
        timeout: 3000,
      });
    });
  },
  async getNotifications({ commit }) {
    try {
      let response = await axios.get(`notifications`);
      const { notifications, count } = response?.data.data;
      console.log("nextPage", notifications.next_page_url);
      commit("setNotifications", {
        data: notifications.data,
        current_page: notifications.current_page,
        last_page: notifications.last_page,
        count,
        moreItems: notifications.next_page_url ? true : false,
      });
    } catch (error) {
      console.log(error);
    }
  },
  addNotification({ commit }, notification) {
    commit("addNotification", notification);
  },
  async loadMoreNotifications({ commit }, page) {
    let response = await axios.get(`notifications?page=${page}`);
    const { notifications, count } = response?.data.data;
    console.log("nextPage", notifications.next_page_url);
    console.log(notifications);
    commit("setNotifications", {
      data: notifications.data,
      current_page: notifications.current_page,
      last_page: notifications.last_page,
      count,
      moreItems: notifications.next_page_url ? true : false,
    });
  },
  async notificationCounter({ commit }) {
    try {
      let response = await axios.post("notifications", {
        action: "open",
      });
      commit("resetCounter");
    } catch (error) {
      console.log(error);
    }
  },
  async readNotifications({ commit }, ids) {
    try {
      let response = await axios.post("notifications", {
        action: "read",
        ids,
      });
      commit("readNotification", ids);
    } catch (error) {
      console.log(error);
    }
  },

  setThemeConfig({ commit }, response) {
    const data = response?.data.data;
    const configs = {};
    data.forEach(({ key, value, full_url = null }) => {
      const newKey = key
        .split("_")
        .map((word, index) => {
          if (index !== 0) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
          }
          return word.toLowerCase();
        })
        .join("");
      configs[newKey] = full_url ? full_url : value;
    });
    commit("setThemeConfigs", configs);
  },
  async saveThemeConfig({ commit }, configs) {
    try {
      let settings = [];
      for (const key in configs) {
        settings.push({
          key: key,
          value: configs[key],
          group: "theme",
        });
      }
      return await axios.post("settings", {
        settings,
      });
    } catch (error) {
      console.log(error);
    }
  },
  setBreadCrumb({ commit }, { breadcrumbs, pageTitle }) {
    commit("setPageTitle", pageTitle);
    commit("setBreadCrumb", breadcrumbs);
  },
  async getNavTemplate({ commit }) {
    try {
      const response = await axios.get(`get-template-type`);
      const templates = response?.data.data;
      const templateData = templates.map((template) => ({
        title: template.name,
        id: template.id,
      }));

      commit("SET_NAV_TEMPLATE", templateData);
    } catch (error) {
      console.error(error);
    }
  },
};

// const showToast = ({ state, commit }, message) => {
//   if (state.toast.show) commit('hideToast')

//   setTimeout(() => {
//     commit('showToast', {
//       color: 'black',
//       message,
//       timeout: 3000
//     })
//   })
// }

// const showError = ({ state, commit }, { message = 'Failed!', error }) => {
//   if (state.toast.show) commit('hideToast')

//   setTimeout(() => {
//     commit('showToast', {
//       color: 'error',
//       message: message + ' ' + error.message,
//       timeout: 10000
//     })
//   })
// }

// const showSuccess = ({ state, commit }, message) => {
//   if (state.toast.show) commit('hideToast')

//   setTimeout(() => {
//     commit('showToast', {
//       color: 'success',
//       message,
//       timeout: 3000
//     })
//   })
// }

// export default {
//   showToast,
//   showError,
//   showSuccess
// }

export default actions;
