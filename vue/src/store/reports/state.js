const state = {
  models: [],
  report_types: [],
  show_types: [],
  time_types: [
    {
      key: "",
      value: "",
    },
    {
      dates: [],
      key: "",
      value: "",
    },
  ],
  chartData: localStorage.getItem("builderData") ?? [],
  chartFilter: localStorage.getItem("builderFilter") ?? [],
  config: {},
  chartConfig: {},
  drafts: [],
  draftTitle: "",
  showDraft: {},
  draft: {},
  pinned: [],
  pinTitle: "",
  showPin: {},
  pin: {},
  cards: [],
  pinnedReports: [],
};

export default state;
