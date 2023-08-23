<template>
  <div class="d-flex flex-column flex-grow-1" v-if="formData">
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1">
          {{ $t("general.edit") + " " + selectedTitle }}
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step :complete="e1 > 2" step="2">
          {{ $t("general.info") + " " + selectedTitle }}
        </v-stepper-step>
        <v-divider></v-divider>

        <v-stepper-step step="3">
          {{ $t("cases.adviceActions") }}
        </v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <v-card class="mb-12" v-if="!initialLoading">
            <v-card-text>
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
                :label="$t('cases.adviceName')"
                :required="true"
                :rules="[requiredRule]"
                dense
              ></v-text-field>
            </v-card-text>
          </v-card>
          <v-btn color="primary" @click="saveAdviceInfo">
            {{ $t("general.continue") }}
          </v-btn>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card v-if="!initialLoading">
            <v-tabs v-model="activeTab">
              <v-tab v-for="(tab, index) in pagesValues" :key="index">{{
                tab.title
              }}</v-tab>
            </v-tabs>
            <v-card-text>
              <v-tabs-items v-model="activeTab">
                <v-tab-item
                  v-for="(tab, tabIndex) in pagesValues"
                  :key="tabIndex"
                >
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
                              show-size
                              :label="getInputLabel(input)"
                              @change="(file) => handleFileUpload(file, input)"
                              @click:clear="handleFileClear(input)"
                              :required="input.required"
                              :rules="input.required ? [requiredRule] : []"
                              :error-messages="errorMessage(input)"
                            >
                            </v-file-input>
                            <div
                              class="mt-1 d-flex justify-content-between align-item-center"
                              v-if="
                                input.preview && input.preview === input.value
                              "
                            >
                              <h6>{{ fileInfo(input.preview).name }}</h6>
                              <img
                                v-if="
                                  fileInfo(input.preview).type === 'png' ||
                                  fileInfo(input.preview).type === 'jpg' ||
                                  fileInfo(input.preview).type === 'jpeg'
                                "
                                width="50"
                                height="50"
                                :src="input.preview"
                                alt="file preview"
                              />
                              <a
                                v-else-if="
                                  fileInfo(input.preview).type === 'pdf'
                                "
                                :href="input.preview"
                                target="_blank"
                              >
                                <v-icon> mdi-file-pdf-box </v-icon>
                              </a>
                              <a
                                v-else-if="
                                  fileInfo(input.preview).type === 'doc' ||
                                  fileInfo(input.preview).type === 'docx'
                                "
                                :href="input.preview"
                                target="_blank"
                              >
                                <v-icon> mdi-file-word-outline </v-icon>
                              </a>
                              <a
                                v-else-if="
                                  fileInfo(input.preview).type === 'xls' ||
                                  fileInfo(input.preview).type === 'xlsx'
                                "
                                :href="input.preview"
                                target="_blank"
                              >
                                <v-icon> mdi-file-excel </v-icon>
                              </a>
                            </div>
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
                              v-model="input.value"
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
                          <template v-else-if="input.type === 'checkbox'">
                            <label>
                              {{ input.label }}
                            </label>
                            <v-checkbox
                              v-for="(option, optionIndex) in input.childList"
                              v-model="input.value"
                              :label="option.text"
                              :value="option.text"
                              :required="input.required"
                              :rules="input.required ? [requiredRule] : []"
                              :error-messages="errorMessage(input)"
                              :class="optionIndex > 0 ? 'mt-0' : ''"
                            ></v-checkbox>
                          </template>
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </v-tab-item>
              </v-tabs-items>
            </v-card-text>
            <v-card-actions class="px-5 pb-4">
              <v-btn
                color="primary"
                :disabled="isSubmitingForm"
                :loading="isSubmitingForm"
                @click="saveForm"
                >{{ $t("general.saveChanges") }}</v-btn
              >
            </v-card-actions>
          </v-card>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card class="mb-12">
            <v-card-title> </v-card-title>
            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-row dense v-if="lastAction" class="mb-2">
                    <v-col cols="12">
                      <v-expansion-panels multiple>
                        <v-expansion-panel>
                          <v-expansion-panel-header>
                            <h5>{{ $t("general.last_action") }}</h5>
                          </v-expansion-panel-header>
                          <v-expansion-panel-content>
                            <v-row class="mb-1" dense>
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
                                  label="Solo"
                                  disabled
                                  hide-details
                                  dense
                                ></v-text-field>
                              </v-col>
                            </v-row>

                            <v-row class="mb-1" dense>
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

                            <v-row dense>
                              <v-col cols="12" sm="3">
                                <h6 class="mt-1 mb-0 c-h6">
                                  {{ $t("tables.status") }}
                                </h6>
                              </v-col>
                              <v-col cols="12" sm="9">
                                <v-chip
                                  :color="getStatusColor(lastAction?.status)"
                                  label
                                  text-color="white"
                                >
                                  {{ $t(`general.${lastAction?.status}`) }}
                                </v-chip>
                              </v-col>
                            </v-row>
                          </v-expansion-panel-content>
                        </v-expansion-panel>
                      </v-expansion-panels>
                    </v-col>
                  </v-row>
                  <v-row>
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
  name: "EditLegalAdvice",

  data() {
    return {
      e1: 1,
      selectedTitle: "",
      dateDialog: false,
      formData: null,
      caseNumber: "",
      caseName: "",
      case_id: "",
      initialLoading: false,
      isLoading: false,
      isSubmitingForm: false,
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
      lastAction: null,
      status: [
        { key: "error", value: 0 },
        { key: "confirmed", value: 1 },
        { key: "pending", value: 2 },
      ],
    };
  },
  created() {
    this.init();
  },
  watch: {
    e1(val) {
      if (val === 4) {
        this.getCourts();
      }
    },
  },
  computed: {
    ...mapState("cases", [
      "pagesValues",
      "selectedForm",
      "courts",
      "caseTypes",
    ]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("auth", ["user"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("cases", [
      "getPagesValues",
      "validateFormData",
      "updatePages",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
    ]),
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
        text: this.$t("general.edit") + " " + this.selectedForm.name,
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
      this.getPagesValues(id)
        .then((data) => {
          this.setCurrentBread();
          this.formData = data;
          this.lastAction = data?.lastFormRequestInformation || null;
          this.caseName = data.name;
          this.caseNumber = data.form_request_number;
          this.formRequestId = this.formData.id;
          if (this.formData.form_request_side) {
            this.sidesInfo.claimant_id =
              this.formData?.form_request_side?.claimant_id;
            this.sidesInfo.defendant_id =
              this.formData?.form_request_side?.defendant_id;
            this.sidesInfo.civil = this.formData?.form_request_side?.civil;
            this.sidesInfo.department_id =
              this.formData?.form_request_side?.department_id;
          }
          if (this.formData.form_request_information) {
            this.caseAction.amount =
              this.formData?.form_request_information?.amount;
            this.caseAction.court =
              this.formData?.form_request_information?.court;
            this.caseAction.date =
              this.formData?.form_request_information?.date;
            this.caseAction.percentage =
              this.formData?.form_request_information?.percentage;
            this.caseAction.status =
              this.formData?.form_request_information?.status || null;
            this.caseAction.details =
              this.formData?.form_request_information?.details;
          }
          console.log(data);
        })
        .finally((_) => {
          this.initialLoading = false;
        });
    },
    getStatusColor(status) {
      const colors = {
        processing: "blue",
        pending: "orange",
        accepted: "green",
      };

      return colors[status] || "primary";
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
    handleFileUpload(file, input) {
      if (file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          input["value"] = reader.result;
        };
      }
    },
    fileInfo(filePath) {
      const filePathArr = filePath.split(".");
      const fileInfo = filePathArr.pop();
      const fileName = filePath.split("/").pop();
      const info = { name: fileName || "", type: fileInfo || "" };
      return info;
    },

    handleFileClear(input) {
      if (input.preview) {
        input.value = input.preview;
      }
    },
    loadFile(filePath, input) {
      console.log(input);

      fetch("/" + filePath)
        .then((response) => response.blob())
        .then((blob) => {
          const file = new File([blob], "photo.png", { type: "image/png" });
          this.handleFileUpload(file, input);
          return file;
        })
        .catch((error) => {
          console.error("Error loading file:", error);
        });
    },
    saveAdviceInfo() {
      if (!this.caseName || !this.caseNumber) {
        makeToast("error", "Advice name and number ");
        return;
      }
      this.e1 = 2;
    },
    async saveForm() {
      const { id } = this.$route.params;
      const { formType: currentFormId } = this.$route.params;
      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        const saveResult = await this.updatePages({
          caseName: this.caseName,
          caseNumber: this.caseNumber,
          case_id: this.case_id,
          formId: id,
        });
        if (saveResult) {
          this.e1 = 3;
        } else {
          console.log("Failed To save data");
        }
        this.isSubmitingForm = false;

        // this.$router.push({ path: `/cases/${currentFormId}` });
      } else {
        this.showErrors = true;
        this.isSubmitingForm = false;

        console.log("some fields is required");
      }
    },
    async storeFormInformation() {
      this.isLoading = true;
      // this.caseAction.dates = this.caseAction.dates.map(
      //   (cdate) => cdate.caseDate
      // );
      let data = {
        form_request_id: this.formRequestId,
        amount: this.caseAction.amount,
        percentage: this.caseAction.percentage,
        details: this.caseAction.details,
        status: this.caseAction.status,
        date: this.caseAction.date,
        court: this.caseAction.court,
      };

      // if (await this.validateFormData()) {
      const result = await this.saveFormInformation(data);

      if (result) {
        this.isLoading = false;
        const { formType: currentFormId } = this.$route.params;
        makeToast("success", "تم تحديث بيانات القضية");
        this.$router.push(`/cases/${currentFormId}`);
      } else {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped></style>
