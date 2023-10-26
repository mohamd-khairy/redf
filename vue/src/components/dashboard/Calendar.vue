<template>
  <v-row class="fill-height">
    <v-col>
      <v-sheet height="64">
        <v-toolbar flat class="p-2 w-100">
          <v-btn outlined class="ml-4" color="grey darken-2" @click="setToday">
            {{ $t("general.today") }}
          </v-btn>
          <v-spacer></v-spacer>
          <div class="d-flex justify-center align-center">
            <v-btn fab text small color="grey darken-2" @click="prev">
              <v-icon small>mdi-chevron-right </v-icon>
            </v-btn>
            <v-toolbar-title v-if="$refs.calendar" class="mx-2 calendar-title">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-btn fab text small color="grey darken-2" @click="next">
              <v-icon small> mdi-chevron-left </v-icon>
            </v-btn>
          </div>
          <v-spacer></v-spacer>

          <!-- <v-menu bottom right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                <span>{{
                  $t(`general.${typeToLabel[type].toLowerCase()}`)
                }}</span>
                <v-icon right> mdi-menu-down </v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>{{ $t("general.day") }}</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>{{ $t("general.week") }}</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>{{ $t("general.month") }}</v-list-item-title>
              </v-list-item>

            </v-list>
          </v-menu> -->
          <v-btn-toggle color="primary" v-model="type">
            <v-btn value="day">
              {{ $t("general.day") }}
            </v-btn>

            <v-btn value="week">
              {{ $t("general.week") }}
            </v-btn>

            <v-btn value="month">
              {{ $t("general.month") }}
            </v-btn>
          </v-btn-toggle>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="600" class="p-4">
        <v-calendar
          id="calendar"
          ref="calendar"
          v-model="focus"
          locale="ar"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :type="type"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
        >
          <template #event="{ event }">
            <div
              @click="($event) => showEvent($event, event)"
              :key="event.id || Math.random()"
              class="event"
            >
              {{ event.name }}
            </div>
          </template>
        </v-calendar>
        <!-- <template v-slot:day-body="{ date, week, month }">
          <div
            class="v-current-time"
            :class="{ first: date === week[0].date }"
            :style="{ top: nowY }"
          ></div>
        </template> -->
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card color="grey lighten-4" min-width="350px" flat>
            <v-toolbar :color="selectedEvent.color" dark>
              <!-- <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title> -->
              <v-toolbar-title>{{ $t("cases.action") }}</v-toolbar-title>
              <v-spacer></v-spacer>

              <!-- <v-btn icon>
                <v-icon>mdi-dots-vertical</v-icon>
              </v-btn> -->
            </v-toolbar>
            <v-card-text>
              <span v-html="selectedEvent.name"></span>
            </v-card-text>
            <v-card-actions>
              <v-btn outlined color="secondary" @click="selectedOpen = false">
                {{ $t("common.close") }}
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    eventsData: {
      type: Array,
      default: [],
    },
  },
  data: () => ({
    focus: "",
    type: "month",
    typeToLabel: {
      month: "Month",
      week: "Week",
      day: "Day",
    },
    selectedEvent: {},
    selectedElement: null,
    selectedOpen: false,
    events: [],
    colors: [
      "blue",
      "indigo",
      "deep-purple",
      "cyan",
      "green",
      "orange",
      "grey darken-1",
    ],
    names: [
      "Meeting",
      "Holiday",
      "PTO",
      "Travel",
      "Event",
      "Birthday",
      "Conference",
      "Party",
    ],
  }),
  mounted() {
    // this.$refs.calendar.checkChange();
    console.log(this.$refs.calendar.title);
  },
  watch: {
    eventsData(val) {
      console.log("aaaaa");
      console.log(val);
      this.$refs.calendar.checkChange();
    },
  },
  methods: {
    viewDay({ date }) {
      this.focus = date;
      this.type = "day";
    },
    getEventColor(event) {
      return event.color;
    },
    setToday() {
      this.focus = "";
    },
    prev() {
      this.$refs.calendar.prev();
    },
    next() {
      this.$refs.calendar.next();
    },
    showEvent(nativeEvent, event) {
      // this.events.map((ev) => {
      //   if (ev.expanded) {
      //     ev.end = ev.start;
      //     ev.expanded = false;
      //   }
      //   return ev;
      // });
      // if (event.expand && event.expanded) {
      //   event.end = event.start;
      //   event.expanded = false;
      //   return;
      // } else if (event.expand && !event.expanded) {
      //   // this.$emit("expandEvent", event);
      //   this.events = this.events.map((ev) => {
      //     if (ev.expanded === true) {
      //       ev.expanded = false;
      //       ev.end = ev.start;
      //     }
      //     return ev;
      //   });
      //   const startDateString = event.start;
      //   const startDate = new Date(startDateString);

      //   // Calculate the end date by adding 30 days to the start date
      //   const endDate = new Date(startDate);
      //   endDate.setDate(startDate.getDate() + 30);

      //   // Format the end date as "YYYY-MM-DD"
      //   const endDateString = endDate.toISOString().split("T")[0];
      //   event.end = endDateString;
      //   event.expanded = true;
      // }

      if (event.expand && event.expanded) {
        // Close the currently expanded event
        event.expanded = false;
        event.end = event.start;
      } else if (event.expand && !event.expanded) {
        // Close all other expanded events
        this.events = this.events.map((ev) => {
          if (ev.expanded === true) {
            ev.expanded = false;
            ev.end = ev.start;
          }
          return ev;
        });

        // Expand the clicked event
        const startDateString = event.start;
        const startDate = new Date(startDateString);

        // Calculate the end date by adding 30 days to the start date
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + 30);

        // Format the end date as "YYYY-MM-DD"
        const endDateString = endDate.toISOString().split("T")[0];
        event.end = endDateString;
        event.expanded = true;
      }

      const open = () => {
        this.selectedEvent = event;
        this.selectedElement = nativeEvent.target;
        requestAnimationFrame(() =>
          requestAnimationFrame(() => (this.selectedOpen = true))
        );
      };

      if (this.selectedOpen) {
        this.selectedOpen = false;
        requestAnimationFrame(() => requestAnimationFrame(() => open()));
      } else {
        open();
      }

      nativeEvent.stopPropagation();
      // this.$refs.calendar.checkChange();
    },
    updateRange({ start, end }) {
      const events = [];
      const min = new Date(`${start.date}T00:00:00`);
      const max = new Date(`${end.date}T23:59:59`);
      const days = (max.getTime() - min.getTime()) / 86400000;
      const eventCount = this.rnd(days, days + 5);
      this.eventsData.forEach((ev) => {
        events.push({
          id: ev.form_request_id,
          name: ev.name,
          start: ev.start_date,
          end: ev.start_date,
          endDate: ev.end_date,
          color: ev.color,
          timed: true,
          expand: ev.expand,
        });
      });
      console.log(this.events);
      // for (let i = 0; i < eventCount; i++) {
      //   const allDay = this.rnd(0, 3) === 0;
      //   const firstTimestamp = this.rnd(min.getTime(), max.getTime());
      //   const first = new Date(firstTimestamp - (firstTimestamp % 900000));
      //   const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000;
      //   const second = new Date(first.getTime() + secondTimestamp);
      //   // console.log(events);
      //   events.push({
      //     name: this.names[this.rnd(0, this.names.length - 1)],
      //     start: first,
      //     end: second,
      //     color: this.colors[this.rnd(0, this.colors.length - 1)],
      //     timed: !allDay,
      //   });
      // }

      this.events = events;
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
  },
};
</script>

<style lang="scss">
#calendar .v-calendar-daily__head {
  margin-right: 0px !important;
  margin-left: 5px !important;
}

.v-application--is-rtl #calendar .pl-1 {
  padding-left: 0 !important;
  padding-right: 8px !important;
}

.v-calendar .v-event {
  margin-right: 0 !important;
}
.calendar-title {
  width: 120px;
  text-align: center;
}
</style>
