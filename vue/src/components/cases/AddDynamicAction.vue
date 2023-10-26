<template>
  <v-dialog
    v-model="dialogVisible"
    @click:outside="closeDialog"
    max-width="800"
  >
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
      <v-card-text class="pb-0">
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
<!--                      <v-row class="mb-1" dense>-->
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
<!--                      </v-row>-->

<!--                      <v-row class="mb-1" dense>-->
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

<!--                      <v-row dense>-->
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
<!--                            {{-->
<!--                              $t(`general.${lastAction?.status?.toLowerCase()}`)-->
<!--                            }}-->
<!--                          </v-chip>-->
<!--                        </v-col>-->
<!--                      </v-row>-->
<!--                    </v-expansion-panel-content>-->
<!--                  </v-expansion-panel>-->
<!--                </v-expansion-panels>-->
<!--              </v-col>-->
<!--            </v-row>-->
            <v-row dense>
              <v-col cols="6">
                <v-select
                  :items="caseTypes"
                  :label="$t('tables.status')"
                  item-text="title"
                  item-value="value"
                  hide-details
                  dense
                  outlined
                  v-model="caseAction.status"
                >
                </v-select>
              </v-col>
              <v-col cols="12" sm="6">
                <v-dialog
                  ref="dateDialog"
                  v-model="dateDialog"
                  :return-value.sync="caseAction.date"
                  persistent
                  width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="caseAction.date"
                      :label="$t('tables.date')"
                      prepend-icon="mdi-calendar"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                      dense
                      outlined
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="caseAction.date" scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="modal = false">
                      Cancel
                    </v-btn>
                    <v-btn
                      text
                      color="primary"
                      @click="$refs.dateDialog.save(caseAction.date)"
                    >
                      OK
                    </v-btn>
                  </v-date-picker>
                </v-dialog>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  :label="$t('cases.action')"
                  value=""
                  v-model="caseAction.details"
                  dense
                  outlined
                ></v-textarea>
              </v-col>
            </v-row>
          </div>
        </div>
      </v-card-text>

      <v-card-actions class="pb-2">
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
  name:'AddDynamicAction',
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
      dateDialog: false,
      sessionDialog: false,
      sessionDate: false,
      caseAction: {
        form_request_id: this.formRequestId,
        details: "",
        status: "",
        date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
          .toISOString()
          .substr(0, 10),
      },
    };
  },
  watch: {},
  created() {
    this.getCourts();
    console.log(this.lastAction);
  },
  computed: {
    ...mapState("cases", ["courts", "caseTypes"]),
  },
  methods: {
    ...mapActions("cases", ["saveFormInformation", "getCourts"]),
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

      let data = {
        form_request_id: this.formRequestId,
        details: this.caseAction.details,
        status: this.caseAction.status,
        date: this.caseAction.date,
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
