<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center pb-3">
      <!-- <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("menu.pipesModel") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div> -->
      <v-spacer></v-spacer>

      <div class="h-38 d-flex">
        <v-btn class="primary--text  v-btn--active mx-1" title="Reload" :to="$route.path" elevation="0">
          <v-icon>mdi-reload</v-icon>
        </v-btn>
        <div class="date-picker position-relative mx-1">
          <i aria-hidden="true" class="v-icon mdi mdi-calendar"></i>
          <date-range-picker v-model="dateRange" direction="rtl" @update="changeDatePicker()">
          </date-range-picker>
        </div>
        <v-switch v-model="listen" hide-details inset :label="$t('menu.live_mode')" class="custom-switch"></v-switch>
      </div>
    </div>

    <!-- <statistics ref="statComponent"></statistics> -->
    <statistics-card ref="statComponent"></statistics-card>

    <v-row class="mt-0 mb-2">
      <v-col cols="12">
        <v-card>
          <v-row dense class="pa-2 align-center">
            <v-col cols="3">
              <v-menu offset-y left>
                <template v-slot:activator="{ on }">
                  <transition name="slide-fade" mode="out-in">
                    <v-btn v-show="selectedEvents.length > 0 && selectedStatus.value === 1
                      " v-on="on">
                      {{ $t("general.actions") }}
                      <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                  </transition>
                </template>
                <v-list dense>
                  <v-list-item @click="doAction((action = 'status'), (value = 1))">
                    <v-list-item-title>{{
                      $t("general.verify")
                    }}</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="doAction((action = 'status'), (value = 0))">
                    <v-list-item-title>{{
                      $t("general.disabled")
                    }}</v-list-item-title>
                  </v-list-item>
                  <v-divider></v-divider>
                  <v-list-item @click="doAction((action = 'delete'), (value = 1))" v-can="'delete-event'">
                    <v-list-item-title>{{
                      $t("general.delete")
                    }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
            <v-col cols="9" class="d-flex text-right align-center">
              <v-select class=" mx-1" :label="$t('general.status')" dense :items="translatedStatusTypes" item-text="text"
                item-value="value" hide-details v-model="selectedStatus" return-object solo>
              </v-select>
              <v-select :disabled="items.length === 0" class=" mx-1" :label="$t('general.types')" dense
                :items="translatedEventTypes" item-text="text" item-value="value" hide-details v-model="selectedType"
                return-object solo></v-select>
              <!--          <v-select-->
              <!--            :disabled="items.length === 0"-->
              <!--            class=" mx-1"-->
              <!--            :label="$t('general.detectionTypes')"-->
              <!--            dense-->
              <!--            :items="translatedDetectionTypes"-->
              <!--            item-text="text"-->
              <!--            item-value="value"-->
              <!--            hide-details-->
              <!--            v-model="selectedDetectionType"-->
              <!--            return-object-->
              <!--            solo-->
              <!--          ></v-select>-->
              <v-text-field :disabled="items.length === 0" v-model="searchQuery" append-icon="mdi-magnify"
                class="flex-grow-1  mx-1" hide-details dense solo clearable :placeholder="$t('general.search')"
                @keyup.enter="searchEvent(searchQuery)"></v-text-field>

              <v-btn :disabled="items.length === 0" class="primary--text  v-btn--active mx-1" title="Gallery"
                @click="openAllCapturedImage" elevation="0">
                <v-icon>mdi-image-multiple</v-icon>
              </v-btn>

              <v-btn :disabled="items.length === 0" class="primary--text  v-btn--active mx-1" title="Notes"
                @click="openNotes" elevation="0">
                <v-icon>mdi-note-text-outline</v-icon>
              </v-btn>

              <v-btn :disabled="items.length === 0" class="primary--text  v-btn--active mx-1" title="Export"
                @click="downloadExcel" elevation="0">
                <v-icon>mdi-download-box-outline</v-icon>
              </v-btn>
              <!--          <v-btn-->
              <!--            class="primary&#45;&#45;text  v-btn&#45;&#45;active mx-1"-->
              <!--            @click="OpenImageSettings"-->
              <!--          >-->
              <!--            <v-icon>mdi-cog-outline</v-icon>-->
              <!--          </v-btn>-->
              <v-btn :loading="isLoading" icon small class="mx-1" @click.prevent="open()">
                <v-icon>mdi-refresh</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-data-table v-model="selectedEvents" show-select :headers="headers" :items="items" :options.sync="options"
            :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :search="searchQuery" class="flex-grow-1 cursor-pointer"
            @click:row="openCapturedImage" :page="page" :pageCount="numberOfPages" :server-items-length="total"
            :footer-props="{
              'items-per-page-options': [5, 10, 15, 25, 50, 100]
            }">
            <template v-slot:item.id="{ item }">
              <div class="font-weight-bold">{{ item.id }}</div>
            </template>

            <template v-slot:item.date="{ item }">
              <div>{{ formatDateTime(item.date).date }}</div>
            </template>

            <template v-slot:item.time="{ item }">
              <div>{{ formatDateTime(item.date).time }}</div>
            </template>

            <!--        <template v-slot:item.default="{ item }">-->
            <!--          <div v-if="item.default === 1">-->
            <!--            {{ $t('general.normal') }}-->
            <!--          </div>-->
            <!--          <div v-if="item.default === 2">-->
            <!--            {{ $t('general.change') }}-->
            <!--          </div>-->
            <!--        </template>-->

            <template v-slot:item.type="{ item }">
              <div class="font-weight-bold d-flex align-center text-no-wrap">
                <div v-for="(type, key) in item.types">
                  <v-chip class="ma-1" v-if="type === 'vehicles'" color="deep-purple" text-color="white" small>
                    {{ $t("general." + type) }}
                  </v-chip>
                  <v-chip class="ma-1" v-else-if="type === 'people'" color="warning" text-color="white" small>
                    {{ $t("general." + type) }}
                  </v-chip>
                  <v-chip class="ma-1" v-else-if="type === 'smoke'" color="success" text-color="white" small>
                    {{ $t("general." + type) }}
                  </v-chip>
                  <v-chip class="ma-1" v-else-if="type === 'fire'" color="red" text-color="white" small>
                    {{ $t("general." + type) }}
                  </v-chip>
                  <v-chip class="ma-1" v-else-if="type === 'leakage'" color="primary" text-color="white" small>
                    {{ $t("general." + type) }}
                  </v-chip>
                  <v-chip class="ma-1" v-else color="green" text-color="white" small>
                    {{ type }}
                  </v-chip>
                </div>
              </div>
            </template>

            <template v-slot:item.status="{ item }">
              <div class="font-weight-bold d-flex align-center text-no-wrap">
                <div v-if="item.status.key === 'pending'" class="warning--text">
                  <v-icon small color="warning">mdi-circle-medium</v-icon>
                  <span>{{ $t("general.pending") }}</span>
                </div>
                <div v-else-if="item.status.key === 'confirmed'" class="success--text">
                  <v-icon small color="success">mdi-circle-medium</v-icon>
                  <span>{{ $t("general.confirmed") }}</span>
                </div>
                <div v-else-if="item.status.key === 'error'" class="red--text">
                  <v-icon small color="red">mdi-circle-medium</v-icon>
                  <span>{{ $t("general.error") }}</span>
                </div>
              </div>
            </template>

            <template v-slot:item.action="{ item }">
              <div class="actions" @click.stop="dialog = true">
                <v-btn color="primary" small @click="changeStatus(item)">
                  {{ $t("general.changeStatus") }}
                </v-btn>
              </div>
            </template>
            <template v-slot:no-data>
              <div class="text-center my-2 primary--text" color="primary">
                <emptyDataSvg></emptyDataSvg>
                <div class="dt-no_data">
                  {{ $t("general.no_data_available") }}
                </div>
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
    <change-status-dialog v-model="dialog" :id="eventId" :eventItem="eventItem"
      :statuses="statuses"></change-status-dialog>

    <!-- Captured Image-->
    <div id="captured_images" class="model-aside" :class="{ open: CapturedImageOpened }">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center border-bottom">
          {{ $t("general.capturedImage") }}
          <!-- <span class="cursor-pointer" @click="closeCapturedImage">
            X
          </span> -->
          <v-btn color="primary" icon @click="closeCapturedImage">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pa-0">
          <div class="captured_images_body ">
            <div class="captured_images_scroll position-relative scroll-y ">
              <v-container fluid>
                <v-row dense>
                  <v-col cols="12">
                    <div class="image-cover position-relative">
                      <div class="event-img">
                        <canvas id="canvas" style="max-width: 100%" class="mx-auto"></canvas>
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="12">
                    <div class=" data-cont">
                      <div>
                        <h6 class="">ID</h6>
                        {{ eventOpened.id }}
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="12" md="6">
                    <div class=" data-cont">
                      <div>
                        <h6 class="">Date</h6>
                        <p>{{ eventOpened.date | formatDate }}</p>
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="12" md="6">
                    <div class=" data-cont">
                      <div>
                        <h6 class="">Time</h6>
                        <p>{{ eventOpened.date | formatTime }}</p>
                      </div>
                    </div>
                  </v-col>

                  <v-col cols="12" md="6">
                    <div class=" data-cont">
                      <div>
                        <h6 class="">Status</h6>

                        <v-chip class="mt-1" v-if="eventStatus === 'pending'" color="orange">
                          <v-icon small color="warning">mdi-circle-medium</v-icon>
                          {{ $t("general.pending") }}
                        </v-chip>
                        <v-chip v-else-if="eventStatus === 'confirmed'" class="mt-1" color="green">
                          <v-icon small color="green lighten-4">mdi-circle-medium</v-icon>
                          {{ $t("general.confirmed") }}
                        </v-chip>
                        <v-chip class="mt-1" v-else color="red">
                          <v-icon small color="red lighten-4">mdi-circle-medium</v-icon>
                          {{ $t("general.error") }}
                        </v-chip>
                      </div>
                    </div>
                  </v-col>
                  <v-col cols="12" md="6">
                    <div class=" data-cont">
                      <div>
                        <h6 class="">Management</h6>
                        <div class="modal-action" @click.stop="dialog = true">
                          <v-btn color="primary" class="mt-1" @click="changeStatus(eventOpened)">
                            {{ $t("general.changeStatus") }}
                          </v-btn>
                        </div>
                      </div>
                    </div>
                  </v-col>
                </v-row>
              </v-container>

              <!--end::Content-->
            </div>
            <!--end::Body-->
          </div>
        </v-card-text>
      </v-card>
    </div>

    <!-- All Captured Images-->
    <div id="all_captured_images" class="model-aside" :class="{ open: AllCapturedImageOpened }">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center border-bottom">
          {{ $t("general.capturedImages") }}
          <v-btn color="primary" icon @click="closeAllCapturedImage">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <div class="captured_images_body">
            <div class="captured_images_scroll position-relative scroll-y ">
              <div class="row ma-0">
                <div class="col-md-6 pa-1" v-for="(item, key) in items">
                  <div class="overlay-wrapper">
                    <img :src="item.image" alt="" />
                    <div class="overlay-layer bg-dark bg-opacity-10">
                      <v-btn color="primary">
                        {{ $t("general.screenshot") }} {{ key + 1 }}</v-btn>
                    </div>
                  </div>
                </div>
              </div>

              <!--end::Content-->
            </div>
            <!--end::Body-->
          </div>
        </v-card-text>
      </v-card>
    </div>

    <!-- Settings-->
    <div id="captured_images_settings" class="model-aside" :class="{ open: ImageSettingsOpened }">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center border-bottom">
          {{ $t("general.settings") }}
          <span class="cursor-pointer" @click="CloseImageSettings">
            X
          </span>
        </v-card-title>
        <v-card-text>
          <div class="captured_images_body ">
            <div class="captured_images_scroll position-relative scroll-y ">
              <div class="row ma-0">
                <div class="col-md-12 px-0 py-3 border-bottom">
                  <h3 class="mb-2">{{ $t("general.controlSetting") }}</h3>
                  <v-text-field value="" type="date" :label="$t('general.selectDate')"></v-text-field>
                  <div class="d-flex align-center">
                    <v-switch class="mr-2" :label="$t('general.notification')"></v-switch>
                    <v-switch :label="$t('general.screenshots')"></v-switch>
                  </div>
                </div>
                <div class="col-md-12 px-0 py-3">
                  <h3 class="mb-2">{{ $t("general.export") }}</h3>
                  <v-text-field value="" type="date" label="Select Date"></v-text-field>
                  <div class="d-flex align-center">
                    <v-switch class="mr-2" :label="$t('general.notification')"></v-switch>
                    <v-switch :label="$t('general.screenshots')"></v-switch>
                  </div>
                  <v-select v-model="defaultSelected" :items="filter" :label="$t('general.selectDate')"></v-select>
                </div>
              </div>

              <!--end::Content-->
            </div>
            <!--end::Body-->
          </div>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script>
import ChangeStatusDialog from "../../../components/pipes/ChangeStatusDialog";
import Statistics from "./statistics";
import StatisticsCard from "./StatisticsCard";
import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";
import moment from "moment";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import Echo from "laravel-echo";
import notificationSound from "@/assets/sounds/notification-sound.wav";
import axios from "@/plugins/axios";

export default {
  components: {
    ChangeStatusDialog,
    DateRangePicker,
    Statistics,
    StatisticsCard,
    emptyDataSvg
  },
  filters: {
    dateCell(value) {
      const dt = new Date(value);
      return dt.getDate();
    },
    date(val) {
      return val ? val.toLocaleString() : "";
    }
  },
  data() {
    return {
      isLoading: false,
      sortBy: "date",
      sortDesc: true,
      statusTypes: ["waiting", "Accepted", "rejected"],
      selectedStatus: { key: "pending", value: 1 },
      selectedType: "",
      selectedDetectionType: "",
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      breadcrumbs: [
        {
          text: this.$t("menu.pipesModel"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.selectLocation"),
          disabled: false,
          href: "/models"
        },
        {
          text: ""
        }
      ],
      dialog: false,
      CapturedImageOpened: false,
      AllCapturedImageOpened: false,
      ImageSettingsOpened: false,
      searchQuery: "",
      selectedEvents: [],
      headers: [
        { text: this.$t("tables.id"), align: "start", value: "id" },
        { text: this.$t("tables.date"), value: "date" },
        { text: this.$t("tables.time"), value: "time" },
        // { text: this.$t('tables.detectionType'), sortable: false, value: 'default' },
        { text: this.$t("tables.type"), sortable: false, value: "type" },
        { text: this.$t("tables.status"), sortable: false, value: "status" },
        {
          text: this.$t("tables.actions"),
          sortable: false,
          align: "center",
          value: "action"
        }
      ],
      items: [],
      cards: [],
      gallery: [],
      filter: [
        this.$t("dashboard.thismonth"),
        this.$t("dashboard.lastweek"),
        this.$t("dashboard.lastmonth")
      ],
      defaultSelected: this.$t("dashboard.thismonth"),
      dateRange: {
        startDate: "",
        endDate: ""
      },
      // dateRange: {
      //   startDate: "2022-11-1",
      //   endDate: "2022-12-1",
      // },
      eventOpened: {},
      eventStatus: "",
      ctx: null,
      img: null,
      scaledWidth: null,
      scaledHeight: null,
      x_image: null,
      y_image: null,
      boxes: [],
      percentageWidth: null,
      percentageHeight: null,
      eventItem: {},
      eventId: 0,
      export: "",
      location: ""
    };
  },
  computed: {
    ...mapState("events", [
      "events",
      "statuses",
      "eventTypes",
      "liveMode",
      "locations",
      "detectionTypes"
    ]),
    ...mapState("app", ["isRTL", "notifications"]),
    backgroundImageInlineStyle() {
      return `background-image: url("${this.eventOpened.image}");`;
    },
    listen: {
      get() {
        return this.liveMode;
      },
      set(val) {
        let { id } = this.$route.params;
        this.setLiveModeState({ locationId: id, liveModeState: val });
      }
    },
    translatedEventTypes() {
      let items = this.eventTypes.map(option => {
        return {
          key: option.key,
          text: this.$t("general." + option.key),
          value: option.value
        };
      });
      return [
        { key: "All", text: this.$t("general.all"), value: "" },
        ...items
      ];
    },
    translatedStatusTypes() {
      let items = this.statuses.map(option => {
        return {
          key: option.key,
          text: this.$t("general." + option.key),
          value: option.value
        };
      });
      return [
        { key: "All", text: this.$t("general.all"), value: "" },
        ...items
      ];
    },
    translatedDetectionTypes() {
      let items = this.detectionTypes.map(option => {
        return {
          key: option.key,
          text: this.$t("general." + option.key),
          value: option.value
        };
      });
      return [
        { key: "All", text: this.$t("general.all"), value: "" },
        ...items
      ];
    }
  },
  created() {
    // this.endDate.setDate(this.endDate.getDate() + 6)
    // this.dateRange = {
    //   startDate , endDate
    // }
    let { id } = this.$route.params;
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.pipesModel")
    });

    this.fetchTypes();
    this.fetchLocations();
    this.getLiveModeState(id);

    this.$root.$on("table_component", () => {
      this.open();
    });
  },
  watch: {
    eventOpened(newValue) {
      if (!newValue) return;
      this.getRegressionBoxes();
    },
    listen(newVal) {
      if (newVal) {
        console.log("initEcho");
        this.initEcho();
      } else {
        console.log("stopEcho");
        this.stopEcho();
      }
    },

    options: {
      handler() {
        this.open();
      }
    },
    deep: true,
    searchQuery() {
      this.open();
    },
    selectedStatus() {
      this.open();
    },
    selectedType() {
      this.open();
    },
    selectedDetectionType() {
      this.open();
    }
  },
  mounted() { },
  unmounted() {
    this.stopEcho();
  },
  methods: {
    ...mapActions("events", [
      "getEvents",
      "getTypes",
      "takeAction",
      "getLiveModeState",
      "setLiveModeState",
      "getLocations"
    ]),
    ...mapActions("app", ["setBreadCrumb"]),

    open() {
      this.isLoading = true;
      const { id } = this.$route.params;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "desc" : "asc";
      let data = {
        eventId: this.$route.query.id ?? "",
        search: this.searchQuery ?? "",
        status: this.selectedStatus.value ?? "",
        type: this.selectedType.value ?? "",
        detectionType: this.selectedDetectionType.value ?? "",
        pageSize: itemsPerPage,
        pageNumber: page,
        locationId: id,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
        startDate: this.dateRange.startDate
          ? moment(this.dateRange.startDate).format("YYYY-MM-DD")
          : "",
        endDate: this.dateRange.endDate
          ? moment(this.dateRange.endDate).format("YYYY-MM-DD")
          : "",
        export: this.export
      };
      this.getEvents(data)
        .then(response => {
          this.isLoading = false;
          if (this.export) {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", "events.xlsx");
            document.body.appendChild(link);
            link.click();
            this.export = "";
          }
          this.items = this.events.data;
          this.total = this.events.total;
          this.numberOfPages = this.events.last_page;
          this.dateRange.startDate = this.events.start_date;
          this.dateRange.endDate = this.events.end_date;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    downloadExcel() {
      this.export = 1;
      this.open();
    },
    openNotes() {
      let { id } = this.$route.params;
      this.$router.push("/models/notes/" + id);
    },
    searchEvent() { },
    changeDatePicker() {
      this.open();
      this.$refs.statComponent.cardsData(this.dateRange);
    },
    doAction(action, value) {
      let selectedIds = [];
      for (let i = 0; i < this.selectedEvents.length; i++) {
        selectedIds.push(this.selectedEvents[i].id);
      }
      var ids = selectedIds.join(",");
      let data = {};
      data = {
        ids: ids,
        action: action,
        value: value
      };
      this.takeAction(data)
        .then(response => {
          this.isLoading = false;
          this.open();
          makeToast("success", response.data.message);
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    changeStatus(item) {
      this.eventItem = item;
      this.eventId = item.id;
    },
    locationName() {
      let locations = {
        1: "riyadh",
        2: "makkah",
        3: "sharqiyah"
      };
      let { id } = this.$route.params;
      let selectedLocation = locations[id] || "not_selected";
      return this.$t(`breadcrumbs.${selectedLocation}`);
    },
    fetchTypes() {
      this.isLoading = true;
      this.getTypes()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchLocations() {
      this.isLoading = true;
      this.getLocations()
        .then(() => {
          this.isLoading = false;
          let { id } = this.$route.params;
          // let selectedLocation = this.locations[id] || 'not_selected';
          let selectedLocation =
            this.locations.find(item => item.id == id) || "not_selected";
          // this.breadcrumbs[2].text = this.$t(`breadcrumbs.${selectedLocation.name}`);
          this.breadcrumbs[2].text = selectedLocation.name;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    openCapturedImage(value) {
      this.eventOpened = value;
      this.eventStatus = value.status.key;
      this.CapturedImageOpened = true;
    },
    closeCapturedImage() {
      this.CapturedImageOpened = false;
    },
    openAllCapturedImage() {
      this.AllCapturedImageOpened = true;
    },
    closeAllCapturedImage() {
      this.AllCapturedImageOpened = false;
    },
    OpenImageSettings() {
      this.ImageSettingsOpened = true;
    },
    CloseImageSettings() {
      this.ImageSettingsOpened = false;
    },
    close() {
      this.dialog = false;
      this.resetCanvas();
    },
    resetCanvas() {
      this.boxes = [];
      this.detection = null;
      this.percentageWidth = null;
      this.percentageHeight = null;
      this.ctx = null;
      this.img = null;
      this.scaledWidth = null;
      this.scaledHeight = null;
      this.x_image = null;
      this.y_image = null;
    },
    resetSelectedDetails() {
      this.selectedDetails = [];
    },
    getRegressionBoxes() {
      this.img = new Image();
      this.img.src = this.eventOpened.image;
      this.img.onload = () => {
        const canvas = document.getElementById(`canvas`);
        this.ctx = canvas.getContext("2d");
        const { naturalWidth, naturalHeight } = this.img;
        const aspectRatio = naturalWidth / naturalHeight;
        let canvasWidth = 450;
        let canvasHeight = 500;
        if (aspectRatio > 1) {
          canvasHeight = 450 / aspectRatio;
        } else {
          canvasWidth = 500 * aspectRatio;
        }
        canvas.width = canvasWidth;
        canvas.height = canvasHeight;
        const scaleFactor = Math.min(
          canvasWidth / naturalWidth,
          canvasHeight / naturalHeight
        );
        this.scaledWidth = naturalWidth * scaleFactor;
        this.scaledHeight = naturalHeight * scaleFactor;
        this.x_image = (canvasWidth - this.scaledWidth) / 2;
        this.y_image = (canvasHeight - this.scaledHeight) / 2;
        this.ctx.drawImage(
          this.img,
          this.x_image,
          this.y_image,
          this.scaledWidth,
          this.scaledHeight
        );
        this.percentageWidth = this.scaledWidth / naturalWidth;
        this.percentageHeight = this.scaledHeight / naturalHeight;
        this.drawBoxes();
      };
    },
    drawBoxes() {
      this.eventOpened.detections.forEach((detail, index) => {
        let color = "";
        if (detail.type === "people") color = "#ffd166";
        else if (detail.type === "vehicles") color = "#673ab7";
        else if (detail.type === "smoke") color = "#06d6a0";
        else if (detail.type === "fire") color = "red";
        else if (detail.type === "leakage") color = "#0096c7";
        else color = "rgba(255, 82, 82, 0.4)";

        let box = detail.box;
        this.boxes.push({
          detail: box,
          color,
          filled: false
        });
        this.drawBox(index);
      });
    },
    drawBox(index) {
      const { detail, filled, color } = this.boxes[index];
      const { top, height, left, width } = this.prepareCoordinates(detail);
      this.ctx.beginPath();
      this.ctx.rect(left, top, width, height);
      if (filled) {
        this.ctx.fillStyle = "rgba(255, 82, 82, 0.4)";
        this.ctx.fill();
      } else {
        this.ctx.strokeStyle = color;
        this.ctx.lineWidth = 1;
        this.ctx.stroke();
      }
    },
    prepareCoordinates(detail) {
      const { bottom, right } = detail;
      let { top, left } = detail;
      let width = right - left;
      let height = bottom - top;
      top = top * this.percentageHeight;
      left = left * this.percentageWidth;
      width = width * this.percentageWidth;
      height = height * this.percentageHeight;
      return {
        top,
        left,
        width,
        height
      };
    },
    onCardHover(index) {
      this.boxes[index].filled = true;
      this.drawBox(index);
    },
    onCardHoverOff(index) {
      this.boxes[index].filled = false;
      this.ctx.clearRect(0, 0, 500, 500);
      this.ctx.drawImage(
        this.img,
        this.x_image,
        this.y_image,
        this.scaledWidth,
        this.scaledHeight
      );
      this.drawBoxes();
      this.ctx.stroke();
    },

    initEcho() {
      console.log(this.$echo);
      this.$echo.connect();
      let { id } = this.$route.params;
      let { id: eventId } = this.$route.query;
      this.$echo.channel(`event.${id}`).listen(`.DetectionEvent`, data => {
        // console.log(data);
        let { eventData } = data;
        // console.log("recieved data");
        // console.log(eventData);

        eventData.types.forEach(type => {
          let elm = this.$el.querySelector(`#${type} .text-h4`);
          let totalElm = this.$el.querySelector(`#total .text-h4`);
          elm && (elm.innerText = +elm.innerText + 1);
          totalElm && (totalElm.innerText = +totalElm.innerText + 1);
        });

        let { page, itemsPerPage } = this.options;
        const direction = this.options.sortDesc[0] ? "desc" : "asc";

        let checkType = true;
        if (this.selectedType) {
          checkType = eventData.types.includes(this.selectedType.key);
        }
        let checkDate = true;
        if (this.dateRange.startDate) {
          checkDate = moment(eventData.date).isAfter(
            moment(this.dateRange.startDate)
          );
        }
        if (this.dateRange.endDate) {
          checkDate = moment(eventData.date).isSameOrBefore(
            moment(this.dateRange.endDate)
          );
        }

        console.log("search", !this.searchQuery.trim());
        console.log(
          "status",
          this.selectedStatus.value === eventData.status.value
        );
        console.log("type", checkType);
        console.log("date", checkDate);
        console.log("eventId", eventId);
        if (
          !this.searchQuery.trim() &&
          direction === "desc" &&
          page === 1 &&
          this.selectedStatus.value === eventData.status.value &&
          checkType &&
          checkDate &&
          !eventId
        ) {
          // add element to beginning
          this.items.unshift(eventData);
          this.total = this.total + 1;
          console.log(this.items);
          // if the length of items is greater than itemsPerPage then remove the last element
          if (this.items.length >= itemsPerPage) {
            this.items.pop();
          }
        }
        // this.open();
      });
      // const pusher = new Pusher("PUSHER_APP_KEY", {
      //   cluster: "PUSHER_APP_CLUSTER"
      // });
      // this.echo = new Echo({
      //   broadcaster: "pusher",
      //   key: "PUSHER_APP_KEY",
      //   cluster: "PUSHER_APP_CLUSTER",
      //   encrypted: true,
      //   pusher
      // });
      // this.echo.channel("events").listen("MyEvent", data => {
      //   this.items.push(data);
      // });
      //       window.Pusher = require("pusher-js");
      // window.Echo = new Echo({
      //   broadcaster: "pusher",
      //   key: process.env.MIX_PUSHER_APP_KEY,
      //   wsHost: window.location.hostname,
      //   wsPort: 6001,
      //   encrypted: false,
      //   disableStats: true,
      //   authEndpoint: `${process.env.apiUrl}/broadcasting/auth`,
      //   auth: { headers: { Authorization: "Bearer " + token } }
      // });
    },
    stopEcho() {
      // if (this.echo) {
      this.$echo.leave("events");
      this.$echo.disconnect();
      //   this.echo = null;
      // }
    },
    formatDateTime(data) {
      let date = moment(data).format("YYYY-MM-DD");
      let time = moment(data).format("hh:mm:ss A");

      return { date, time };
    }
  }
};
</script>

<style lang="scss" scoped>
.event-img {
  height: 300px;
  background-size: 100% 100%;
}

.slide-fade-enter-active {
  transition: all 0.3s ease;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}

.custom-switch {
  margin: 0;
  padding: 0;
  align-items: center;
}

.vue-daterange-picker {
  margin: 0;
}

////
</style>
