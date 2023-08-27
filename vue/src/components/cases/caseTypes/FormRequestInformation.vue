<template>
  <v-expansion-panel-content>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("cases.amount") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          dense
          class="custom-disabled-input"
          :value="action?.formable?.amount || ''"
          solo
          disabled
          hide-details
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("cases.percentageLose") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          dense
          class="custom-disabled-input"
          :value="action?.formable?.percentage || ''"
          solo
          disabled
          hide-details
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("tables.court") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          class="custom-disabled-input"
          :value="
            action?.formable?.court
              ? $t(`general.${action?.formable?.court}`)
              : ''
          "
          solo
          disabled
          hide-details
          dense
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("tables.date") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          class="custom-disabled-input"
          :value="action?.formable?.date || ''"
          solo
          disabled
          hide-details
          dense
        ></v-text-field>
      </v-col>
    </v-row>

    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("cases.action") }}
        </h6>
      </v-col>

      <v-col cols="12" sm="9">
        <v-textarea
          class="custom-disabled-input"
          :value="action?.formable?.details || ''"
          solo
          disabled
          hide-details
        ></v-textarea>
      </v-col>
    </v-row>

    <v-row class="mb-1" dense v-if="action?.formable?.sessionDate">
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("cases.sessionDate") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          class="custom-disabled-input"
          :value="formatDate(action?.formable?.sessionDate || '')"
          solo
          disabled
          hide-details
          dense
        ></v-text-field>
      </v-col>
    </v-row>

    <v-row dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("tables.status") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-chip
          :color="getStatusColor(action?.formable?.status?.toLowerCase())"
          label
          text-color="white"
        >
          {{ $t(`general.${action?.formable?.status?.toLowerCase()}`) }}
        </v-chip>
      </v-col>
    </v-row>
  </v-expansion-panel-content>
</template>

<script>
export default {
  props: {
    action: {},
  },
  created() {},

  methods: {
    formatDate(date) {
      if (!date) {
        return;
      }
      return date.split("T")[0];
    },
    getStatusColor(status = "") {
      const colors = {
        processing: "blue",
        pending: "orange",
        accepted: "green",
        closed: "red",
      };

      return colors[status.toLocaleLowerCase()] || "primary";
    },
  },
};
</script>

<style lang="scss" scoped></style>
