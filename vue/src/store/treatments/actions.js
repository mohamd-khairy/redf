import axios from "@/plugins/axios";

const actions = {
  async getTreatments({ commit }, data) {
    const response = await axios.get("treatments", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const { treatments } = response?.data.data;
    commit("SET_TREATMENTS", treatments);
  },

  async getTreatment({ commit }, treatmentId) {
    try {
      const response = await axios.get(`treatments/${treatmentId}`);
      const treatment = response?.data?.data || {};
      commit("SET_TREATMENT", treatment);
      return true;
    } catch (error) {
      console.log(error);
      return false;
    }
  },
};

export default actions;
