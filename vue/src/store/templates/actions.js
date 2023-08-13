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
  async getTemplatesList({ commit }, data) {
    const response = await axios.get("all-form", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      }
    });
    commit('SET_TEMPLATES_LIST', response.data.data)
  },
  async storeForm({ commit }, data) {
    return await axios.post('create-form', data)
  },
  async storeTemplate({ commit }, data) {
    return await axios.post('templates', data)
  },
  async updateTemplate({ commit }, data) {
    let identifier = data.get("id");
    return await axios.post(`templates/${identifier}`, data)
  },
  async updateForm({ commit }, data) {
    let identifier = data.get("id");
    return await axios.post(`update-form-basic/${identifier}`, data)
  },
  async deleteTemplate({ commit, dispatch }, id) {
    return await axios.delete(`templates/${id}`)
    // await dispatch('getUsers')
  },
  async deleteForm({ commit, dispatch }, id) {
    return await axios.delete(`delete-form/${id}`)
    // await dispatch('getUsers')
  },
}

export default actions
