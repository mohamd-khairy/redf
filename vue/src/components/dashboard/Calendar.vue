<template>
  <v-row>
    <v-col cols="12">
      <FullCalendar :options="calendarOptions" />
    </v-col>
  </v-row>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import arLocale from "@fullcalendar/core/locales/ar";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  props: {
    events: {
      type: Array,
      default: [],
    },
  },
  data: function () {
    return {};
  },
  computed: {
    calendarOptions() {
      return {
        plugins: [dayGridPlugin],
        initialView: "dayGridMonth",
        weekends: false,
        events: this.events,
        locale: arLocale,
        direction: "rtl",
        eventDisplay: "block",
        eventColor: "rgba(1, 76, 79,.7)",
        displayEventTime: false,
        eventClick: (e) => {
          const actionId = e.event?.id || null;
          if (!actionId) return;
          this.$emit("selectedEventId", actionId);
          console.log(actionId);
        },
      };
    },
  },
  // watch: {
  //   events(val) {
  //     this.calendarOptions.events = val;
  //   },
  // },
};
</script>

<style lang="scss" scoped></style>
