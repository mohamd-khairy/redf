<template>
  <div>
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1">
          {{ $t("general.create") + " " + selectedTitle }}
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step :complete="e1 > 2" step="2">
          {{ $t("general.info") + " " + selectedTitle }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="e1 > 3" step="3">
          {{ $t("cases.sidesInfo") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step step="4">
          {{ $t("cases.caseActions") }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <div class="mt-2">
            <v-text-field
              class="mb-2"
              v-model="caseName"
              :label="$t('cases.caseName')"
              outlined
              :required="true"
              :error-messages="stepOneValidation(caseName)"
              dense
              :rules="[requiredRule]"
            ></v-text-field>
            <v-text-field
              outlined
              type="number"
              class="mb-2"
              v-model="caseNumber"
              @keydown="handleInput"
              :label="$t('cases.caseNumber')"
              :required="true"
              :rules="[requiredRule]"
              :error-messages="stepOneValidation(caseNumber)"
              dense
            ></v-text-field>
          </div>
          <v-card-actions class="px-2">
            <v-btn color="primary" @click="saveCaseInfo">
              {{ $t("general.continue") }}
            </v-btn>
          </v-card-actions>
        </v-stepper-content>
        <v-stepper-content step="2">
          <div class="mt-2" v-if="!initialLoading">
            <v-tabs v-model="activeTab">
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
                            :rules="input.required ? [requiredRule] : []"
                            :required="input.required"
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
            <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn>
          </v-card-actions>
        </v-stepper-content>
        <v-stepper-content step="3">
          <div class="mt-2">
            <v-card-title>
              <v-flex class="text-left">
                <v-btn color="primary" large @click.stop="dialog = true">
                  <v-icon> mdi-plus </v-icon>
                  {{ $t("cases.addUser") }}
                </v-btn>
              </v-flex>
            </v-card-title>
            <v-card-text>
              <v-row dense>
                <v-col cols="12">
                  <v-select
                    :items="claimantUsers"
                    :label="$t('cases.claimant')"
                    :item-text="(item) => item.name"
                    :item-value="(item) => item.id"
                    dense
                    outlined
                    v-model="sidesInfo.claimant_id"
                    :rules="[rules.required]"
                    :error-messages="stepOneValidation(sidesInfo.claimant_id)"
                    clearable
                    @click:clear="clearClaimantSelect"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-select
                    :items="defendantUsers"
                    :label="$t('cases.defendant')"
                    :item-text="(item) => item.name"
                    :item-value="(item) => item.id"
                    dense
                    outlined
                    v-model="sidesInfo.defendant_id"
                    :rules="[rules.required]"
                    :error-messages="stepOneValidation(sidesInfo.defendant_id)"
                    clearable
                    @click:clear="clearDefendantSelect"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-select
                    :items="departments"
                    :label="$t('tables.department')"
                    :item-text="(item) => item.name"
                    :item-value="(item) => item.id"
                    disabled
                    dense
                    outlined
                    v-model="sidesInfo.department_id"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    type="number"
                    v-model="sidesInfo.civil"
                    :label="$t('cases.civil')"
                    disabled
                    dense
                    outlined
                  ></v-text-field>
                </v-col>
              </v-row>
            </v-card-text>
          </div>

          <v-card-actions>
            <v-btn color="primary" @click="storeRequestSide">
              {{ $t("general.continue") }}
            </v-btn>
            <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn>
          </v-card-actions>
        </v-stepper-content>

        <v-stepper-content step="4">
          <div class="d-flex flex-column flex-sm-row">
            <div class="flex-grow-1 pt-2 pa-sm-2">
              <v-row dense>
                <v-col cols="6">
                  <v-text-field
                    type="number"
                    v-model="caseAction.amount"
                    :label="$t('cases.amount')"
                    outlined
                    dense
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
                    :items="caseTypes"
                    item-text="title"
                    item-value="value"
                    :label="$t('tables.status')"
                    dense
                    outlined
                    required="true"
                    :rules="[rules.required]"
                    :error-messages="stepOneValidation(caseAction.status)"
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
                        required="true"
                        :rules="[rules.required]"
                        :error-messages="stepOneValidation(caseAction.date)"
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
                    item-text="title"
                    item-value="value"
                    dense
                    outlined
                    v-model="caseAction.court"
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
          <v-card-actions>
            <v-btn @click="storeFormInformation" color="primary">
              {{ $t("general.save") }}
            </v-btn>

            <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn>
          </v-card-actions>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>

    <add-user-dialog v-model="dialog"></add-user-dialog>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import AddUserDialog from "../../components/cases/AddUserDialog";
import { makeToast } from "@/helpers";

export default {
  name: "CreateCase",
  components: { AddUserDialog },
  data() {
    return {
      e1: 1,
      selectedTitle: "",
      dateDialog: false,
      caseNumber: "",
      caseName: "",
      initialLoading: false,
      isLoading: false,
      isSubmitingForm: false,
      users: [],
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
      stepOneErrors: false,
      sidesInfo: {
        claimant_id: "",
        defendant_id: "",
        civil: "",
        department_id: "",
      },
      caseAction: {
        amount: "",
        form_request_id: "",
        percentage: "",
        details: "",
        status: "",
        court: "",
        date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
          .toISOString()
          .substr(0, 10),
        dates: [
          {
            caseDate: "",
          },
        ],
      },
      dialog: false,
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
    this.fetchUsers();
    this.fetchDepartments();

    this.$root.$on("userCreated", () => {
      this.fetchUsers();
    });
  },
  watch: {
    e1(val) {
      if (val === 4) {
        this.getCourts();
      }
    },
  },
  computed: {
    ...mapState("cases", ["pages", "selectedForm", "courts", "caseTypes"]),
    ...mapState("auth", ["user"]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("departments", ["departments"]),

    defendantUsers() {
      // return this.sidesInfo.claimant_id
      //   ? this.users.filter((obj) => {
      //       return obj.id !== this.sidesInfo.claimant_id;
      //     })
      //   : this.users;
      if (this.sidesInfo.claimant_id) {
        const user = this.users.find(
          (user) => user.id === this.sidesInfo.claimant_id
        );
        const civilNumber = user?.user_information?.civil_number || null;
        if (civilNumber) {
          this.sidesInfo.civil = civilNumber;

          return this.users.filter((user) => !user.user_information);
        }
        this.sidesInfo.department_id = user.department_id;
        return this.users.filter((user) => user.user_information);
      }
      return this.users;
    },
    claimantUsers() {
      // return this.sidesInfo.defendant_id
      //   ? this.users.filter((obj) => {
      //       return obj.id !== this.sidesInfo.defendant_id;
      //     })
      //   : this.users;
      if (this.sidesInfo.defendant_id) {
        const user = this.users.find(
          (user) => user.id === this.sidesInfo.defendant_id
        );
        const civilNumber = user?.user_information?.civil_number || null;
        if (civilNumber) {
          this.sidesInfo.civil = civilNumber;
          this.claimantType = "civil";
          return this.users.filter((user) => !user.user_information);
        }
        this.sidesInfo.department_id = user.department_id;
        return this.users.filter((user) => user.user_information);
      }
      return this.users;
    },
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("users", ["getUserType"]),
    ...mapActions("departments", ["getDepartments"]),
    ...mapActions("cases", [
      "getPages",
      "validateFormData",
      "savePages",
      "userDepartment",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
    ]),
    addDate(index) {
      this.caseAction.dates.push({ caseDate: "" });
    },
    handleInput(event) {
      if (event.key.toLowerCase() === "e") {
        event.preventDefault();
      }
    },
    removeDate(index) {
      this.caseAction.dates.splice(index, 1);
    },
    stepBack() {
      if (this.e1 > 1) {
        this.e1--;
        this.stepOneErrors = false;
      }
      return;
    },
    getUserDepartment(id) {
      this.isLoading = true;
      let data = {
        user_id: id,
      };
      this.userDepartment(data)
        .then((response) => {
          this.isLoading = false;
          const userDepartmentId = response.data?.data?.department?.id || "";
          const userCivilId =
            response.data?.data?.userInformation?.civil_number || "";

          this.sidesInfo.department_id = userDepartmentId;
          this.sidesInfo.civil = userCivilId;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    clearClaimantSelect() {
      const user = this.users.find(
        (user) => user.id === this.sidesInfo.claimant_id
      );
      if (user.user_information) {
        this.sidesInfo.civil = null;
      } else {
        this.sidesInfo.department_id = null;
      }
    },
    clearDefendantSelect() {
      const user = this.users.find(
        (user) => user.id === this.sidesInfo.defendant_id
      );
      if (user.user_information) {
        this.sidesInfo.civil = null;
      } else {
        this.sidesInfo.department_id = null;
      }
    },

    fetchUsers() {
      this.isLoading = true;
      this.getUserType()
        .then((response) => {
          this.isLoading = false;
          this.users = response.data.data.users;
          // this.claimantUsers = response.data.data.users
          // this.defendantUsers = response.data.data.users
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchDepartments() {
      this.isLoading = true;
      let data = {
        pageSize: -1,
      };
      this.getDepartments(data)
        .then((response) => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
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
    stepOneValidation(value) {
      const msg = this.$t("general.required_input");
      return this.stepOneErrors && !value ? [msg] : [];
    },
    saveCaseInfo() {
      if (!this.caseName || !this.caseNumber) {
        this.stepOneErrors = true;
        return;
      }
      this.stepOneErrors = false;
      this.e1 = 2;
    },
    async saveForm() {
      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        const result = await this.savePages({
          caseName: this.caseName,
          caseNumber: this.caseNumber,
        });
        if (result) {
          this.isSubmitingForm = false;
          this.formRequestId = result.data?.data?.formRequest?.id;
          this.showErrors = false;
          this.e1 = 3;
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
    async storeRequestSide() {
      this.isLoading = true;
      let data = {
        form_request_id: this.formRequestId,
        claimant_id: this.sidesInfo.claimant_id,
        defendant_id: this.sidesInfo.defendant_id,
      };

      // if (await this.validateFormData()) {
      const result = await this.saveRequestSide(data);
      if (result) {
        this.isLoading = false;
        this.e1 = 4;
        this.stepOneErrors = false;
      } else {
        this.stepOneErrors = true;
        this.isLoading = false;
      }
    },
    async storeFormInformation() {
      this.isLoading = true;

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
        makeToast("success", "تم انشاء القضية بنجاح");
        this.$router.push(`/cases/${currentFormId}`);
      } else {
        this.isLoading = false;
        this.stepOneErrors = true;
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
