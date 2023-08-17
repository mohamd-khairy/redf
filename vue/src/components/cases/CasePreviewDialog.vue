<template>
  <v-dialog
    v-model="dialogVisible"
    fullscreen
    hide-overlay
    transition="dialog-bottom-transition"
  >
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        color="primary"
        icon
        v-bind="attrs"
        v-on="on"
        @click="getCaseTimeline()"
      >
        <v-icon>mdi-eye</v-icon>
      </v-btn>
    </template>
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("general.casePreview") }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text v-if="!loading">
        <v-timeline>
          <v-timeline-item
            v-for="(action, i) in formActions"
            :key="i"
            color="green"
            small
          >
            <template v-slot:opposite>
              <span
                :class="`headline font-weight-bold green--text`"
                v-text="formatDate(action.created_at)"
              ></span>
            </template>
            <v-row dense>
              <v-col cols="12">
                <v-expansion-panels multiple>
                  <v-expansion-panel>
                    <v-expansion-panel-header>
                      <h5>{{ action.msg }}</h5>
                    </v-expansion-panel-header>
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
                            :value="action?.formable?.form?.name || ''"
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
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
            </v-row>
          </v-timeline-item>
        </v-timeline>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions } from "vuex";

export default {
  props: {
    dialogVisible: Boolean,
    caseId: Number,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      notifications: false,
      sound: true,
      widgets: false,
      years: [
        {
          color: "cyan",
          year: "1960",
        },
        {
          color: "green",
          year: "1970",
        },
        {
          color: "pink",
          year: "1980",
        },
        {
          color: "amber",
          year: "1990",
        },
        {
          color: "orange",
          year: "2000",
        },
      ],
      formActions: [],
    };
  },
  watch: {
    caseId: {
      immediate: true,
      handler(newId) {
        // Fetch data using Axios when elementId changes
        this.getCaseTimeline(newId);
      },
    },
  },
  methods: {
    ...mapActions("cases", ["getCasePreview"]),
    closeDialog() {
      this.$emit("closePrevDialog");
    },
    getStatusColor(status) {
      const colors = {
        processing: "blue",
        pending: "orange",
        accepted: "green",
      };

      return colors[status] || "primary";
    },
    formatDate(date) {
      if (!date) {
        return;
      }
      return date.split("T")[0];
    },
    async getCaseTimeline(id) {
      try {
        this.loading = true;
        const response = await this.$axios.get(`action-preview/${id}`);
        const { formRequestActions } = response?.data?.data;
        this.formActions = formRequestActions;
      } catch (error) {
        console.log(error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.theme--light.v-input.custom-disabled-input {
  color: rgba(0, 0, 0, 0.87) !important;
  pointer-events: auto !important;
}
.c-h6 {
  font-weight: 600;
}
</style>
<style>
.theme--light.v-input--is-disabled.custom-disabled-input input {
  color: rgba(0, 0, 0, 0.87) !important;
}
</style>
