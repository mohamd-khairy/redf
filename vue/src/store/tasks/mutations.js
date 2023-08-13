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
};

export default mutations;
