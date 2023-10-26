<template>
  <v-dialog v-model="dialogVisible" @click:outside="closeDialog" scrollable max-width="800">
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("cases.addAction") }}</v-toolbar-title>
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
            <!--            <v-row dense v-if="lastAction" class="mb-2">-->
            <!--              <v-col cols="12">-->
            <!--                <v-expansion-panels multiple>-->
            <!--                  <v-expansion-panel>-->
            <!--                    <v-expansion-panel-header>-->
            <!--                      <h5>{{ $t("general.last_action") }}</h5>-->
            <!--                    </v-expansion-panel-header>-->
            <!--                    <v-expansion-panel-content>-->
            <!--                      <v-row class="mb-1" dense v-if="lastAction?.sessionDate">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.sessionDate") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.sessionDate || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row-->
            <!--                        class="mb-1"-->
            <!--                        dense-->
            <!--                        v-if="lastAction?.session_place"-->
            <!--                      >-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.casePlace") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.session_place || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row dense v-if="lastAction?.status">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("tables.status") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-chip-->
            <!--                            :color="-->
            <!--                              getStatusColor(lastAction?.status?.toLowerCase())-->
            <!--                            "-->
            <!--                            label-->
            <!--                            text-color="white"-->
            <!--                          >-->
            <!--                            &lt;!&ndash; {{-->
            <!--                              lastAction?.status-->
            <!--                                ? $t(-->
            <!--                                    `general.${lastAction?.status?.toLowerCase()}`-->
            <!--                                  )-->
            <!--                                : ""-->
            <!--                            }} &ndash;&gt;-->
            <!--                            {{ lastAction?.status }}-->
            <!--                          </v-chip>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row dense v-if="lastAction?.user">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.judgment_for") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.user?.name || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row dense v-if="lastAction?.date">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.judgmentDate") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.date || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row dense v-if="lastAction?.date_of_receipt">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.receiptDate") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.date_of_receipt || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->

            <!--                      <v-row class="mb-1" dense v-if="lastAction?.amount">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.amount") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.amount || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row class="mb-1" dense v-if="lastAction?.percentage">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.percentageLose") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            dense-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.percentage || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      &lt;!&ndash; <v-row class="mb-1" v-if="lastAction?.court" dense>-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("tables.court") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="-->
            <!--                              lastAction?.court-->
            <!--                                ? $t(`general.${lastAction?.court}`)-->
            <!--                                : ''-->
            <!--                            "-->
            <!--                            item-text="title"-->
            <!--                            item-value="value"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                            dense-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                      <v-row class="mb-1" v-if="lastAction?.date" dense>-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("tables.date") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->
            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-text-field-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.date || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                            dense-->
            <!--                          ></v-text-field>-->
            <!--                        </v-col>-->
            <!--                      </v-row> &ndash;&gt;-->

            <!--                      <v-row class="mb-1" dense v-if="lastAction?.details">-->
            <!--                        <v-col cols="12" sm="3">-->
            <!--                          <h6 class="mt-1 mb-0 c-h6">-->
            <!--                            {{ $t("cases.action") }}-->
            <!--                          </h6>-->
            <!--                        </v-col>-->

            <!--                        <v-col cols="12" sm="9">-->
            <!--                          <v-textarea-->
            <!--                            class="custom-disabled-input"-->
            <!--                            :value="lastAction?.details || ''"-->
            <!--                            solo-->
            <!--                            disabled-->
            <!--                            hide-details-->
            <!--                          ></v-textarea>-->
            <!--                        </v-col>-->
            <!--                      </v-row>-->
            <!--                    </v-expansion-panel-content>-->
            <!--                  </v-expansion-panel>-->
            <!--                </v-expansion-panels>-->
            <!--              </v-col>-->
            <!--            </v-row>-->
            <v-row dense class="mb-2">
              <v-radio-group v-model="radioAction" row>
                <v-radio class="radio-check" value="session" :label="$t('cases.add_session')"></v-radio>
                <v-radio class="radio-check" value="court" :label="$t('cases.add_court')"></v-radio>
                <v-radio value="other" :label="$t('cases.another')"></v-radio>
              </v-radio-group>
            </v-row>
            <v-row dense v-if="radioAction === 'session'">
              <v-col cols="12" md="12">
                <v-dialog ref="sessionDialog" v-model="sessionDialog" :return-value.sync="caseAction.sessionDate"
                  persistent width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field v-model="caseAction.sessionDate" :label="$t('cases.sessionDate')"
                      append-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" dense outlined></v-text-field>
                  </template>
                  <v-date-picker v-model="caseAction.sessionDate" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="sessionDialog = false">
                      Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.sessionDialog.save(caseAction.sessionDate)">
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-col>
              <v-col cols="12" md="12">
                <v-select :items="sessionPlaces || []" :label="$t('cases.casePlace')" dense outlined
                  v-model="caseAction.sessionPlace">
                </v-select>
              </v-col>
            </v-row>
            <v-row dense v-if="radioAction === 'court'">
              <v-col cols="6">
                <v-select clearable :items="caseTypes" @click:clear="caseAction.status = null"
                  :label="$t('tables.status')" item-text="title" item-value="value" hide-details dense outlined
                  v-model="caseAction.status">
                </v-select>
              </v-col>
              <v-col cols="12" md="6">
                <v-select :items="claimant" :label="$t('cases.judgment_for')" dense outlined item-text="name"
                  item-value="id" v-model="caseAction.judgment_for">
                </v-select>
              </v-col>
              <v-col cols="12">
                <v-dialog ref="dateDialog" v-model="dateDialog" :return-value.sync="caseAction.date" persistent
                  width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field v-model="caseAction.date" :label="$t('cases.judgmentDate')" append-icon="mdi-calendar"
                      readonly v-bind="attrs" v-on="on" dense outlined></v-text-field>
                  </template>
                  <v-date-picker v-model="caseAction.date" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="dateDialog = false">
                      Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.dateDialog.save(caseAction.date)">
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-col>
             
              <v-col cols="12" v-if="caseAction.status">
                <v-textarea :label="caseActionDetailsLabel" value="" v-model="caseAction.details" dense
                  outlined></v-textarea>
              </v-col>

              <v-col cols="12">
                <v-dialog ref="receiptDialog" v-model="receiptDialog" :return-value.sync="caseAction.receiptDate"
                  persistent width="290px">
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field v-model="caseAction.receiptDate" :label="$t('cases.receiptDate')"
                      append-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" dense outlined></v-text-field>
                  </template>
                  <v-date-picker v-model="caseAction.receiptDate" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="receiptDialog = false">
                      Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.receiptDialog.save(caseAction.receiptDate)">
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-col>
            </v-row>

            <v-row dense v-if="radioAction === 'other'">
              <v-col cols="12">
                <v-text-field type="number" @keydown="handleInput" v-model="caseAction.amount" :label="$t('cases.amount')"
                  dense outlined>
                  <template v-slot:append>
                    <v-icon> mdi-cash </v-icon>
                  </template>
                </v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field type="number" @keydown="handleInput" v-model="caseAction.percentage"
                  :label="$t('cases.percentageLose')" dense outlined>
                  <template v-slot:append>
                    <v-icon> mdi-percent </v-icon>
                  </template>
                </v-text-field>
              </v-col>
            </v-row>
          </div>
        </div>
      </v-card-text>
      <hr class="mb-0" />
      <v-card-actions class="pb-2">
        <v-spacer></v-spacer>

        <v-btn @click="storeFormAction" color="primary" :disabled="isLoading" :loading="isLoading">
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
      caseActionDetailsLabel: "",
      receiptDialog: false,
      dateDialog: false,
      sessionDialog: false,
      sessionDate: false,
      radioAction: 'session',
      caseAction: {
        form_request_id: this.formRequestId,
        amount: "",
        judgment_date: null,
        percentage: "",
        details: "",
        court_name: "",
        status: "",
        sessionPlace: null,
        judgment_for: null,
        receiptDate: null,
        date: null,
        // sessionDate: new Date(
        //   Date.now() - new Date().getTimezoneOffset() * 60000
        // )
        //   .toISOString()
        //   .substr(0, 10),
        sessionDate: null,
        dates: [
          {
            caseDate: "",
          },
        ],
      },
      status: [
        { key: "error", value: 0 },
        { key: "confirmed", value: 1 },
        { key: "pending", value: 2 },
      ],
    };
  },

  created() {
    this.getCourts();
    this.retrieveClaimant({ form_request_id: this.formRequestId });
  },
  watch: {
    "caseAction.status"(newVal) {
      const selecetdStatus = this.caseTypes.find(
        (type) => type.value === newVal
      );
      this.caseActionDetailsLabel = selecetdStatus.title.substr(selecetdStatus.title.indexOf(" ") + 1);
    },
  },
  computed: {
    ...mapState("cases", ["courts", "caseTypes", "claimant", "sessionPlaces"]),
  },
  methods: {
    ...mapActions("cases", [
      "saveFormInformation",
      "getCourts",
      "retrieveClaimant",
    ]),
    closeDialog() {
      this.$emit("close-action-dialog");
    },
    handleInput(event) {
      if (event.key.toLowerCase() === "e") {
        event.preventDefault();
      }
    },
    async storeFormAction() {
      this.isLoading = true;
      this.caseAction.dates = this.caseAction.dates.map(
        (cdate) => cdate.caseDate
      );

      let data = {
        form_request_id: this.formRequestId,
        amount: this.caseAction.amount,
        percentage: this.caseAction.percentage,
        details: this.caseAction.details,
        status: this.caseAction.status,
        date: this.caseAction.date,
        court: this.caseAction.court_name,
        sessionDate: this.caseAction.sessionDate,
        session_place: this.caseAction.sessionPlace,
        date_of_receipt: this.caseAction.receiptDate,
        user_id: this.caseAction.judgment_for,
        type: this.radioAction
      };
      this.isLoading = true;
      const result = await this.saveFormInformation(data);
      if (result) {
        makeToast("success", "تم اضافة الاجراء");
        this.closeDialog();
      }
      this.isLoading = false;
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
