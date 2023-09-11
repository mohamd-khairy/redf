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
                <v-expansion-panels multiple v-model="panel" readonly>
                  <v-expansion-panel v-model="panel">
                    <v-expansion-panel-header hide-actions>
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

                            {{ lastAction?.display_status }}
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
                      <v-row dense v-if="lastAction?.type === 'court'">
                        <v-col cols="12" sm="3">
                          <h6 class="mt-1 mb-0 c-h6">
                            {{ $t("cases.receiptDate") }}
                          </h6>
                        </v-col>
                        <v-col cols="12" sm="9">

                          <v-dialog
                            v-if="lastAction?.type === 'court' && !lastAction?.date_of_receipt"
                            ref="receiptDialog"
                            v-model="receiptDialog"
                            :return-value.sync="receiptDate"
                            persistent
                            width="290px"
                          >
                            <template v-slot:activator="{ on, attrs }">
                              <v-text-field
                                v-model="receiptDate"
                                :label="$t('cases.receiptDate')"
                                append-icon="mdi-calendar"
                                readonly
                                v-bind="attrs"
                                v-on="on"
                                dense
                                solo
                              ></v-text-field>
                            </template>
                            <v-date-picker v-model="receiptDate" scrollable>
                              <v-spacer></v-spacer>
                              <v-btn text color="primary" @click="receiptDialog = false">
                                Cancel
                              </v-btn>
                              <v-btn
                                text
                                color="primary"
                                @click="$refs.receiptDialog.save(receiptDate)"
                              >
                                OK
                              </v-btn>
                            </v-date-picker>
                          </v-dialog>


                          <v-text-field
                            v-else
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
      <v-card-actions class="pb-2" v-if="lastAction?.type === 'court' && !lastAction?.date_of_receipt">
        <v-spacer></v-spacer>
        <v-btn
          @click="storeFormAction"
          color="primary"
          :disabled="isLoading"
          :loading="isLoading"
        >
          {{ $t("general.save") }}
        </v-btn>
        <v-btn class="ms-2" @click="closeDialog" color="grey">
          {{ $t("general.cancel") }}
        </v-btn>
      </v-card-actions>
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
      receiptDialog: false,
      receiptDate: null,
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
    // this.getCourts();
  },
  watch: {
  },
  computed: {
    ...mapState("cases", ["courts", "caseTypes", "claimant"]),
  },
  methods: {
    ...mapActions("cases", ["getCourts",'updateFormRequestInfo']),
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
    async storeFormAction() {
      this.isLoading = true;

      let data = {
        id: this.lastAction.id,
        date_of_receipt: this.receiptDate,
      };
      this.isLoading = true;
      const result = await this.updateFormRequestInfo(data);
      if (result) {
        makeToast("success", "تم اضافة تاريخ استلام الحكم");
        this.closeDialog();
      }
      this.isLoading = false;
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
