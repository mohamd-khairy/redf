import axios from "@/plugins/axios";

const actions = {
  async getTreatments({ commit }, data) {
    const response = await axios.get("treatments", {
      params: {
        search: data.search,
        pageSize: data.pageSize,
        page: data.pageNumber,
        sortDirection: data.sortDirection,
        sortCoulmn: data.sortColumn,
      },
    });
    const { treatments } = response?.data.data;
    commit("SET_TREATMENTS", treatments);
  },

  async getTreatment({ commit }, treatmentId) {
    try {
      const response = await axios.get(`treatments/${treatmentId}`);
      const treatment = response?.data?.data || {};
      commit("SET_TREATMENT", treatment);
      return true;
    } catch (error) {
      console.log(error);
      return false;
    }
  },
  async saveTreatment({ commit }, data) {
    try {
      const formData = new FormData();
      for (const key in data) {
        if (key === "files") {
          for (let i = 0; i < data.files.length; i++) {
            formData.append("files[]", data.files[i]);
          }
        } else {
          formData.append(key, data[key]);
        }
      }
      console.log(Object.fromEntries(formData));
      const response = await axios.post(`treatment-informations`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    } catch (error) {
      console.log(error);
    }
  },
};

export default actions;
