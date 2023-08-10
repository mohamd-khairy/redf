import axios from "@/plugins/axios";

const actions = {
  async getForms({ commit }, data) {
    const response = await axios.get(`get-forms-by-templateId`, {
      params: {
        template_id: data.template_id,
      },
    });
    const forms = response?.data.data;
    commit("SET_FORMS", forms);
  },

  async getEvents({ commit }, data) {
    let config = {
      responseType: data.export ? "blob" : "",
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
    };

    const response = await axios.get(`events/${data.locationId}`, config);
    const events = response?.data.data;
    commit("SET_EVENTS", events);
    return response;
  },
  async getTypes({ commit }) {
    const response = await axios.get(`types`);
    const types = response?.data.data.types;
    const statuses = response?.data.data.status;
    const detectionTypes = response?.data.data.default_types;
    commit("SET_TYPES", types);
    commit("SET_STATUSES", statuses);
    commit("SET_DETECTIONTYPES", detectionTypes);
  },
  async getCards({ commit }, data) {
    const response = await axios.get(`events/${data.locationId}/cards`, {
      params: {
        start_date: data.startDate,
        end_date: data.endDate,
      },
    });
    const cards = response?.data.data;
    commit("SET_CARDS", cards);
  },
  async getEvent({ commit }, id) {
    const response = await axios.get(`events/${id}`);
    const event = response?.data.data;
    commit("SET_EVENT", event);
  },
  async getLocations({ commit }) {
    const response = await axios.get(`location`);
    const locations = response?.data.data;
    commit("SET_LOCATIONS", locations);
  },
  async getNotes({ commit }, data) {
    const response = await axios.get(`notes/${data.locationId}`, {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const notes = response?.data.data;
    commit("SET_NOTES", notes);
  },
  async deleteEvent({ commit, dispatch }, id) {
    await axios.delete(`events/${id}`);
    await dispatch("getEvents");
  },
  async takeAction({ commit }, data) {
    return await axios.get("events/actions", {
      params: {
        ids: data.ids,
        action: data.action,
        value: data.value,
      },
    });
  },
  async updateEvent({ state }, form) {
    const { id } = state?.event ?? {};
    return await axios.post(`events/${id}/update`, form);
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
      live_mode: liveModeState ? 1 : 0,
    });
    const live_mode = response?.data.data;
    commit("SET_LIVE_MODE", liveModeState);
  },
  async getPages({ commit }, formId) {
    const response = await axios.get(`get-form/${formId}`);
    const pages = response?.data.data.pages;
    const selectedFormName = response?.data.data.name;
    const selectedFormId = response?.data.data.id;
    commit("SET_PAGES", pages);
    commit("SET_SELECTED_FORM", {
      name: selectedFormName,
      id: selectedFormId,
    });
  },
  validateFormData({ state }) {
    return state.pages.every((page) => {
      return page.items.every(
        (input) => !input.required || (input.value && input.value.trim() !== "")
      );
    });
  },
  async updatePages({ state }, formId) {
    try {
      const customFormData = {
        id: state.selectedForm.id,
        name: state.selectedForm.name,
        pages: state.pages.map((page) => ({
          id: page.id,
          title: page.title,
          items: page.items
            .filter((input) => input.value)
            .map((input) => {
              return {
                form_page_item_id: input.id,
                value: input.value,
                type: input.type,
              };
            }),
        })),
      };

      const bodyFormData = new FormData();

      for (const key in customFormData) {
        let value = customFormData[key];
        bodyFormData.set(key, JSON.stringify(value));
      }
      const response = await axios.post(`store-form-fill`, bodyFormData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    } catch (error) {
      console.error("Error saving form data:", error);
    }
  },
};

export default actions;
