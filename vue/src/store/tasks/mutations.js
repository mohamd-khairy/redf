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
  SET_FORMS(state, value){
    state.forms = value;
  }
};

export default mutations;
