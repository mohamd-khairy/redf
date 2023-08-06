import axios from '@/plugins/axios'

const actions = {
  async getTemplates({ commit }, data) {
    const response = await axios.get("templates", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    const { templates } = response?.data.data
    commit('SET_TEMPLATES', templates)
  },
  async storeTemplate({ commit }, data) {
    return await axios.post('templates', data)
  },
  async deleteTemplate({ commit, dispatch }, id) {
    return await axios.delete(`templates/${id}`)
    // await dispatch('getUsers')
  },
}

export default actions
