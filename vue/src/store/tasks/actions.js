import axios from "@/plugins/axios";

const actions = {
  async getTasks({ commit }, data) {
    const response = await axios.get("tasks");
    const { tasks } = response?.data.data;
    commit("SET_TASKS", tasks);
  },
  async getTask({ commit }, id) {
    const response = await axios.get(`tasks/${id}`);
    const task = response?.data.data;

    commit("SET_TASK", task);
  },
  async deleteTask({ commit, dispatch }, id) {
    return await axios.delete(`tasks/${id}`);
  },
  async deleteAll({ commit }, data) {
    return await axios.get("tasks/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value,
      },
    });
  },
  async updateTask({ state }, formData) {
    const bodyFormData = new FormData();
    for (const key in formData) {
      let value = formData[key];
      bodyFormData.set(key, value);
    }
    bodyFormData.set("_method", "PUT");
    return await axios.post(`tasks/${formData.id}`, bodyFormData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },
  async createTask({ commit }, formData) {
    console.log("form-->", formData);
    const bodyFormData = new FormData();
    for (const key in formData) {
      let value = formData[key];
      if (key == "files") {
        bodyFormData.set("files[]", formData[key]);
        // for (let i = 0; i < formData[key].length; i++) {
        //   console.log("formData[key][i]", formData[key][i]);
        //   bodyFormData.set("files[]", formData[key][i]);
        // }
      } else {
        bodyFormData.set(key, value);
      }
    }
    return await axios.post(`tasks`, bodyFormData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },
  async getUsers({ commit }) {
    const response = await axios.get("user-employee");
    const { users } = response?.data.data;

    commit("SET_USERS", users);
  },
  async getDocuments({ commit }) {
    const response = await axios.get("documents");

    const { documents } = response?.data.data;
    commit("SET_DOCUMENTS", documents.data);
  },
  async getCasesNames({ commit }) {
    const response = await axios.get("get-form-Requests", {
      params: {
        template_id: 1,
        pageSize: -1,
      },
    });
    const resData = response?.data.data;

    const casesNames = resData.map((c) => ({ name: c.name, id: c.id }));
    commit("SET_CASES_NAMES", casesNames);
  },
  async getConsultationNames({ commit }) {
    const response = await axios.get("get-form-Requests", {
      params: {
        template_id: 2,
        pageSize: -1,
      },
    });
    const resData = response?.data.data;

    const consultationNames = resData.map((c) => ({ name: c.name, id: c.id }));
    commit("SET_CONSULTATION_NAMES", consultationNames);
  },
};

export default actions;
