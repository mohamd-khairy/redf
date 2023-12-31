const mutations = {
  SET_USERS(state, value) {
    state.users = value;
  },
  SET_TREATMENTS(state, value) {
    state.treatments = value;
  },
  SET_BENEFICIARIES(state, value) {
    state.beneficiaries = value;
  },
  SET_CONSULTATIONS(state, value) {
    state.consultations = value;
  },
  SET_ACTIVITIES(state, value) {
    state.actvs = value;
  },
  SET_USER(state, value) {
    state.user = value;
  },
};

export default mutations;
