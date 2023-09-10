<template>
  <div class="d-flex flex-column flex-grow-1" style="margin: 50px">
    <div v-if="formData">
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
            <div class="mt-2" v-if="!initialLoading">
              <div class="mt-2">
                <div class="d-flex">
                  <v-select
                    :items="formRequests"
                    :label="$t('cases.belongToCase')"
                    :item-text="(item) => item.name"
                    :item-value="(item) => item.id"
                    hide-details
                    dense
                    outlined
                    v-model="caseId"
                    clearable
                    :required="true"
                    :error-messages="stepOneValidation(caseId)"
                    :rules="[requiredRule]"
                  >
                  </v-select>

                  <v-spacer></v-spacer>
                  <v-btn
                    v-if="caseId"
                    color="primary"
                    outlined
                    @click="openCaseInfoDialog()"
                    >{{ $t("cases.view_info") }}</v-btn
                  >
                </div>
              </div>
            </div>
            <v-card-actions>
              <v-btn color="primary" @click="updateCaseInfo">
                {{ $t("general.continue") }}
              </v-btn>
              <!-- <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn> -->
            </v-card-actions>
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
                                @change="
                                  (file) => handleFileUpload(file, input)
                                "
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
                                  v-for="(
                                    option, optionIndex
                                  ) in input.childList"
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
                  >{{ $t("general.continue") }}</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-stepper-content>

          <v-stepper-content step="3">
            <iframe
              v-if="uploadedFileType === 'pdf'"
              :src="docUrl"
              width="100%"
              height="500"
            ></iframe>
            <div v-else id="filePreview-container"></div>

            <v-card-actions>
              <v-btn color="primary">
                {{ $t("general.acceptRequest") }}
              </v-btn>
              <v-btn color="primary">
                {{ $t("general.returnRequest") }}
              </v-btn>
              <v-btn color="primary">
                {{ $t("general.refuseRequest") }}
              </v-btn>

              <v-btn color="grey" @click="stepBack" class="ms-2">
                {{ $t("general.back") }}
              </v-btn>
            </v-card-actions>
          </v-stepper-content>
        </v-stepper-items>
      </v-stepper>

      <CaseInfoDialog
        :dialogVisible="caseInfoDialog"
        :case-id="caseId"
        v-if="caseInfoDialog"
        @closeInfoDialog="caseInfoDialog = false"
      />
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";
import CaseInfoDialog from "@/components/cases/CaseInfoDialog";
import * as docx from "docx-preview";

export default {
  name: "Edit",
  components: {
    CaseInfoDialog,
  },
  data() {
    return {
      caseId: "",
      caseInfoDialog: false,
      fileUploaded: null,
      uploadedFileType: "",
      e1: 1,
      selectedTitle: "",
      dateDialog: false,
      formData: null,
      caseNumber: "",
      caseName: "",
      case_id: "",
      caseCheck: false,
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
    };
  },
  created() {
    this.init();
    this.fetchCases();
  },
  watch: {
    e1(val) {
      if (val === 3) {
        // console.log(this.uploadedFileType);
        if (this.uploadedFileType === "pdf") {
          this.getPagesValues(this.formRequestId).then((data) => {
            const url = data.form_page_item_fill[1].value;
            this.docUrl = url;
            console.log(this.docUrl);
          });
        } else {
          docx
            .renderAsync(
              this.fileUploaded,
              document.getElementById("filePreview-container")
            )
            .then((x) => console.log("docx: finished"));
        }
      }
    },
  },
  computed: {
    ...mapState("cases", [
      "pagesValues",
      "selectedForm",
      "courts",
      "caseTypes",
      "formRequests",
    ]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("auth", ["user"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("cases", [
      "getPagesValues",
      "validateFormData",
      "updateBackPages",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
      "getFormRequests",
    ]),
    stepOneValidation(value) {
      const msg = this.$t("general.required_input");
      return this.stepOneErrors && !value ? [msg] : [];
    },
    stepBack() {
      if (this.e1 > 1) {
        this.e1--;
        this.stepOneErrors = false;
      }
      return;
    },
    openCaseInfoDialog(id) {
      this.caseInfoDialog = true;
    },
    async updateCaseInfo() {
      if (!this.caseId) {
        this.stepOneErrors = true;
        this.showErrors = true;
        return;
      }
      this.getCaseTimeline(this.caseId);
      this.showErrors = false;
      this.stepOneErrors = false;
      this.e1 = 2;
    },
    async getCaseTimeline(id) {
      try {
        this.loading = true;
        const response = await this.$axios.get(`get-form-Requests/${id}`);

        const { form_request_side, form_request_number, name } =
          response?.data?.data;

        this.formRequestSide = form_request_side;
        this.caseName = name;
        this.caseNumber = form_request_number;

        this.caseClaimant = {
          id: form_request_side.claimant.id,
          name: form_request_side.claimant.name,
        };
      } catch (error) {
        console.log(error);
      } finally {
        this.loading = false;
      }
    },
    fetchCases() {
      let data = {
        template_id: 1,
        pageSize: -1,
      };
      this.getFormRequests(data)
        .then(() => {})
        .catch(() => {});
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
      this.$axios.get(`get-file/${id}`).then((response) => {
        const base64String = response.data.data;
        const binaryString = atob(base64String);
        const uint8Array = new Uint8Array(binaryString.length);
        for (let i = 0; i < binaryString.length; i++) {
          uint8Array[i] = binaryString.charCodeAt(i);
        }
        const blob = new Blob([uint8Array], {
          type: "application/octet-stream",
        });
        this.fileUploaded = blob;
      });

      this.getPagesValues(id)
        .then((data) => {
          this.setCurrentBread();
          this.formData = data;
          this.lastAction = data?.lastFormRequestInformation || null;
          this.caseId = data.request?.formable_id;
          const url = data.form_page_item_fill[1].value;
          this.docUrl = url;
          const pathArray = url.split("/");
          const fileName = pathArray[pathArray.length - 1];

          // Use the 'split' method to separate the file name and its extension
          const fileNameParts = fileName.split(".");
          this.uploadedFileType = fileNameParts[fileNameParts.length - 1];

          // this.fileUploaded = url;

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
        this.fileUploaded = file;
        const fileName = file.name.split(".")[0];
        const fileExtension = file.name.split(".")[1];
        this.uploadedFileType = fileExtension.toLowerCase();
        input["file_name"] = fileName + "." + fileExtension;
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
    async saveForm() {
      const { id } = this.$route.params;
      const { formType: currentFormId } = this.$route.params;
      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        const saveResult = await this.updateBackPages({
          caseName: null,
          caseNumber: null,
          caseDate: null,
          type: "related_case",
          case_id: this.caseId,
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
