<template>
  <div class="d-flex flex-grow-1 flex-column">
    <div class="d-flex align-center py-3">
      <div class="d-flex align-baseline">
        <div class="display-1 d-flex align-center">{{ $t('menu.reports') }}</div><small class="mx-1">({{
          $t('dashboard.thismonth') }})</small>
      </div>
      <v-spacer></v-spacer>
      <!--      <div style="width: 140px; height: 47px;">-->
      <!--        <v-select-->
      <!--          label="Select Date"-->
      <!--          class="mb-0"-->
      <!--          :items="filter"-->
      <!--          v-model="defaultSelected"-->
      <!--          solo-->
      <!--        ></v-select>-->
      <!--      </div>-->
      <div class="h-38">
        <template>
          <div class="date-picker position-relative">
            <i aria-hidden="true" class="v-icon mdi mdi-calendar"></i>
            <date-range-picker v-model="dateRange" :date-format="dateFormat" direction='rtl'>
            </date-range-picker>
          </div>
        </template>
      </div>
    </div>
    <v-row v-if="isLoading3" class="flex-grow-0 mb-1" dense>
      <v-col cols="12" lg="3" md="6">
        <v-card>
          <v-skeleton-loader :min-height="115" type="card-heading, list-item"></v-skeleton-loader>
        </v-card>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <v-card>
          <v-skeleton-loader :min-height="115" type="card-heading, list-item"></v-skeleton-loader>
        </v-card>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <v-card>
          <v-skeleton-loader :min-height="115" type="card-heading, list-item"></v-skeleton-loader>
        </v-card>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <v-card>
          <v-skeleton-loader :min-height="115" type="card-heading, list-item"></v-skeleton-loader>
        </v-card>
      </v-col>
    </v-row>
    <v-row v-else class="flex-grow-0 mb-1" dense>
      <v-col cols="12" lg="3" md="6">
        <div class="d-flex flex-column flex-grow-1 h-full">
          <track-card :label="$t('general.screenshots')" class="h-full" color="#8c9eff" :value="12" :percentage="4.3"
            :percentage-label="$t('dashboard.lastweek')" :loading="isLoading3" :series="ordersSeries"></track-card>
        </div>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <div class="d-flex flex-column flex-grow-1 h-full">
          <track-card :label="$t('general.risky')" class="h-full" color="#8c9eff" :value="32" :percentage="24"
            :percentage-label="$t('dashboard.lastweek')" :loading="isLoading3" :series="ordersSeries"></track-card>
        </div>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <div class="d-flex flex-column flex-grow-1 h-full">
          <track-card :label="$t('general.drones')" class="h-full" color="#8c9eff" :value="8" :percentage="85"
            :percentage-label="$t('dashboard.lastweek')" :loading="isLoading3" :series="ordersSeries"></track-card>
        </div>
      </v-col>
      <v-col cols="12" lg="3" md="6">
        <div class="d-flex flex-column flex-grow-1 h-full">
          <track-card :label="$t('general.stations')" class="h-full" color="#8c9eff" :value="2" :percentage="7.9"
            :percentage-label="$t('dashboard.lastweek')" :loading="isLoading3" :series="ordersSeries"></track-card>
        </div>
      </v-col>
    </v-row>

    <v-row class="flex-grow-0 mb-1" dense>
      <v-col cols="12" lg="6">
        <v-card>
          <div v-if="loading" class="d-flex flex-grow-1 align-center justify-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title>
              {{ $t('general.screenshots') }}
            </v-card-title>
            <div class="d-flex flex-column flex-grow-1">
              <pie></pie>
            </div>
          </div>
        </v-card>

      </v-col>
      <v-col cols="12" lg="6">
        <v-card>
          <div v-if="loading" class="d-flex flex-grow-1 align-center justify-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title>
              {{ $t('general.stations') }}
            </v-card-title>
            <div class="d-flex flex-column flex-grow-1 justify-center pb-3">
              <pie2></pie2>
            </div>
          </div>
        </v-card>

      </v-col>
      <v-col cols="12" lg="6">
        <v-card>
          <div v-if="loading" class="d-flex flex-grow-1 align-center justify-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title>
              {{ $t('general.drones') }}
            </v-card-title>
            <div class="d-flex flex-column flex-grow-1">
              <!--              <radar></radar>-->
              <column></column>
            </div>
          </div>
        </v-card>

      </v-col>
      <v-col cols="12" lg="6">
        <table-card class="h-full" :label="$t('general.drones')" />
      </v-col>
      <v-col cols="12">
        <v-card>
          <div v-if="loading" class="d-flex flex-grow-1 align-center justify-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <div v-else class="d-flex flex-column flex-grow-1">
            <v-card-title>
              {{ $t('general.dronesInEachStation') }}
            </v-card-title>
            <div class="d-flex flex-column flex-grow-1">
              <bar></bar>
            </div>
          </div>
        </v-card>

      </v-col>
    </v-row>

  </div>
</template>

<script>
// DEMO Cards for dashboard
import bar from '../../components/dashboard/bar'
// import column from '../../components/dashboard/column'
import pie from '../../components/dashboard/pie'
import pie2 from '../../components/dashboard/pie2'
// import radar from '../../components/dashboard/radar'
import Column from '../../components/dashboard/column'

// import spline from '../../components/dashboard/spline'
import TrackCard from '../../components/dashboard/TrackCard'
import TableCard from '../../components/dashboard/TableCard'

import DateRangePicker from 'vue2-daterange-picker'
import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'

export default {
  components: {
    Column,
    TrackCard,
    TableCard,
    bar,
    pie,
    pie2,
    DateRangePicker
    // radar
    // spline
  },
  filters: {
    dateCell(value) {
      const dt = new Date(value)

      return dt.getDate()
    },
    date(val) {
      return val ? val.toLocaleString() : 'filter'
    }
  },
  data() {
    return {
      loadingInterval: null,

      isLoading1: true,
      isLoading2: true,
      isLoading3: true,
      isLoading4: true,

      ordersSeries: [{
        name: 'Orders',
        data: [
          ['2020-02-02', 34],
          ['2020-02-03', 43],
          ['2020-02-04', 40],
          ['2020-02-05', 43]
        ]
      }],

      customersSeries: [{
        name: 'Customers',
        data: [
          ['2020-02-02', 13],
          ['2020-02-03', 11],
          ['2020-02-04', 13],
          ['2020-02-05', 12]
        ]
      }],

      filter: [this.$t('dashboard.thismonth'), this.$t('dashboard.lastweek'), this.$t('dashboard.lastmonth')],
      defaultSelected: this.$t('dashboard.thismonth'),

      filterLabel: true,

      dateRange: {
        startDate: '2022-11-1',
        endDate: '2022-12-1'
      }

    }

  },
  watch: {
    selectedUsers(val) {

    }
  },
  computed: {
    theme() {
      return this.$vuetify.theme.isDark
        ? this.$vuetify.theme.defaults.dark
        : this.$vuetify.theme.defaults.light
    }
  },
  mounted() {
    let count = 0

    // DEMO delay for loading graphics
    this.loadingInterval = setInterval(() => {
      this[`isLoading${count++}`] = false
      if (count === 4) this.clear()
    }, 400)
  },
  beforeDestroy() {
    this.clear()
  },
  methods: {
    clear() {
      clearInterval(this.loadingInterval)
    },
    showFilterLabel() {
      this.filterLabel = false
    }
  }
}
</script>
<style>
.vue-daterange-picker {
  margin: 0;
}

/* .v-skeleton-loader > .v-skeleton-loader__bone{
  min-height: 115px;
} */
</style>
