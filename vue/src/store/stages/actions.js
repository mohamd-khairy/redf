import axios from "@/plugins/axios";

const actions = {
  async getStages({ commit }) {
    const response = await axios.get("get-all-stages");
    const {stages} = response.data.data
    commit("SET_STAGES", stages);
  },

  async updateTemplateStages({ commit }, data) {
    return await axios.post(`store-form-stages`, data);
  },
};

export default actions;
