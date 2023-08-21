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
              <v-col cols="6" v-for="(date, k) in caseAction.dates" :key="k">
                <v-text-field
                  dense
                  outlined
                  type="date"
                  :label="$t('cases.courtDate')"
                  v-model="date.caseDate"
                >
                </v-text-field>
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

        <v-btn @click="storeFormAction" color="primary">
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
import { mapActions } from "vuex";

export default {
  components: {},
  props: {
    dialogVisible: Boolean,
    formRequestId: Number,
  },
  data() {
    return {
      loading: false,
      caseAction: {
        form_request_id: this.formRequestId,
        amount: "",
        percentage: "",
        details: "",
        status: "",
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
  methods: {
    ...mapActions("cases", ["saveFormInformation"]),
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
        dates: this.caseAction.dates,
        status: this.caseAction.status,
      };
      await this.saveFormInformation(data)
        .then((response) => {
          this.isLoading = false;
          makeToast("success", response.data.message);
          this.closeDialog();
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
  },
};
</script>
