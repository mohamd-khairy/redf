<template>
  <v-card class="general-stats-card">
    <v-card-text>
      <div class="d-flex" :id="card.id">
        <v-avatar
          size="42"
          class="me-3 v-avatar--variant-tonal"
          :class="getColor(card.name)"
          variant="tonal"
        >
          <!-- <component
                      :is="key === 'count' ? 'total' : key"
                      v-if="isComponentRegistered(key)"
                    ></component> -->
          <v-icon>
            {{ card.icon }}
          </v-icon>
        </v-avatar>
        <div class="d-flex flex-column">
          <span class="text-h4 font-weight-medium">{{
            card.requests_count
          }}</span>
          <span class="text-caption text-h4">
            {{ card.name }}
          </span>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import total from "@/assets/images/statsIcons/Total.svg";
import smoke from "@/assets/images/statsIcons/Smoke.svg";
import pending from "@/assets/images/statsIcons/UnderProcess.svg";
import fire from "@/assets/images/statsIcons/Fire.svg";
import people from "@/assets/images/statsIcons/People.svg";
import vehicles from "@/assets/images/statsIcons/Vehicles.svg";
import leakage from "@/assets/images/statsIcons/Leakage.svg";
import confirmed from "@/assets/images/statsIcons/Approved.svg";
import notice from "@/assets/images/statsIcons/Authorized.svg";
import not_notice from "@/assets/images/statsIcons/Forbidden.svg";
import error from "@/assets/images/statsIcons/Warning.svg";
import change from "@/assets/images/statsIcons/Change.svg";

export default {
  components: {
    total,
    smoke,
    pending,
    fire,
    people,
    vehicles,
    leakage,
    confirmed,
    notice,
    error,
    not_notice,
    change,
  },
  name: "ShowBuilderCards",
  props: {
    card: Object,
    // loading: {
    //   type: Boolean,
    //   default: true
    // },
    outlined: {
      type: Boolean,
      default: false,
    },
  },

  methods: {
    isComponentRegistered(key) {
      const type = key === "count" ? "total" : key;
      return !!this.$options.components[type];
    },
    getColor(type) {
      const colors = {
        smoke: "grey--text text--darken-1",
        total: "primary--text",
        fire: "red--text",
        people: "indigo--text",
        vehicles: "cyan--text",
        leakage: "orange--text",
        notice: "teal--text",
        not_notice: "deep-orange--text",
        pending: "light-blue--text",
        error: "red--text text--darken-4",
        change: "yellow--text text--darken-1",
      };
      return type in colors ? colors[type] : "primary--text";
    },
  },
};
</script>

<style>
.general-stats-card .text-h6 {
  font-size: 1.25rem !important;
  line-height: 1.5rem;
  color: #4d4b55;
}

.v-avatar--variant-tonal::before {
  background-color: currentColor;
  bottom: 0;
  content: "";
  left: 0;
  opacity: 0;
  pointer-events: none;
  position: absolute;
  right: 0;
  top: 0;
  transition: 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
  opacity: 0.22;
}

.v-avatar--variant-tonal .v-icon {
  color: inherit;
}

.v-avatar--variant-tonal svg {
  width: 30px;
  height: 30px;
}

.v-application .text-caption {
  font-family: "Cairo", sans-serif !important;
}
</style>
