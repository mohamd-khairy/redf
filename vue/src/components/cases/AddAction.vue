<template>
  <v-dialog v-model="dialogVisible" max-width="800">
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
            <v-row dense>
              <v-col cols="6">
                <v-text-field
                  type="number"
                  v-model="caseAction.amount"
                  :label="$t('cases.amount')"
                  dense
                  outlined
                >
                  <template v-slot:append>
                    <v-icon> mdi-cash </v-icon>
                  </template>
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  type="number"
                  v-model="caseAction.percentage"
                  :label="$t('cases.percentageLose')"
                  dense
                  outlined
                >
                  <template v-slot:append>
                    <v-icon> mdi-percent </v-icon>
                  </template>
                </v-text-field>
              </v-col>
              <v-col cols="6">
                <v-select
                  :items="status"
                  :label="$t('tables.status')"
                  :item-text="(item) => item.key"
                  :item-value="(item) => item.value"
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
                <v-select
                  :items="courts"
                  :label="$t('tables.court')"
                  dense
                  outlined
                  v-model="caseAction.court_name"
                >
                </v-select>
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

export default {
  components: {},
  props: {
    dialogVisible: Boolean,
    formRequestId: Number,
  },
  data() {
    return {
      loading: false,
      isLoading: false,
      dateDialog: false,
      caseAction: {
        form_request_id: this.formRequestId,
        amount: "",
        percentage: "",
        details: "",
        court_name: "",
        status: "",
        date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
          .toISOString()
          .substr(0, 10),
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
  watch: {},
  created() {
    this.getCourts();
  },
  computed: {
    ...mapState("cases", ["courts"]),
  },
  methods: {
    ...mapActions("cases", ["saveFormInformation", "getCourts"]),
    closeDialog() {
      this.$emit("closeActionDialog");
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
        court_name: this.caseAction.court_name,
      };
      this.isLoading = true;
      await this.saveFormInformation(data)
        .then((response) => {
          console.log("aaaaaa");
          makeToast("success", response.data.message);
          this.closeDialog();
        })
        .catch(() => {})
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>
