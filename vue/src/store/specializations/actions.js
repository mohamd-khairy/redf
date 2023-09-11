import axios from "@/plugins/axios";

const actions = {
  async getSpecializations({ commit }, data) {
    const response = await axios.get("specializations", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const { specializations } = response?.data.data;
    commit("SET_SPECIALIZAIONS", specializations);
  },
  async getSpecialization({ commit }, id) {
    const response = await axios.get(`specializations/${id}`);
    const specialization = response?.data.data;
    commit("SET_SPECIALIZAION", specialization);
  },
  async deleteSpecialization({ commit, dispatch }, id) {
    return await axios.delete(`specializations/${id}`);
  },
  async deleteAll({ commit }, data) {
    return await axios.get("specializations/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value,
      },
    });
  },
  async updateSpecialization({ state }, form) {
    const { id } = state?.specialization ?? {};
    return await axios.put(`specializations/${id}`, form);
  },
  async createSpecialization({ commit }, data) {
    return await axios.post("specializations", data);
  },
};

export default actions;
