<template>
  <div v-if="showChart">
    <apexchart type="bar" height="488" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>

<script>
export default {
  props: {
    series: {
      type: Array,
      default: []
    },
    labels: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      showChart: false,
      // series: [{
      //   name: '',
      //   data: [30, 28, 25, 12, 30, 46, 50, 28]
      // }],
      chartOptions: {
        chart: {
          type: "bar",
          height: 350,
          borderRadius: 10
        },
        noData: {
          text: this.$t("general.no_data_available"),
          align: "center",
          verticalAlign: "middle",
          offsetX: 0,
          offsetY: 0,
          style: {
            color: this.$vuetify.theme.themes.light.primary,
            fontSize: "20px",
            fontFamily: undefined
          }
        },
        // colors: [this.$vuetify.theme.themes.light.primary],
        theme: {
          monochrome: {
            enabled: true,
            color: this.$vuetify.theme.themes.light.primary,
            shadeTo: "light",
            shadeIntensity: 0.8
          }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "55%",
            endingShape: "rounded"
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"]
        },
        xaxis: {
          categories: this.labels
          // categories: [this.$t('charts.drone1'), this.$t('charts.drone2'), this.$t('charts.drone3'), this.$t('charts.drone4'),
          //   this.$t('charts.drone5'), this.$t('charts.drone6'), this.$t('charts.drone7'), this.$t('charts.drone8')]
          // title: {
          //   text: 'drones'
          // }
        },
        yaxis: {
          title: {
            text: "Screenshots"
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return +val + " Screenshots";
            }
          }
        }
      }
    };
  },
  mounted() {
    this.showChart = true;
  }
};
</script>
