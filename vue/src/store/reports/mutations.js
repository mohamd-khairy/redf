const mutations = {
  SET_MODELS(state, value) {
    state.models = value;
  },
  SET_REPORT_TYPES(state, value) {
    state.report_types = value;
  },
  SET_SHOW_TYPES(state, value) {
    state.show_types = value;
  },
  SET_TIME_TYPES(state, value) {
    state.time_types = value;
  },
  SET_CHART_DATA(state, value) {
    state.chartData = value;
  },
  SET_CHART_FILTER(state, value) {
    state.chartFilter = value;
  },
  SET_CONFIG(state, value) {
    state.config = value;
  },
  SET_CHART_CONFIG(state, value) {
    state.chartConfig = value;
  },
  SET_DRAFTS(state, value) {
    state.drafts = value;
  },
  SET_SHOW_DRAFT(state, value) {
    state.showDraft = value;
  },
  SET_DRAFT(state, value) {
    state.draft = value;
  },
  SET_DRAFT_TITLE(state, value) {
    state.draftTitle = value;
  },
  SET_PINNED(state, value) {
    state.pinned = value;
  },
  SET_PINNED_REPORTS(state, value) {
    state.pinnedReports = value;
  },
  SET_SHOW_PIN(state, value) {
    state.showPin = value;
  },
  SET_PIN(state, value) {
    state.pin = value;
  },
  SET_PIN_TITLE(state, value) {
    state.pinTitle = value;
  },
  DELETE_PIN(state, value) {
    state.pinnedReports = state.pinnedReports.filter(
      (pinned) => pinned.id !== value
    );
  },
  SET_CARDS_DATA(state, value) {
    state.cards = value;
  },
};

export default mutations;
