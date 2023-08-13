import axios from "@/plugins/axios";

const actions = {
  async getTasks({ commit }, data) {
    const response = await axios.get("tasks", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
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
  async updateTask({ state }, form) {
    const { id } = state?.task ?? {};
    return await axios.put(`tasks/${id}`, form);
  },
  async createTask({ commit }, data) {
    return await axios.post("tasks", data);
  },
  async storeDepartment({ commit }, data) {
    return await axios.post("tasks", data);
  },
  async deleteDepartment({ commit, dispatch }, id) {
    return await axios.delete(`tasks/${id}`);
    // await dispatch('getUsers')
  },
};

export default actions;
