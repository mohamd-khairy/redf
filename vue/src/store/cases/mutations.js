const mutations = {
  SET_FORMS(state, value) {
    state.forms = value;
  },
  SET_formRequests(state, value) {
    state.formRequests = value;
  },


  SET_CARDS(state, value) {
    state.cards = value;
  },
  SET_TYPES(state, value) {
    state.eventTypes = value;
  },
  SET_LOCATIONS(state, value) {
    state.locations = value;
  },
  SET_STATUSES(state, value) {
    state.statuses = value;
  },
  SET_DETECTIONTYPES(state, value) {
    state.detectionTypes = value;
  },
  SET_NOTES(state, value) {
    state.notes = value;
  },
  SET_EVENT(state, value) {
    state.event = value;
  },
  SET_LIVE_MODE(state, value) {
    state.liveMode = value;
  },
  SET_PAGES(state, value) {
    state.pages = value;
  },
  SET_SELECTED_FORM(state, value) {
    state.selectedForm = value;
  },
  SET_INPUT_VALUE(state, { tabIndex, inputIndex, newValue }) {
    state.tabs[tabIndex].items[inputIndex].value = newValue;
  },
};

export default mutations;
