<template>
  <v-dialog v-model="dialogVisible" fullscreen hide-overlay transition="dialog-bottom-transition">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("cases.view_timeline") }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text v-if="loading" class="dialog-loading-cont">
        <v-row align-content="center" justify="center">
          <v-col class="text-subtitle-1 text-center" cols="12">
            {{ $t("general.getting_data") }}
          </v-col>
          <v-col cols="12">
            <v-progress-linear color="primary accent-4" indeterminate rounded height="6"></v-progress-linear>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-text v-if="!loading && formActions.length">
        <v-timeline>
          <v-timeline-item v-for="(action, i) in formActions" :key="i" :color="getTimeColor(action.formable_type)"
            fill-dot icon="mdi-calendar">
            <template v-slot:opposite>
              <span :class="`headline font-weight-bold ${getTimeColor(
                action.formable_type
              )}--text`" v-text="formatDate(action.created_at)"></span>
            </template>
            <v-row dense>
              <v-col cols="12">
                <v-expansion-panels>
                  <v-expansion-panel>
                    <v-expansion-panel-header>
                      <h5>{{ action.msg }}</h5>
                    </v-expansion-panel-header>
                    <component :action="action" :is="getFormType(action.formable_type)" />
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
            </v-row>
          </v-timeline-item>
        </v-timeline>
      </v-card-text>
      <v-card-text v-if="!loading && !formActions.length">
        <div class="text-center mt-7 primary--text" color="primary">
          <emptyDataSvg></emptyDataSvg>
          <div class="dt-no_data">
            {{ $t("general.no_action_yet") }}
          </div>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions } from "vuex";
import FormRequest from "./caseTypes/FormRequest.vue";
import FormAssignRequest from "./caseTypes/FormAssignRequest.vue";
import FormRequestInformation from "./caseTypes/FormRequestInformation.vue";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  components: {
    FormRequest,
    FormAssignRequest,
    FormRequestInformation,
    emptyDataSvg,
  },
  props: {
    dialogVisible: Boolean,
    caseId: Number,
  },
  data() {
    return {
      loading: false,
      formActions: [],
      panelExpanded: true,
    };
  },
  watch: {
    caseId: {
      immediate: true,
      handler(newId) {
        this.getCaseTimeline(newId);
      },
    },
  },
  methods: {
    ...mapActions("cases", ["getCasePreview"]),
    closeDialog() {
      this.$emit("closePrevDialog");
    },
    getTimeColor(typeStr) {
      const type = this.getFormType(typeStr || "");
      const colors = {
        FormAssignRequest: "blue",
        FormRequest: "orange",
        accepted: "green",
      };

      return colors[type] || "primary";
    },
    formatDate(date) {
      if (!date) {
        return;
      }
      return date.split("T")[0];
    },
    getFormType(type) {
      if (!type) return;
      return type.split("\\").pop();
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

.dialog-loading-cont {
  height: calc(100vh - 65px);
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
</style>
