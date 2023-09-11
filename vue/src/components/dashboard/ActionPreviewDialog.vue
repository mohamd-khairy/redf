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
      <v-card-text class="pb-0 mt-2">
        <v-row class="mb-1" dense v-if="actionInfo?.sessionDate">
          <v-col cols="12" sm="3">
            <h6 class="mt-1 mb-0 c-h6">
              {{ $t("cases.sessionDate") }}
            </h6>
          </v-col>
          <v-col cols="12" sm="9">
            <v-text-field
              dense
              class="custom-disabled-input"
              :value="actionInfo?.sessionDate | formatDate('lll')"
              solo
              disabled
              hide-details
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row class="mb-1" dense v-if="actionInfo?.session_place">
          <v-col cols="12" sm="3">
            <h6 class="mt-1 mb-0 c-h6">
              {{ $t("cases.casePlace") }}
            </h6>
          </v-col>
          <v-col cols="12" sm="9">
            <v-text-field
              dense
              class="custom-disabled-input"
              :value="actionInfo?.session_place || ''"
              solo
              disabled
              hide-details
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense v-if="actionInfo?.status">
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
              {{ actionInfo?.status }}
            </v-chip>
          </v-col>
        </v-row>
        <v-row dense v-if="actionInfo?.user">
          <v-col cols="12" sm="3">
            <h6 class="mt-1 mb-0 c-h6">
              {{ $t("cases.judgment_for") }}
            </h6>
          </v-col>
          <v-col cols="12" sm="9">
            <v-text-field
              dense
              class="custom-disabled-input"
              :value="actionInfo?.user?.name || ''"
              solo
              disabled
              hide-details
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense v-if="actionInfo?.date">
          <v-col cols="12" sm="3">
            <h6 class="mt-1 mb-0 c-h6">
              {{ $t("cases.judgmentDate") }}
            </h6>
          </v-col>
          <v-col cols="12" sm="9">
            <v-text-field
              dense
              class="custom-disabled-input"
              :value="actionInfo?.date | formatDate('lll')"
              solo
              disabled
              hide-details
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row dense v-if="actionInfo?.type === 'court'">
          <v-col cols="12" sm="3">
            <h6 class="mt-1 mb-0 c-h6">
              {{ $t("cases.receiptDate") }}
            </h6>
          </v-col>
          <v-col cols="12" sm="9">
            <v-dialog
              v-if="
                actionInfo?.type === 'court' && !actionInfo?.date_of_receipt
              "
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
              :value="actionInfo?.date_of_receipt | formatDate('lll')"
              solo
              disabled
              hide-details
            ></v-text-field>
          </v-col>
        </v-row>

        <v-row class="mb-1" dense v-if="actionInfo?.amount">
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
        <v-row class="mb-1" dense v-if="actionInfo?.percentage">
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
        <v-row class="mb-1" dense v-if="actionInfo?.details">
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
