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
  data: function () {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin],
        initialView: "dayGridMonth",
        weekends: false,
        events: [{ title: "Meeting", start: new Date() }],
        locale: arLocale,
        direction: "rtl",
      },
    };
  },
};
</script>

<style lang="scss" scoped></style>
