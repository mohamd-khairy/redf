<template>
  <v-expansion-panel-content>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.case") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          dense
          class="custom-disabled-input"
          :value="action?.formable?.name || ''"
          solo
          label="Solo"
          disabled
          hide-details
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.caseNumber") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          dense
          class="custom-disabled-input"
          :value="action?.formable?.form_request_number || ''"
          solo
          label="Solo"
          disabled
          hide-details
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.applicant") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          class="custom-disabled-input"
          :value="action.formable?.user?.name || ''"
          solo
          label="Solo"
          disabled
          hide-details
          dense
        ></v-text-field>
      </v-col>
    </v-row>

    <v-row class="mb-1" dense v-if="action?.formable?.note">
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.note") }}
        </h6>
      </v-col>

      <v-col cols="12" sm="9">
        <v-text-area
          class="custom-disabled-input"
          :value="action?.formable?.note || ''"
          solo
          label="Solo"
          disabled
          hide-details
        ></v-text-area>
      </v-col>
    </v-row>
    <v-row class="mb-1" dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.date") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-text-field
          class="custom-disabled-input"
          :value="formatDate(action?.formable?.created_at)"
          solo
          label="Solo"
          disabled
          hide-details
          dense
        ></v-text-field>
      </v-col>
    </v-row>
    <v-row dense>
      <v-col cols="12" sm="3">
        <h6 class="mt-1 mb-0 c-h6">
          {{ $t("general.status") }}
        </h6>
      </v-col>
      <v-col cols="12" sm="9">
        <v-chip
          :color="getStatusColor(action?.formable?.status)"
          label
          text-color="white"
        >
          {{ $t(`general.${action?.formable?.status}`) }}
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
  methods: {
    formatDate(date) {
      if (!date) {
        return;
      }
      return date.split("T")[0];
    },
    getStatusColor(status) {
      const colors = {
        processing: "blue",
        pending: "orange",
        accepted: "green",
      };

      return colors[status] || "primary";
    },
  },
};
</script>

<style lang="scss" scoped></style>
