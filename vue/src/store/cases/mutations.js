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
  SET_PAGES_VALUES(state, value) {
    state.pagesValues = value;
  },
  SET_SELECTED_FORM(state, value) {
    state.selectedForm = value;
  },
  SET_INPUT_VALUE(state, { tabIndex, inputIndex, newValue }) {
    state.tabs[tabIndex].items[inputIndex].value = newValue;
  },
  SET_CORTS(state, value) {
    state.courts = value;
  },
  SET_CASE_TYPES(state, value) {
    state.caseTypes = value;
  },
  SET_specializations(state, value) {
    state.specializations = value;
  },
  SET_organizations(state, value) {
    state.organizations = value;
  },
  SET_CLAIMANT(state, value) {
    state.claimant = value;
  },
  SET_BRANCHES(state, value) {
    state.branches = value;
  },
};

export default mutations;
