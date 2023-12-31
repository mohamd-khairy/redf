import axios from '@/plugins/axios'

const actions = {
  async getFlights({ commit },data) {
    const response = await axios.get("flight", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
        start_date: data.startDate,
        end_date: data.endDate,
      }
    });
    const flights = response?.data.data
    commit('SET_FLIGHTS', flights)
  },
  // async getUser({ commit }, id) {
  //   const response = await axios.get(`users/${id}`)
  //   const user = response?.data.data
  //   commit('SET_USER', user)
  // },
  // async deleteUser({ commit, dispatch }, id) {
  //   return await axios.delete(`users/${id}`)
  //   // await dispatch('getUsers')
  // },
  async deleteAll({commit}, data) {
    return await axios.get("flight-actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value
      }
    });
  },
  // async editUser({ state }, form) {
  //   const { id } = state?.user ?? {}
  //   return await axios.post(`users/${id}`, form)
  // },
  // async storeUser({ commit }, data) {
  //   return await axios.post('users', data)
  // }
}

export default actions
