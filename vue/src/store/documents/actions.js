import axios from "@/plugins/axios";

const actions = {
  async getDocuments({ commit }, data) {
    const response = await axios.get("documents", {
      params: {
        search: data.search,
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
  async updateDocument({ state }, form) {
    const { id } = state?.document ?? {};
    return await axios.put(`documents/${id}`, form);
  },
  async createDocument({ commit }, data) {
    return await axios.post("documents", data);
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
