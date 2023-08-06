import axios from '@/plugins/axios'

const actions = {
  async getDepartments({ commit }, data) {
    const response = await axios.get("departments", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const { departments } = response?.data.data
    commit('SET_DEPARTMENTS', departments)
  },
  async storeDepartment({ commit }, data) {
    return await axios.post('departments', data)
  },
  async deleteDepartment({ commit, dispatch }, id) {
    return await axios.delete(`departments/${id}`)
    // await dispatch('getUsers')
  },
}

export default actions
