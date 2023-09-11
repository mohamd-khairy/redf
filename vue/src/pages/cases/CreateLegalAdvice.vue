<template>
  <div>
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1">
          {{ $t("general.create") + " " + selectedTitle }}
        </v-stepper-step>
        <v-divider></v-divider>

        <v-stepper-step step="2">
          {{ $t("cases.adviceActions") }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <div class="mt-2" v-if="!initialLoading">
            <div class="mt-2">
              <v-text-field
                outlined
                v-model="caseName"
                :label="$t('cases.adviceName')"
                :required="true"
                :rules="[requiredRule]"
                dense
              ></v-text-field>
              <v-text-field
                outlined
                type="number"
                v-model="caseNumber"
                :label="$t('cases.adviceNumber')"
                :required="true"
                :rules="[requiredRule]"
                dense
              ></v-text-field>
              <v-checkbox
                v-model="caseCheck"
                :label="$t('cases.belongToCase')"
              ></v-checkbox>
              <v-select
                v-if="caseCheck"
                :items="formRequests"
                :label="$t('cases.cases')"
                :item-text="(item) => item.name"
                :item-value="(item) => item.id"
                hide-details
                dense
                outlined
                v-model="case_id"
                clearable
              >
              </v-select>
            </div>
            <v-tabs class="mt-2" v-model="activeTab">
              <v-tab v-for="(tab, index) in pages" :key="index">{{
                  tab.title
                }}</v-tab>
            </v-tabs>
            <v-tabs-items v-model="activeTab">
              <v-tab-item v-for="(tab, tabIndex) in pages" :key="tabIndex">
                <v-form>
                  <v-container>
                    <v-row dense>
                      <v-col
                        v-for="(input, inputIndex) in tab.items"
                        :key="inputIndex"
                        :cols="inputWidth(input.width)"
                      >
                        <template v-if="input.type === 'text'">
                          <v-text-field
                            outlined
                            v-model="input.value"
                            :label="getInputLabel(input)"
                            :required="input.required"
                            :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"
                            dense
                          ></v-text-field>
                        </template>
                        <template v-else-if="input.type === 'textarea'">
                          <v-textarea
                            outlined
                            dense
                            v-model="input.value"
                            :label="getInputLabel(input)"
                            :required="input.required"
                            :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"
                          ></v-textarea>
                        </template>
                        <template v-else-if="input.type === 'file'">
                          <v-file-input
                            outlined
                            dense
                            counter
                            show-size
                            :label="getInputLabel(input)"
                            @change="(file) => handleFileUpload(file, input)"
                            :required="input.required"
                            :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"
                          >
                          </v-file-input>
                        </template>
                        <template v-else-if="input.type === 'select'">
                          <v-select
                            v-model="input.value"
                            :items="input.childList"
                            item-text="text"
                            :label="getInputLabel(input)"
                            :required="input.required"
                            :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"
                            outlined
                            dense
                          ></v-select>
                        </template>
                        <template v-else-if="input.type === 'radio'">
                          <v-radio-group
                            v-model="input.selectedOption"
                            :label="getInputLabel(input)"
                            :required="input.required"
                            :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"
                          >
                            <v-radio
                              v-for="(option, optionIndex) in input.childList"
                              :key="optionIndex"
                              :label="option.text"
                              :value="option.text"
                            ></v-radio>
                          </v-radio-group>
                        </template>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-form>
              </v-tab-item>
            </v-tabs-items>
          </div>
          <v-card-actions>
            <v-btn color="primary" @click="saveForm">
              {{ $t("general.continue") }}
            </v-btn>
            <!-- <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn> -->
          </v-card-actions>
        </v-stepper-content>

        <v-stepper-content step="2">
          <v-card class="mb-12">
            <v-card-title> </v-card-title>
            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-row>
                    <v-col cols="6">
                      <v-select
                        :items="caseTypes"
                        item-text="title"
                        item-value="value"
                        :label="$t('tables.status')"
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
                  <!-- <v-row v-for="(date, k) in caseAction.dates" :key="k">
                    <v-col cols="12">
                      <v-text-field
                        dense
                        outlined
                        type="date"
                        :label="$t('cases.courtDate')"
                        v-model="date.caseDate"
                      >
                      </v-text-field>
                    </v-col>
                    <v-col cols="1">
                      <v-btn
                        @click="removeDate(k)"
                        v-show="k || (!k && caseAction.dates.length > 1)"
                      >
                        <v-icon color="green"> mdi-minus </v-icon>
                      </v-btn>
                    </v-col>
                    <v-col cols="1">
                      <v-btn
                        @click="addDate(k)"
                        v-show="k == caseAction.dates.length - 1"
                      >
                        <v-icon color="red"> mdi-plus </v-icon>
                      </v-btn>
                    </v-col>
                  </v-row> -->
                </div>
              </div>
            </v-card-text>
          </v-card>
          <v-btn @click="storeFormInformation" color="primary">
            {{ $t("general.save") }}
          </v-btn>

          <v-btn text> {{ $t("general.cancel") }} </v-btn>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  name: "CreateLegalAdvice",
  data() {
    return {
      e1: 1,
      selectedTitle: "",
      dateDialog: false,
      caseName: "",
      caseNumber: "",
      case_id: "",
      initialLoading: false,
      isLoading: false,
      isSubmitingForm: false,
      caseCheck: false,
      formRequestId: null,

      breadcrumbs: [
        {
          text: this.$t("menu.requests"),
          disabled: false,
          href: "#",
        },
      ],
      activeTab: null,
      requiredRule: (v) => !!v || this.$t("general.required_input"),
      showErrors: false,
      caseAction: {
        form_request_id: "",
        details: "",
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
      rules: {
        required: (value) =>
          (value && Boolean(value)) || this.$t("general.fieldRequired"),
      },
      errors: {},
      status: [
        { key: "error", value: 0 },
        { key: "confirmed", value: 1 },
        { key: "pending", value: 2 },
      ],
    };
  },

  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("cases.casesList"),
    });
    this.init();
    this.fetchCases()
  },
  watch: {
    e1(val) {
      if (val === 2) {
        this.getCourts();
      }
    },
  },
  computed: {
    ...mapState("cases", ["pages", "selectedForm", "courts", "caseTypes",'formRequests']),
    ...mapState("auth", ["user"]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("departments", ["departments"]),

  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("departments", ["getDepartments"]),
    ...mapActions("cases", [
      "getPages",
      "validateFormData",
      "savePages",
      "userDepartment",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
      'getFormRequests'
    ]),
    fetchCases(){
      let data = {
        template_id: 1,
        pageSize: -1,
      };
      this.getFormRequests(data)
        .then(() => {
        })
        .catch(() => {
        });
    },
    addDate(index) {
      this.caseAction.dates.push({ caseDate: "" });
    },
    removeDate(index) {
      this.caseAction.dates.splice(index, 1);
    },

    setCurrentBread() {
      const { formType: currentFormId } = this.$route.params;
      const currentPage = this.navTemplates.find((nav) => {
        return nav.id === +currentFormId;
      });
      if (currentPage) {
        this.breadcrumbs.push({
          text: currentPage.title,
          disabled: false,
          href: `/cases/${currentFormId}`,
        });
      }
      this.breadcrumbs.push({
        text: this.$t(this.selectedForm.name),
      });
      this.selectedTitle = this.$t(this.selectedForm.name);
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: this.$t("cases.casesList"),
      });
    },
    init() {
      const { id } = this.$route.params;
      if (!id) {
        this.$router.push({ name: "dashboard-analytics" });
      }
      this.initialLoading = true;
      this.getPages(id)
        .then((_) => {
          this.setCurrentBread();
        })
        .finally((_) => {
          this.initialLoading = false;
        });
    },
    handleFileUpload(file, input) {
      if (file) {
        const fileName = file.name.split(".")[0];
        const fileExtension = file.name.split(".")[1];
        input["file_name"] = fileName + "." + fileExtension;
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          input["value"] = reader.result;
        };
      }
    },
    getInputLabel(input) {
      const inputLabel = input.label;
      const isRequired = input.required;
      if (isRequired) return inputLabel + " *";
      return inputLabel;
    },
    inputWidth(width) {
      const inputWidth = width.split("-")[1];
      return inputWidth || 12;
    },
    errorMessage(input) {
      const msg = this.$t("general.required_input");
      return this.showErrors && input.required && !input.value ? [msg] : [];
    },
    saveAdviceInfo() {
      if (!this.caseName || !this.caseNumber) {
        makeToast("failed", "advice name and number ");
        return;
      }
      this.e1 = 2;
    },
    async saveForm() {
      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        const result = await this.savePages({
          caseName: this.caseName,
          caseNumber: this.caseNumber,
          case_id: this.case_id,
          type: 'consultation'
        });
        if (result) {
          this.isSubmitingForm = false;
          this.formRequestId = result.data?.data?.formRequest?.id;
          this.e1 = 2;
          // makeToast("success", response.data.message);
        } else {
          makeToast("error", "Failed to save data");
        }
      } else {
        this.showErrors = true;
        this.isSubmitingForm = false;

        console.log("some fields is required");
      }
    },
    async storeFormInformation() {
      this.isLoading = true;

      let data = {
        form_request_id: this.formRequestId,
        details: this.caseAction.details,
        status: this.caseAction.status,
        date: this.caseAction.date,
      };

      // if (await this.validateFormData()) {
      const result = await this.saveFormInformation(data);
      if (result) {
        this.isLoading = false;
        const { formType: currentFormId } = this.$route.params;
        makeToast("success", "تم انشاء استشارة بنجاح");
        this.$router.push(`/cases/2`);
      } else {
        this.isLoading = false;
      }
      // } else {
      //   this.showErrors = true;
      //   this.isSubmitingForm = false;
      //   console.log("some fields is required");
      // }
    },
  },
};
</script>

<style scoped></style>

