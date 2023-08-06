import axios from '@/plugins/axios'

const actions = {
<<<<<<< HEAD
  async getOrganizations({ commit },data) {
=======
  async getOrganizations({ commit }, data) {
>>>>>>> 067342234c9ecdbc758a74d5845dc50b5d1d0529
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
  async getOrganization({ commit }, id) {
    const response = await axios.get(`organizations/${id}`)
    const organization = response?.data.data
    commit('SET_ORGANIZATION', organization)
  },
  async deleteOrganization({ commit, dispatch }, id) {
    return await axios.delete(`organizations/${id}`)
    
  },
  async deleteAll({commit}, data) {
    return await axios.get("organizations/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value
      }
    });
  },
  async updateOrganization({ state }, form) {
    // const { id } = state?.organization ?? {}
    
    return await axios.post(`organizations/${id}`, form)
  },
  async createOrganization({ commit }, data) {
    return await axios.post('organizations', data)
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
