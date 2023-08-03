<template>
  <div class="d-flex flex-grow-1 flex-column">
    <img :src="image" style="width: fit-content; align-self: center; max-width: 50%;" alt="" />

    <div class="d-flex align-center py-3">
      <!-- <div class="d-flex align-baseline">
        <div class="display-1 d-flex align-center">{{ $t('menu.flights') }}</div><small class="mx-1">({{ $t('dashboard.thismonth') }})</small>
      </div> -->
      <v-spacer></v-spacer>

      <div class="h-38">
        <template>
          <div class="date-picker position-relative">
            <i aria-hidden="true" class="v-icon mdi mdi-calendar"></i>
            <date-range-picker v-model="dateRange" direction="rtl" @update="changeDatePicker()">
            </date-range-picker>
          </div>
        </template>
      </div>
    </div>

    <!--    <cards-skeleton :cardNumber="4" :cardPerRow="4" v-if="isLoading3"></cards-skeleton>-->
    <!--    <v-row v-else class="flex-grow-0 mb-1" dense>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('general.screenshots')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="12"-->
    <!--            :percentage="4.3"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('general.risky')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="32"-->
    <!--            :percentage="24"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('general.drones')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="4"-->
    <!--            :percentage="85"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('general.stations')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="7"-->
    <!--            :percentage="7.9"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--    </v-row>-->

    <v-row class="flex-grow-0 mb-1" dense>
      <v-col cols="12">
        <v-card>
          <!-- users list -->
          <v-row dense class="pa-2 align-center">
            <v-col cols="6">
              <v-menu offset-y left>
                <template v-slot:activator="{ on }">
                  <transition name="slide-fade" mode="out-in">
                    <v-btn v-show="selected.length > 0" v-on="on">
                      {{ $t("general.actions") }}
                      <v-icon right>mdi-menu-down</v-icon>
                    </v-btn>
                  </transition>
                </template>
                <v-list dense>
                  <!--                  <v-list-item >-->
                  <!--                    <v-list-item-title>{{ $t('general.verify') }}</v-list-item-title>-->
                  <!--                  </v-list-item>-->
                  <!--                  <v-list-item >-->
                  <!--                    <v-list-item-title>{{ $t('general.disabled') }}</v-list-item-title>-->
                  <!--                  </v-list-item>-->
                  <!--                  <v-divider></v-divider>-->
                  <v-list-item @click="deleteAllFlights()">
                    <v-list-item-title>{{
                      $t("general.delete")
                    }}</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>
            <v-col cols="6" class="d-flex text-right align-center">
              <v-text-field v-model="searchQuery" append-icon="mdi-magnify" class="flex-grow-1 mr-md-2 mx-1" hide-details
                dense solo clearable :placeholder="$t('general.search')"></v-text-field>
              <v-btn :loading="isLoading" icon small class="mx-1" @click>
                <v-icon>mdi-refresh</v-icon>
              </v-btn>
            </v-col>
          </v-row>

          <v-data-table v-model="selected" show-select :headers="headers" :items="items" :search="searchQuery"
            class="flex-grow-1 cursor-pointer" :options.sync="options" :loading="isLoading" :page="page"
            :pageCount="numberOfPages" :server-items-length="total">
            <template v-slot:item.id="{ item }">
              <div class="font-weight-bold">{{ item.id }}</div>
            </template>

            <template v-slot:item.date="{ item }">
              <div>{{ item.date | formatDate }}</div>
            </template>

            <template v-slot:item.time="{ item }">
              <div>{{ item.date | formatTime }}</div>
            </template>

            <template v-slot:item.location="{ item }">
              <div>{{ item.location.name }}</div>
            </template>

            <template v-slot:item.action="{ item }">
              <div class="actions">
                <v-btn text @click="open(item)" to="/flights/show" target="_blank">
                  <span class="primary--text">{{ $t("tables.view") }}</span>
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
  </div>
</template>

<script>
import TrackCard from "../../components/dashboard/TrackCard";
import CardsSkeleton from "../../components/common/CardsSkeleton";

import DateRangePicker from "vue2-daterange-picker";
import "vue2-daterange-picker/dist/vue2-daterange-picker.css";
import { mapActions, mapState } from "vuex";
import moment from "moment";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  sockets: {
    connect: function () {
      console.log("socket connected");
    },
    itemAdded: function (data) {
      console.log(`item added ${data}`);
      this.image = data.image;
    }
  },

  components: {
    TrackCard,
    DateRangePicker,
    CardsSkeleton,
    emptyDataSvg
  },
  filters: {
    dateCell(value) {
      const dt = new Date(value);

      return dt.getDate();
    },
    date(val) {
      return val ? val.toLocaleString() : "filter";
    }
  },
  data() {
    return {
      image: "",
      loadingInterval: null,

      isLoading: false,
      isLoading1: true,
      isLoading2: true,
      isLoading3: true,
      isLoading4: true,

      ordersSeries: [
        {
          name: "Orders",
          data: [
            ["2020-02-02", 34],
            ["2020-02-03", 43],
            ["2020-02-04", 40],
            ["2020-02-05", 43]
          ]
        }
      ],

      selected: [],
      searchQuery: "",
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      headers: [
        { text: this.$t("tables.id"), align: "start", value: "id" },
        { text: this.$t("tables.date"), value: "date" },
        { text: this.$t("tables.time"), value: "time" },
        {
          text: this.$t("tables.location"),
          value: "location",
          sortable: false
        },
        {
          text: this.$t("tables.actions"),
          sortable: false,
          align: "center",
          value: "action"
        }
      ],
      items: [],

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
      breadcrumbs: [
        {
          text: this.$t("menu.flights"),
          disabled: false,
          href: "#"
        },

        {
          text: this.$t("menu.flights_table")
        }
      ]
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.flights")
    });
  },
  watch: {
    selected(val) { },
    options: {
      handler() {
        this.open();
      }
    },
    deep: true,
    searchQuery() {
      this.open();
    }
  },
  computed: {
    ...mapState("flights", ["flights"])
  },
  mounted() {
    let count = 0;

    // DEMO delay for loading graphics
    this.loadingInterval = setInterval(() => {
      this[`isLoading${count++}`] = false;
      if (count === 4) this.clear();
    }, 400);
  },
  // beforeDestroy() {
  //   this.clear()
  // },
  methods: {
    ...mapActions("flights", ["getFlights", "deleteAll"]),
    ...mapActions("app", ["setBreadCrumb"]),

    clear() {
      clearInterval(this.loadingInterval);
    },
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";
      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
        startDate: this.dateRange.startDate
          ? moment(this.dateRange.startDate).format("YYYY-MM-DD")
          : "",
        endDate: this.dateRange.endDate
          ? moment(this.dateRange.endDate).format("YYYY-MM-DD")
          : ""
      };
      this.getFlights(data)
        .then(() => {
          this.isLoading = false;
          this.items = this.flights.data;
          this.total = this.flights.total;
          if (itemsPerPage != -1) {
            this.numberOfPages = this.flights.last_page;
          } else {
            this.numberOfPages = 1;
          }
          this.dateRange.startDate = this.flights.start_date;
          this.dateRange.endDate = this.flights.end_date;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    changeDatePicker() {
      this.open();
      // this.$refs.statComponent.cardsData(this.dateRange);
    },
    async deleteAllFlights() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");
      if (isConfirmed) {
        if (this.selected.length) {
          this.selected.forEach(item => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1
        };
        this.isLoading = true;
        this.deleteAll(data)
          .then(response => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    }
  }
};
</script>
<style>
.vue-daterange-picker {
  margin: 0;
}
</style>
