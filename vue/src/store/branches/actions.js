import axios from "@/plugins/axios";

const actions = {
  async getBranches({ commit }, data) {
    const response = await axios.get("branches", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const { branches } = response?.data.data;
    commit("SET_BRANCHES", branches);
  },
  async getBranch({ commit }, id) {
    const response = await axios.get(`branches/${id}`);
    const branch = response?.data.data;
    commit("SET_BRANCH", branch);
  },
  async deleteBranch({ commit, dispatch }, id) {
    return await axios.delete(`branches/${id}`);
  },
  async deleteAll({ commit }, data) {
    return await axios.get("branches/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value,
      },
    });
  },
  async updateBranch({ state }, form) {
    const { id } = state?.branch ?? {};
    return await axios.put(`branches/${id}`, form);
  },
  async createBranch({ commit }, data) {
    return await axios.post("branches", data);
  },
};

export default actions;
