<template>
  <v-dialog
    v-model="dialogVisible"
    @click:outside="closeDialog"
    scrollable
    max-width="800"
  >
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("cases.showAction") }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text style="height: 500px" class="pb-0">
        <div class="d-flex flex-column flex-sm-row">
          <div class="flex-grow-1 pt-2 pa-sm-2">
            <v-row dense v-if="lastAction" class="mb-2">
              <v-col cols="12">
                <v-expansion-panels multiple v-model="panel">
                  <v-expansion-panel v-model="panel">
                    <v-expansion-panel-header>
                      <h5>{{ $t("general.last_action") }}</h5>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                      <v-row class="mb-1" dense v-if="lastAction?.sessionDate">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.sessionDate") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.sessionDate || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row
                        class="mb-1"
                        dense
                        v-if="lastAction?.session_place"
                      >
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.casePlace") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.session_place || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row dense v-if="lastAction?.status">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("tables.status") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-chip
                            :color="
                              getStatusColor(lastAction?.status?.toLowerCase())
                            "
                            label
                            text-color="white"
                          >
                            <!-- {{
                              lastAction?.status
                                ? $t(
                                    `general.${lastAction?.status?.toLowerCase()}`
                                  )
                                : ""
                            }} -->
                            {{ lastAction?.status }}
                          </v-chip>
                        </v-col>
                      </v-row>
                      <v-row dense v-if="lastAction?.user">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.judgment_for") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.user?.name || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row dense v-if="lastAction?.date">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.judgmentDate") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.date || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row dense v-if="lastAction?.date_of_receipt">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.receiptDate") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.date_of_receipt || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>

                      <v-row class="mb-1" dense v-if="lastAction?.amount">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.amount") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.amount || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row class="mb-1" dense v-if="lastAction?.percentage">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.percentageLose") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            dense
                            class="custom-disabled-input"
                            :value="lastAction?.percentage || ''"
                            solo
                            disabled
                            hide-details
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <!-- <v-row class="mb-1" v-if="lastAction?.court" dense>
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("tables.court") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            class="custom-disabled-input"
                            :value="
                              lastAction?.court
                                ? $t(`general.${lastAction?.court}`)
                                : ''
                            "
                            item-text="title"
                            item-value="value"
                            solo
                            disabled
                            hide-details
                            dense
                          ></v-text-field>
                        </v-col>
                      </v-row>
                      <v-row class="mb-1" v-if="lastAction?.date" dense>
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("tables.date") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">
                          <v-text-field
                            class="custom-disabled-input"
                            :value="lastAction?.date || ''"
                            solo
                            disabled
                            hide-details
                            dense
                          ></v-text-field>
                        </v-col>
                      </v-row> -->

                      <v-row class="mb-1" dense v-if="lastAction?.details">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.action") }}
                          </h6>
                        </v-col>

                        <v-col cols="12" sm="9">
                          <v-textarea
                            class="custom-disabled-input"
                            :value="lastAction?.details || ''"
                            solo
                            disabled
                            hide-details
                          ></v-textarea>
                        </v-col>
                      </v-row>
                    </v-expansion-panel-content>
                  </v-expansion-panel>
                </v-expansion-panels>
              </v-col>
            </v-row>
          </div>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  components: {},
  props: {
    dialogVisible: Boolean,
    formRequestId: Number,
    lastAction: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      loading: false,
      isLoading: false,
      panel: [0],
      caseAction: {
        form_request_id: this.formRequestId,
        amount: "",
        judgment_date: null,
        percentage: "",
        details: "",
        court_name: "",
        status: "",
        branch_id: null,
        judgment_for: null,
        receiptDate: null,
        date: null,
        sessionDate: null,
        dates: [
          {
            caseDate: "",
          },
        ],
      },
    };
  },

  created() {
    this.getCourts();
  },
  watch: {
  },
  computed: {
    ...mapState("cases", ["courts", "caseTypes", "claimant"]),
  },
  methods: {
    ...mapActions("cases", ["getCourts"]),
    closeDialog() {
      this.$emit("close-action-dialog");
    },
    handleInput(event) {
      if (event.key.toLowerCase() === "e") {
        event.preventDefault();
      }
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
.radio-check {
  margin-left: 15%;
}
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
