import axios from "@/plugins/axios";
import router from "@/router";
import Vue from "vue";
import store from "@/store";
// import $gates from "@/plugins/vue-gates";

export default {
  async login({ commit }, data) {
    try {
      const response = await axios.post("login", data);
      const { token, user } = response?.data?.data ?? {};
      commit("SET_TOKEN", token);
      commit("SET_USER", user);
      commit("SET_PERMISSIONS", user.permissions);
      localStorage.setItem("token", token);
      localStorage.setItem("user_permissions", user.permissions);
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      router.push("/");
    } catch (error) {
      throw error;
    }
  },

  async getUser({ commit }) {
    try {
      const response = await axios.get("user");
      const user = response?.data?.data ?? {};

      commit("SET_USER", user);
    } catch (error) {
      console.log(error);
    }
  },
  async editUser({ state }, form) {
    const { id } = state?.user ?? {}
    return await axios.post(`users/${id}`, form)
  },
  async logout({ commit }) {
    try {
      await axios.get("logout");
      commit("SET_TOKEN", "");
      commit("SET_USER", {});
      commit("SET_PERMISSIONS", []);
      localStorage.removeItem("token")
      router.push({ name: "auth-signin" });
    } catch (error) { }
  },
  async editProfile({ commit }, data) {
    const response = await axios.post("updateProfile", data);
    const { user } = response?.data ?? {};
    commit("SET_USER", user);
  }
};
