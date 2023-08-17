import axios from '@/plugins/axios'

const actions = {
  async getUserType({ commit }, data) {
    return await axios.get("user-type");
  },

  async getUsers({ commit }, data) {
    const response = await axios.get("users", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const { users } = response?.data.data
    commit('SET_USERS', users)
  },
  async getBeneficiaries({ commit }, data) {
    const response = await axios.get("get-users", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const { users } = response?.data.data
    commit('SET_BENEFICIARIES', users)
  },
  async getActivities({ commit }, data) {
    const response = await axios.get("all-logs", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const actvs = response.data.data
    commit('SET_ACTIVITIES', actvs)
  },
  async getUser({ commit }, id) {
    const response = await axios.get(`users/${id}`)
    const user = response?.data.data
    commit('SET_USER', user)
  },
  async deleteUser({ commit, dispatch }, id) {
    return await axios.delete(`users/${id}`)
    // await dispatch('getUsers')
  },
  async deleteAll({ commit }, data) {
    return await axios.get("users/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value
      }
    });
  },
  async editUser({ state }, form) {
    const { id } = state?.user ?? {}
    return await axios.post(`users/${id}`, form)
  },
  async storeUser({ commit }, data) {
    return await axios.post('users', data)
  }
}

export default actions
