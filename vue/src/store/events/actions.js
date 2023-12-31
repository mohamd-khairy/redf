import axios from '@/plugins/axios'

const actions = {
  async getEvents({ commit }, data) {
    let config = {
      responseType: data.export ? 'blob' : '',
      params: {
        id: data.eventId,
        search: data.search,
        type: data.type,
        status: data.status,
        default: data.detectionType,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
        start_date: data.startDate,
        end_date: data.endDate,
        export: data.export,
      },
    }

    const response = await axios.get(`events/${data.locationId}`, config);
    const events = response?.data.data
    commit('SET_EVENTS', events)
    return response
  },
  async getTypes({commit}) {
    const response = await axios.get(`types`)
    const types = response?.data.data.types
    const statuses = response?.data.data.status
    const detectionTypes = response?.data.data.default_types
    commit('SET_TYPES', types)
    commit('SET_STATUSES', statuses)
    commit('SET_DETECTIONTYPES', detectionTypes)
  },
  async getCards({ commit }, data) {
    const response = await axios.get(`events/${data.locationId}/cards`, {
      params: {
        start_date: data.startDate,
        end_date: data.endDate,
      }
    });
    const cards = response?.data.data;
    commit("SET_CARDS", cards);
  },
  async getEvent({commit}, id) {
    const response = await axios.get(`events/${id}`)
    const event = response?.data.data
    commit('SET_EVENT', event)
  },
  async getLocations({commit}) {
    const response = await axios.get(`location`)
    const locations = response?.data.data
    commit('SET_LOCATIONS', locations)
  },
  async getNotes({commit}, data) {
    const response = await axios.get(`notes/${data.locationId}`, {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn
      }
    });
    const notes = response?.data.data
    commit('SET_NOTES', notes)
  },
  async deleteEvent({commit, dispatch}, id) {
    await axios.delete(`events/${id}`)
    await dispatch('getEvents')
  },
  async takeAction({commit}, data) {
    return await axios.get("events/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value
      }
    });
  },
  async updateEvent({state}, form) {
    const {id} = state?.event ?? {}
    return await axios.post(`events/${id}/update`, form)
  },
  async storeEvent({ commit }, data) {
    return await axios.post("events", data);
  },
  async getLiveModeState({ commit }, locationId) {
    const response = await axios.get(`live-mode/${locationId}`);
    const { live_mode } = response?.data.data;
    commit("SET_LIVE_MODE", live_mode);
  },
  async setLiveModeState({ commit }, { locationId, liveModeState }) {
    const response = await axios.post(`live-mode/${locationId}`, {
      live_mode: liveModeState ? 1 : 0
    });
    const live_mode = response?.data.data;
    commit("SET_LIVE_MODE", liveModeState);
  }
}

export default actions
