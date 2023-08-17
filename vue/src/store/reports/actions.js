import axios from "@/plugins/axios";

const actions = {
  /* ----------------------------------------- Builder Report Page --------------------------------------------- */
  async getBuilderOptions({ commit }) {
    const response = await axios.get(`report/builder`);
    const { models, report_types, show_types, time_types } =
      response?.data.data;
    commit("SET_MODELS", models);
    commit("SET_REPORT_TYPES", report_types);
    commit("SET_SHOW_TYPES", show_types);
    commit("SET_TIME_TYPES", time_types);
  },
  async buildReport({ commit }, payload) {
    return await axios.get("report/show", {
      params: {
        report_list: payload.report_list,
        time_type: payload.time_type,
        time_range: payload.time_range,
        model_type: payload.model_type,
        site_ids: payload.site_ids,
        type: payload.type,
        start: payload.start,
        end: payload.end,
        types: payload.types,
      },
    });
    // const { data, filter } = response?.data
    // commit('SET_CHART_DATA', data)
    // commit('SET_CHART_FILTER', filter)
    // localStorage.setItem("builderData", data);
    // localStorage.setItem("builderFilter", filter);
  },
  async builderConfigs({ commit }) {
    const response = await axios.get(`report/config`);
    const { details } = response?.data.data;
    commit("SET_CONFIG", details);
  },
  async updateConfig({ commit }, data) {
    return await axios.post(`report/config/chart-details`, data);
    // const { details } = response?.data.data
    // commit('SET_CONFIG', details)
  },
  async saveDraft({ commit }, payload) {
    return await axios.post("report/draft-this", payload);
  },

  /* ----------------------------------------- Draft Report Page --------------------------------------------- */

  async getDrafted({ commit }, payload) {
    const response = await axios.get("report/draft", {
      params: {
        search: payload.search,
        pageSize: payload.pageSize,
        page: payload.pageNumber,
        sortDirection: payload.sortDirection,
        sortCoulmn: payload.sortColumn,
      },
    });

    const draftTitle = response?.data.data.title;
    const drafts = response?.data.data.drafts;
    commit("SET_DRAFT_TITLE", draftTitle);
    commit("SET_DRAFTS", drafts);
    return response;
  },

  async getDraft({ commit }, id) {
    const response = await axios.get(`report/draft/${id}/draw`);
    const showDraft = response?.data.data;
    commit("SET_SHOW_DRAFT", showDraft);
  },

  async getOneDraft({ commit }, id) {
    const response = await axios.get(`report/draft/${id}`);
    const draft = response?.data.data;
    commit("SET_DRAFT", draft);
  },

  async editDraft({ commit }, data) {
    return await axios.put(`report/draft/${data.id}`, data);
  },

  async deleteDraft({ commit }, id) {
    return await axios.delete(`report/draft/${id}`);
  },

  async deleteAllDrafts({ commit }, data) {
    return await axios.get(`report/draft-actions`, {
      params: {
        ids: data.ids,
      },
    });
  },

  /* ----------------------------------------- Pinned Report Page --------------------------------------------- */

  async getPinned({ commit }, payload) {
    const response = await axios.get("report/pinned", {
      params: {
        search: payload.search,
        pageSize: payload.pageSize,
        page: payload.pageNumber,
        sortDirection: payload.sortDirection,
        sortCoulmn: payload.sortColumn,
      },
    });
    const pinTitle = response?.data.data.title;
    const pinned = response?.data.data.pinneds;
    commit("SET_PIN_TITLE", pinTitle);
    commit("SET_PINNED", pinned);
    return response;
  },

  async updateStatus({ commit }, data) {
    return await axios.post(`report/pinned/${data.id}/status`, {
      status: data.status,
    });
  },

  async deletePin({ commit }, id) {
    let response = null;
    try {
      response = await axios.delete(`report/pinned/${id}`);
      commit("DELETE_PIN", id);
    } catch (error) {
      console.log(error);
    }

    return response;
  },

  async deleteAllPinned({ commit }, data) {
    return await axios.get(`report/pinned-actions`, {
      params: {
        ids: data.ids,
      },
    });
  },
  async getRelatedPinned({ commit }, chartId) {
    return await axios.get(
      `report/pinned/get-related-draft?chart_id=${chartId}`
    );
  },

  async getSavedPinned({ commit }) {
    const response = await axios.get(`report/pinned`);
    const { pinneds } = response?.data.data;
    const pinnedData = pinneds?.data.map((pin) => ({
      title: pin.title,
      id: pin.id,
    }));
    commit("SET_PINNED_REPORTS", pinnedData);
  },

  async savePins({ commit }, payload) {
    const response = await axios.post("report/pinned/add-draft", payload);
    const { pinneds } = response?.data;

    commit("SET_PINNED_REPORTS", pinneds);
  },

  async getOnePinned({ commit }, id) {
    const response = await axios.get(`report/pinned/${id}`);
    const pin = response?.data.data;
    commit("SET_PIN", pin);
  },

  async editPinned({ commit }, data) {
    return await axios.put(`report/pinned/${data.id}`, data);
  },

  /* ----------------------------------------- Dashboard Report Page --------------------------------------------- */

  async getPinnedActive({ commit }) {
    return await axios.get(`report/pinned-active`);
  },
  async getStatCards({ commit }) {
    const response = await axios.get(`/index`);

    const cards = response?.data.data;

    commit("SET_CARDS_DATA", cards);
  },
};

export default actions;
