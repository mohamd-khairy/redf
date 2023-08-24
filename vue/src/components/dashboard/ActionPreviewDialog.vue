<template>
  <v-dialog v-model="dialogVisible" max-width="800">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("general.actionPreview") }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text class="pb-0">
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
              :value="actionInfo?.amount || ''"
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
              :value="actionInfo?.percentage || ''"
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
              :value="$t(`general.${actionInfo?.court}`) || ''"
              item-text="title"
              item-value="value"
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
              :value="actionInfo?.date || ''"
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
              :value="actionInfo?.details || ''"
              solo
              disabled
              hide-details
            ></v-textarea>
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
              :color="getStatusColor(actionInfo?.status?.toLowerCase())"
              label
              text-color="white"
            >
              {{ $t(`general.${actionInfo?.status?.toLowerCase()}`) }}
            </v-chip>
          </v-col>
        </v-row>
      </v-card-text>

      <v-card-actions class="pb-2">
        <v-spacer></v-spacer>
        <v-btn class="ms-2" @click="closeDialog" color="grey">
          {{ $t("general.cancel") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  components: {},
  props: {
    dialogVisible: Boolean,
    actionInfo: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {};
  },
  watch: {},
  created() {
    console.log(this.actionInfo);
  },
  computed: {},
  methods: {
    closeDialog() {
      this.$emit("close-action-dialog");
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
<style>
.theme--light.v-input.custom-disabled-input {
  color: rgba(0, 0, 0, 0.87) !important;
  pointer-events: auto !important;
}
.c-h6 {
  font-weight: 600;
}
.theme--light.v-input--is-disabled.custom-disabled-input input {
  color: rgba(0, 0, 0, 0.87) !important;
}
</style>
