import axios from '@/plugins/axios'

const actions = {
  async getOrganizations({ commit }, data) {
    const response = await axios.get("organizations", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const { organizations } = response?.data.data
    commit('SET_ORGANIZATIONS', organizations)
  },
  async storeDepartment({ commit }, data) {
    return await axios.post('organizations', data)
  },
  async deleteDepartment({ commit, dispatch }, id) {
    return await axios.delete(`organizations/${id}`)
    // await dispatch('getUsers')
  },
}

export default actions
