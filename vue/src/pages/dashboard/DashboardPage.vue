<template>
  <div class="d-flex flex-grow-1 flex-column">
    <!-- <div class="d-flex align-center py-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("menu.dashboard") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->

    <!-- <v-row class="flex-grow-0 mb-2 draft-cont" dense>
      <v-col cols="12 mb-2">
        <v-card v-if="!loading && pinnedCards">
          <v-card-title class="d-flex justify-space-between">
            <div class="d-flex justify-between">
              {{ $t("general.moreCards") }}
            </div>
          </v-card-title>
          <hr class="mb-0" />
          <v-card-text>
            <show-builder-cards
              v-if="!loading && pinnedCards"
              :cards="pinnedCards"
              :outlined="true"
            ></show-builder-cards>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row> -->
    <div v-if="!loading" class="draft-cont">
      <!-- <show-builder-cards
        v-if="!loading && cards"
        :cards="cards"
        :outlined="true"
      ></show-builder-cards> -->
      <v-row>
        <v-col v-for="card in cards" :key="card.id" cols="3">
          <stat-cards :card="card" />
        </v-col>
      </v-row>
      <!-- <v-card v-if="!loading" class="mt-3">
        <v-card-text>
          <calendar @selectedEventId="setEventId" :events="calendarData" />
        </v-card-text>
      </v-card> -->
      <calendar
        @expandEvent="expandEvent"
        v-if="!loading"
        @selectedEventId="setEventId"
        :eventsData="calendarData"
      />
    </div>
    <div id="loading-bg" v-else>
      <!-- <div class="loading-logo">
          <img src="/logo.png" alt="Logo" />
        </div> -->
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>

    <!-- <v-row class="draft-cont custom--cont mt-1">
      <div style="display: contents;" v-for="({ data: pinnedData, details: pinnedDetails }, index) in reports"
        :key="index">
        <v-col v-if="!loading && pinnedDetails.bar" cols="12" lg="6">
          <v-card>
            <div v-if="isLoading" class="d-flex flex-grow-1 align-center justify-center">
              <v-progress-circular indeterminate color="primary"></v-progress-circular>
            </div>
            <div v-else class="d-flex flex-column flex-grow-1">
              <v-card-title class="d-flex justify-space-between">
                <div class="d-flex justify-between">
                  {{ pinnedDetails.bar.title }}
                </div>
              </v-card-title>
              <hr />
    <div class="d-flex flex-column flex-grow-1">
      <column :labels="pinnedData.bar.sites" :series="pinnedData.bar.result"></column>
    </div>
  </div>
  </v-card>
  </v-col>

  <v-col v-if="!loading && pinnedDetails.line" cols="12" lg="6">
    <v-card>
      <div v-if="isLoading" class="d-flex flex-grow-1 align-center justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </div>
      <div v-else class="d-flex flex-column flex-grow-1">
        <v-card-title class="d-flex justify-space-between">
          <div class="d-flex">
            {{ pinnedDetails.line.title }}
          </div>
        </v-card-title>
        <hr />
    <div class="d-flex flex-column flex-grow-1">
      <line-chart :labels="pinnedData.line.sites" :series="pinnedData.line.result"></line-chart>
    </div>
  </div>
  </v-card>
  </v-col>

  <v-col v-if="!loading && pinnedDetails.pie" cols="12" lg="6" v-for="(pie, key) in pinnedData.pie" :key="key">
    <v-card>
      <div v-if="isLoading" class="d-flex flex-grow-1 align-center justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </div>
      <div v-else class="d-flex flex-column flex-grow-1">
        <v-card-title class="d-flex justify-space-between">
          <div class="d-flex">
            {{ pinnedDetails.pie.title }} {{ $t("general." + key) }}
          </div>
        </v-card-title>
        <hr />
    <div class="d-flex flex-column flex-grow-1">
      <pie :labels="pie.name" :series="pie.value"></pie>
    </div>
  </div>
  </v-card>
  </v-col>

  <v-col v-if="!loading && pinnedData.table" cols="12" lg="6">
    <v-card>
      <div v-if="isLoading" class="d-flex flex-grow-1 align-center justify-center">
        <v-progress-circular indeterminate color="primary"></v-progress-circular>
      </div>
      <div v-else class="d-flex flex-column flex-grow-1 h-full">
        <v-card-title class="d-flex justify-space-between">
          {{ pinnedDetails.table.title }}
        </v-card-title>
        <hr />
    <div class="d-flex flex-column flex-grow-1 h-full">
      <table-card :table="pinnedData.table" class="h-full" />
    </div>
  </div>
  </v-card>
  </v-col>
  </div>
  </v-row> -->
    <action-preview-dialog
      :actionInfo="selectedActionInfo?.last_form_request_information || null"
      :dialogVisible="actionPreviewDialog"
      @close-action-dialog="closeActionPrevDialog"
      v-if="actionPreviewDialog"
    />
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

import column from "../../components/dashboard/column";
import lineChart from "../../components/dashboard/lineChart";
import pie from "../../components/dashboard/pie";
import tableCard from "../../components/dashboard/TableCard";
import showBuilderCards from "@/pages/reports/builder/ShowBuilderCards";
import StatCards from "../reports/builder/StatCards.vue";
import Calendar from "../../components/dashboard/Calendar.vue";
import ActionPreviewDialog from "../../components/dashboard/ActionPreviewDialog.vue";

export default {
  name: "DashboardPage",
  components: {
    column,
    lineChart,
    pie,
    tableCard,
    showBuilderCards,
    StatCards,
    Calendar,
    ActionPreviewDialog,
  },
  created() {
    // const { id } = this.$route.params;
    // if (!id) {
    //   window.location.replace("/reports/drafted");
    // }
    this.getCalendar();
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.dashboard"),
    });
    this.fetchConfig();
    this.fetchPinned();
    this.getStatCards();
  },
  data() {
    return {
      reports: {},
      actionPreviewDialog: false,
      loading: true,
      isLoading: false,
      calendarData: [],
      selectedActionInfo: null,
      // pinnedCards: {},

      breadcrumbs: [
        { text: this.$t("menu.dashboard"), disabled: false, href: "#" },
      ],
    };
  },
  computed: {
    ...mapState("reports", {
      config: (state) => state.config,
      cards: (state) => state.cards,
    }),
  },
  methods: {
    ...mapActions("reports", [
      "builderConfigs",
      "getPinnedActive",
      "getStatCards",
    ]),
    ...mapActions("app", ["setBreadCrumb"]),
    expandEvent(ev) {
      console.log(ev);
    },
    fetchConfig() {
      this.isLoading = true;

      this.builderConfigs()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    getCalendar() {
      this.$axios
        .get("/reminders", {
          params: {
            // pageSize: -1,
          },
        })
        .then((res) => {
          // const { calendars } = res.data.data;
          // this.calendarData = calendars.data.map((c) => ({
          //   title: c.details,
          //   start: new Date(c.date),
          //   id: c.id,
          //   actionInfo: c.calendarable,
          // }));
          this.calendarData = res.data.data.map((action, i) =>
            i % 2 === 0
              ? { ...action, expand: true }
              : { ...action, expand: false }
          );
          console.log(this.calendarData);
        });
    },
    closeActionPrevDialog() {
      this.actionPreviewDialog = false;
      this.selectedActionInfo = null;
    },
    setEventId(id) {
      const { actionInfo } = this.calendarData.find((c) => +c.id === +id);
      if (!actionInfo) return;
      this.selectedActionInfo = actionInfo;

      this.actionPreviewDialog = true;
      console.log(actionInfo);
    },
    fetchPinned() {
      this.loading = true;
      this.getPinnedActive()
        .then((response) => {
          this.loading = false;
          const { report } = response?.data.data;
          this.reports = report;

          const cardsArr = report.map((val) => val.data.cards);
          const combined = cardsArr.reduce(
            (acc, curr) => ({ ...acc, ...curr }),
            {}
          );
          this.pinnedCards = combined;
        })
        .catch(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style>
.draft-cont .v-card {
  height: 100%;
}
</style>
