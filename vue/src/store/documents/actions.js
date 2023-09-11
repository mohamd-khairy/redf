import axios from "@/plugins/axios";

const actions = {
  async getDocuments({ commit }, data) {
    const response = await axios.get("documents", {
      params: {
        search: data.search,
        type: data.type,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const { documents } = response?.data.data;
    commit("SET_DOCUMENTS", documents);
  },
  async getDocument({ commit }, id) {
    const response = await axios.get(`documents/${id}`);
    const document = response?.data.data;

    commit("SET_DOCUMENT", document);
  },
  async deleteDocument({ commit, dispatch }, id) {
    return await axios.delete(`documents/${id}`);
  },
  async deleteAll({ commit }, data) {
    return await axios.get("documents/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value,
      },
    });
  },
  async updateDocument({ state }, formData) {
    const { id } = state?.document ?? {};
    const bodyFormData = new FormData();

    for (const key in formData) {
      let value = formData[key];
      bodyFormData.set(key, value);
    }
    bodyFormData.set("_method", "PUT");
    return await axios.post(`documents/${id}`, bodyFormData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },
  async createDocument({ commit }, formData) {
    const bodyFormData = new FormData();

    for (const key in formData) {
      let value = formData[key];
      bodyFormData.set(key, value);
    }
    return await axios.post(`documents`, bodyFormData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },

  async storeDepartment({ commit }, data) {
    return await axios.post("documents", data);
  },
  async deleteDepartment({ commit, dispatch }, id) {
    return await axios.delete(`documents/${id}`);
    // await dispatch('getUsers')
  },
};

export default actions;
