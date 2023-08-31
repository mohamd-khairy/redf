const mutations = {
  SET_TASKS(state, value) {
    state.tasks = value;
  },
  SET_TASK(state, value) {
    state.task = value;
  },
  SET_USERS(state, value) {
    state.users = value;
  },
  SET_DOCUMENTS(state, value) {
    state.documents = value;
  },
  SET_CASES_NAMES(state, value) {
    state.casesNames = value;
  },
  SET_CONSULTATION_NAMES(state, value) {
    state.consultationNames = value;
  },
};

export default mutations;
