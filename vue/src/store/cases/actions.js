import axios from "@/plugins/axios";

const actions = {
  async getForms({ commit }, id) {
    const response = await axios.get(`get-forms`, {
      params: {
        template_id: id,
      },
    });
    const forms = response?.data.data;
    commit("SET_FORMS", forms);
  },

  async getFormRequests({ commit }, data) {
    const response = await axios.get(`get-form-Requests`, {
      params: {
        template_id: data.template_id,
        form_type: data.form_type,
        type: "case",
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const formRequests = response?.data.data;
    commit("SET_formRequests", formRequests);
  },
  async getCases({ commit }, data) {
    const response = await axios.get(`get-form-Requests`, {
      params: {
        template_id: data.template_id,
        form_type: data.form_type,
        type: "case",
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const cases = response?.data.data;
    commit("SET_CASES", cases);
  },
  async deleteForm({ commit }, id) {
    return await axios.delete(`delete-form-Requests/${id}`);
  },

  async userDepartment({ commit }, data) {
    return await axios.get(`user-department`, {
      params: {
        user_id: data.user_id,
      },
    });
  },

  async assignRequest({ commit }, data) {
    console.log("data-->", data);
    return await axios.post(`assign-request`, data);
  },

  async createUser({ commit }, form) {
    return await axios.post(`store-userInfo`, form);
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
  async getPagesValues({ commit }, formId) {
    const response = await axios.get(`get-form-Requests/${formId}`);

    let pageTabs = response?.data.data.form.pages;
    const inputValues = response?.data.data.form_page_item_fill;
    const selectedFormName = response?.data.data.form.name;
    const selectedFormId = response?.data.data.form.id;

    const inputValuesObj = {};
    inputValues.forEach(({ form_page_item_id, value }) => {
      inputValuesObj[form_page_item_id] = value;
    });
    for (const obj of pageTabs) {
      for (const item of obj.items) {
        item.value = inputValuesObj[item.id];
        if (item.type === "file") {
          item.preview = inputValuesObj[item.id];
        }
      }
    }

    commit("SET_PAGES_VALUES", pageTabs);
    commit("SET_SELECTED_FORM", {
      name: selectedFormName,
      id: selectedFormId,
    });
    return response?.data.data;
  },
  validateFormData({ state }) {
    return state.pages.every((page) => {
      return page.items.every(
        (input) => !input.required || (input.value && input.value.trim() !== "")
      );
    });
  },
  async saveRequestSide({ state }, data) {
    try {
      const result = await axios.post(`form-request-side`, data);
      if (result) {
        return true;
      }
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async saveFormInformation({ state }, data) {
    try {
      await axios.post(`form-request-information`, data);
      return true;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async savePages(
    { state },
    {
      caseName,
      caseNumber,
      case_id,
      branch_id,
      caseDate,
      type,
      case_type,
      specialization_id,
      organization_id,
      file,
      form_type,
      benefire_id,
    }
  ) {
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
      if (caseName) {
        bodyFormData.set("case_name", caseName);
      }
      if (caseNumber) {
        bodyFormData.set("case_number", caseNumber);
      }
      if (caseDate) {
        bodyFormData.set("case_date", caseDate);
      }
      if (case_id) {
        bodyFormData.set("case_id", case_id);
      }
      if (branch_id) {
        bodyFormData.set("branche_id", branch_id);
      }
      if (type) {
        bodyFormData.set("type", type);
      }
      if (case_type) {
        bodyFormData.set("case_type", case_type);
      }
      if (specialization_id) {
        bodyFormData.set("specialization_id", specialization_id);
      }
      if (organization_id) {
        bodyFormData.set("organization_id", organization_id);
      }
      if (file) {
        bodyFormData.set("file", file);
      }
      if (form_type) {
        bodyFormData.set("form_type", form_type);
      }
      if (benefire_id) {
        bodyFormData.set("benefire_id", benefire_id);
      }
      const result = await axios.post(`store-form-fill`, bodyFormData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      return result;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async updatePages(
    { state },
    {
      caseName,
      caseNumber,
      case_id,
      formId,
      branch_id,
      caseDate,
      type,
      case_type,
      specialization_id,
      organization_id,
      file
    }
  ) {
    try {
      const customFormData = {
        id: state.selectedForm.id,

        name: state.selectedForm.name,
        pages: state.pagesValues.map((page) => ({
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
        bodyFormData.set("_method", "PUT");
      }
      if (caseName) {
        bodyFormData.set("case_name", caseName);
      }
      if (caseNumber) {
        bodyFormData.set("case_number", caseNumber);
      }
      if (caseDate) {
        bodyFormData.set("case_date", caseDate);
      }
      if (case_id) {
        bodyFormData.set("case_id", case_id);
      }
      if (branch_id) {
        bodyFormData.set("branche_id", branch_id);
      }
      if (type) {
        bodyFormData.set("type", type);
      }
      if (case_type) {
        bodyFormData.set("case_type", case_type);
      }
      if (specialization_id) {
        bodyFormData.set("specialization_id", specialization_id);
      }
      if (organization_id) {
        bodyFormData.set("organization_id", organization_id);
      }
      if (file) {
        bodyFormData.set("file", file);
      }
      const response = await axios.post(
        `update-form-fill/${formId}`,
        bodyFormData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      return true;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async updateBackPages(
    { state },
    { caseName, caseNumber, formId, branch_id, caseDate, type,case_id }
  ) {
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
        bodyFormData.set("_method", "PUT");
      }
      if (caseName) {
        bodyFormData.set("case_name", caseName);
      }
      if (caseNumber) {
        bodyFormData.set("case_number", caseNumber);
      }
      if (caseDate) {
        bodyFormData.set("case_date", caseDate);
      }
      if (branch_id) {
        bodyFormData.set("branche_id", branch_id);
      }
      if (type) {
        bodyFormData.set("type", type);
      }
      if (case_id) {
        bodyFormData.set("case_id", case_id);
      }
      const response = await axios.post(
        `update-form-fill/${formId}`,
        bodyFormData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      return true;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async saveRelatedPages({ state }, { case_id }) {
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
      bodyFormData.set("case_id", case_id);
      const result = await axios.post(
        `store-related-case-form-fill`,
        bodyFormData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      return result;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async updateRelatedPages({ state }, { formId }) {
    try {
      const customFormData = {
        id: state.selectedForm.id,

        name: state.selectedForm.name,
        pages: state.pagesValues.map((page) => ({
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
        bodyFormData.set("_method", "PUT");
      }
      const response = await axios.post(
        `update-related-case-form-fill/${formId}`,
        bodyFormData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      return true;
    } catch (error) {
      console.error("Error saving form data:", error);
      return false;
    }
  },
  async getCourts({ commit }) {
    const response = await axios.get(`lookup`);

    const { court_types } = response?.data.data;
    const { case_types } = response?.data.data;
    const { specialization } = response?.data.data;
    const { branches } = response?.data.data;
    const { organizations } = response?.data.data;
    commit("SET_CORTS", court_types);
    commit("SET_SESSION_PLACES", branches);
    commit("SET_CASE_TYPES", case_types);
    commit("SET_specializations", specialization);
    commit("SET_organizations", organizations);
  },
  async retrieveClaimant({ commit }, { form_request_id }) {
    const response = await axios.get(`retrieve-claimant`, {
      params: {
        form_request_id,
      },
    });
    const claimant = response?.data?.data || [];

    commit("SET_CLAIMANT", claimant);
  },
  async getCasePreview({ commit }, id) {
    const response = await axios.get(`action-preview/${id}`);
    const { formRequestActions } = response?.data?.data;
    return formRequestActions;
  },
  async changeStatus({ commit }, { status, formId }) {
    try {
      const response = await axios.post(`change-status`, {
        status: status,
        form_request_id: formId,
      });
      if (response) {
        return true;
      }
    } catch (error) {
      return false;
    }
  },
  async updateFormRequestInfo({ commit }, data) {
    return await axios.put(`update-form-request-information/${data.id}`, data);
  },

  async getApplications({ commit }, data) {
    const response = await axios.get(`get-all-applications`,{
      params: {
        form_id: data.id,
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const columns = response?.data.data.data;
    commit("SET_COLUMNS", columns);
    return response;
  },

};

export default actions;
