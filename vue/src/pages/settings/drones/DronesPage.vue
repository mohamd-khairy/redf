<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('drones.dronesSettings') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->
    <!--    <v-row class="flex-grow-0 mb-1" dense>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('general.screenshots')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="300"-->
    <!--            :percentage="60"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('drones.valid')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="120"-->
    <!--            :percentage="34"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--      <v-col cols="12" lg="3" md="6">-->
    <!--        <div class="d-flex flex-column flex-grow-1 h-full">-->
    <!--          <track-card-->
    <!--            :label="$t('drones.invalid')"-->
    <!--            class="h-full"-->
    <!--            color="#8c9eff"-->
    <!--            :value="80"-->
    <!--            :percentage="20"-->
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
    <!--            :value="19"-->
    <!--            :percentage="7.9"-->
    <!--            :percentage-label="$t('dashboard.lastweek')"-->
    <!--            :loading="isLoading3"-->
    <!--            :series="ordersSeries"-->
    <!--          ></track-card>-->
    <!--        </div>-->
    <!--      </v-col>-->
    <!--    </v-row>-->
    <v-card>
      <!-- users list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selecteddrones.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item>
                <v-list-item-title>{{
                  $t("general.verify")
                }}</v-list-item-title>
              </v-list-item>
              <v-list-item>
                <v-list-item-title>{{
                  $t("general.disabled")
                }}</v-list-item-title>
              </v-list-item>
              <v-divider></v-divider>
              <v-list-item>
                <v-list-item-title>{{
                  $t("general.delete")
                }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field v-model="searchQuery" append-icon="mdi-magnify" class="flex-grow-1 mr-md-2" solo hide-details
            dense clearable :placeholder="$t('general.search')"></v-text-field>
          <v-btn :loading="isLoading" icon small class="ml-2" @click.prevent="open()">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table v-model="selecteddrones" :headers="headers" :items="items" :options.sync="options"
        :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" :search="searchQuery" class="flex-grow-1 cursor-pointer"
        :page="page" :pageCount="numberOfPages" :server-items-length="total" :footer-props="{
          'items-per-page-options': [5, 10, 15],
        }">
        <template v-slot:item.serial="{ item }">
          <div>{{ item.number }}</div>
        </template>

        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">{{ item.id }}</div>
        </template>

        <template v-slot:item.name="{ item }">
          <div>{{ item.name }}</div>
        </template>

        <template v-slot:item.status="{ item }">
          <div class="font-weight-bold d-flex align-center text-no-wrap">
            <div v-if="item.status === 'active'" class="success--text">
              <v-icon small color="success">mdi-circle-medium</v-icon>
              <span>{{ $t("drones.valid") }}</span>
            </div>
            <div v-if="item.status === 'notactive'" class="warning--text">
              <v-icon small color="warning">mdi-circle-medium</v-icon>
              <span>{{ $t("drones.invalid") }}</span>
            </div>
          </div>
        </template>

        <template v-slot:item.flightDate="{ item }">
          <div v-if="item.last_flight">{{ item.last_flight | formatDate }}</div>
          <div v-else>---</div>
        </template>

        <template v-slot:item.time="{ item }">
          <div v-if="item.last_flight">{{ item.last_flight | formatTime }}</div>
          <div v-else>---</div>
        </template>

        <template v-slot:item.flights="{ item }">
          <div v-if="item.flights">{{ item.flights }}</div>
          <div v-else>0</div>
        </template>

        <template v-slot:no-data>
          <div class="text-center my-2 primary--text" color="primary">
            <emptyDataSvg></emptyDataSvg>
            <div class="dt-no_data">
              {{ $t("general.no_data_available") }}
            </div>
          </div>
        </template>
        <!--        <template v-slot:item.action="{ item }">-->
        <!--          <div class="actions" >-->
        <!--            <v-btn-->
        <!--              color="primary"-->
        <!--              @click="changeStatus(item)"-->
        <!--            >-->
        <!--              {{ $t('general.changeStatus') }}-->
        <!--            </v-btn>-->
        <!--          </div>-->
        <!--        </template>-->
      </v-data-table>
    </v-card>
    <v-row> </v-row>
  </div>
</template>

<script>
import TrackCard from "../../../components/dashboard/TrackCard";
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  components: {
    TrackCard,
    emptyDataSvg,
  },
  data() {
    return {
      isLoading: false,
      sortBy: "date",
      sortDesc: true,
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      searchQuery: "",
      breadcrumbs: [
        {
          text: this.$t("menu.settings"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("menu.drones"),
        },
      ],
      selecteddrones: [],
      headers: [
        { text: this.$t("tables.serial"), align: "start", value: "serial" },
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.name"), value: "name" },
        {
          text: this.$t("tables.lastFlightDate"),
          sortable: false,
          value: "flightDate",
        },
        {
          text: this.$t("tables.lastFlightTime"),
          sortable: false,
          value: "time",
        },
        {
          text: this.$t("tables.flightNum"),
          sortable: false,
          value: "flights",
        },
        // { text: 'Screenshote', sortable: false, align: 'center', value: 'action' }
      ],
      items: [],
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("drones.dronesSettings"),
    });
  },
  watch: {
    options: {
      handler() {
        this.open();
      },
    },
    deep: true,
    searchQuery() {
      this.open();
    },
  },
  computed: {
    ...mapState("drones", ["drones"]),
  },
  methods: {
    ...mapActions("drones", ["getdrones"]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchRole() { },
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
      };
      this.getdrones(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.items = this.drones.data;
            this.total = this.drones.total;
            this.numberOfPages = this.drones.last_page;
          } else {
            this.items = this.drones;
            this.total = this.drones.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>

<style lang="scss" scoped>
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
</style>
