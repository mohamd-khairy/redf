<template>
  <div v-if="formData">
    <v-stepper v-model="e1" class="mb-4">
      <v-stepper-header>
        <v-divider></v-divider>
        <v-stepper-step :complete="e1 > 1" step="1">
          {{ $t("general.info") + " " + selectedTitle }}
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step :complete="e1 > 2" step="2">
          {{ $t("cases.sidesInfo") }}
        </v-stepper-step>
        <v-divider></v-divider>
        <v-stepper-step step="3">
          {{ $t("cases.caseActions") }}
        </v-stepper-step>
      </v-stepper-header>
      <v-stepper-items>
        <v-stepper-content step="1">
          <div v-if="!initialLoading">
            <div class="mt-2">
              <v-text-field
                outlined
                v-model="caseName"
                :label="$t('cases.caseName')"
                :required="true"
                :error-messages="stepOneValidation(caseName)"
                :rules="[requiredRule]"
                dense
              ></v-text-field>
              <v-text-field
                outlined
                v-model="caseNumber"
                :label="$t('cases.caseNumber')"
                :required="true"
                :rules="[requiredRule]"
                :error-messages="stepOneValidation(caseNumber)"
                dense
              ></v-text-field>
            </div>
            <v-tabs v-model="activeTab">
              <v-tab v-for="(tab, index) in pagesValues" :key="index">{{
                tab.title
              }}</v-tab>
            </v-tabs>
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
                              v-else-if="fileInfo(input.preview).type === 'pdf'"
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
            <v-card-actions class="px-2">
              <v-btn
                color="primary"
                :disabled="isSubmitingForm"
                :loading="isSubmitingForm"
                @click="saveForm"
                >{{ $t("general.continue") }}</v-btn
              >
              <!-- <v-btn color="grey" @click="stepBack" class="ms-2">
                {{ $t("general.back") }}
              </v-btn> -->
            </v-card-actions>
          </div>
        </v-stepper-content>
        <v-stepper-content step="2">
          <div class="mt-2">
            <v-card-title>
              <v-flex class="text-left">
                <v-btn color="primary" large @click.stop="dialog = true">
                  <v-icon> mdi-plus </v-icon>
                  {{ $t("cases.addUser") }}
                </v-btn>
              </v-flex>
            </v-card-title>
            <div class="d-flex flex-column flex-sm-row">
              <div class="flex-grow-1 pt-2 pa-sm-2">
                <v-row>
                  <v-col cols="12">
                    <v-select
                      :items="claimantUsers"
                      :label="$t('cases.claimant')"
                      :item-text="(item) => item.name"
                      :item-value="(item) => item.id"
                      hide-details
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
                      hide-details
                      dense
                      outlined
                      v-model="sidesInfo.defendant_id"
                      :rules="[rules.required]"
                      :error-messages="
                        stepOneValidation(sidesInfo.defendant_id)
                      "
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
                      hide-details
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
              </div>
            </div>
          </div>

          <v-card-actions class="px-2">
            <v-btn color="primary" @click="storeRequestSide">
              {{ $t("general.continue") }}
            </v-btn>
            <v-btn color="grey" @click="stepBack" class="ms-2">
              {{ $t("general.back") }}
            </v-btn>
          </v-card-actions>
        </v-stepper-content>

        <v-stepper-content step="3">
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
                              {{ $t("cases.amount") }}
                            </h6>
                          </v-col>
                          <v-col cols="12" sm="9">
                            <v-text-field
                              dense
                              class="custom-disabled-input"
                              :value="lastAction?.amount || ''"
                              solo
                              label="Solo"
                              disabled
                              hide-details
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row class="mb-1" dense>
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
                              label="Solo"
                              disabled
                              hide-details
                            ></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row class="mb-1" dense>
                          <v-col cols="12" sm="3">
                            <h6 class="mt-1 mb-0 c-h6">
                              {{ $t("tables.court") }}
                            </h6>
                          </v-col>
                          <v-col cols="12" sm="9">
                            <v-text-field
                              class="custom-disabled-input"
                              :value="lastAction?.court || ''"
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
                              :color="
                                getStatusColor(
                                  lastAction?.status?.toLowerCase()
                                )
                              "
                              label
                              text-color="white"
                            >
                              {{
                                $t(
                                  `general.${lastAction?.status?.toLowerCase()}`
                                )
                              }}
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
                    :items="caseTypes"
                    :label="$t('tables.status')"
                    item-text="title"
                    item-value="value"
                    hide-details
                    dense
                    required="true"
                    :error-messages="stepOneValidation(caseAction.status)"
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
                        required="true"
                        :error-messages="stepOneValidation(caseAction.date)"
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
                    item-text="title"
                    item-value="value"
                    :label="$t('tables.court')"
                    hide-details
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
                <v-col cols="12">
                  <v-checkbox
                    v-model="sessionDate"
                    :label="$t('cases.add_session')"
                  ></v-checkbox>
                </v-col>
                <v-col cols="12" sm="12" v-if="sessionDate">
                  <v-dialog
                    ref="sessionDialog"
                    v-model="sessionDialog"
                    :return-value.sync="caseAction.sessionDate"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="caseAction.sessionDate"
                        :label="$t('cases.sessionDate')"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="caseAction.sessionDate" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="modal = false">
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="
                          $refs.sessionDialog.save(caseAction.sessionDate)
                        "
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-dialog>
                </v-col>
              </v-row>
            </div>
          </div>
          <v-card-actions class="px-2">
            <v-btn @click="storeFormInformation" color="primary">
              {{ $t("general.saveChanges") }}
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
import AddUserDialog from "../../components/cases/AddUserDialog";
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  name: "EditCase",
  components: { AddUserDialog },

  data() {
    return {
      e1: 1,
      selectedTitle: "",
      dateDialog: false,
      sessionDialog: false,
      sessionDate: false,
      formData: null,
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
      showErrors: false,
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
        sessionDate: null,
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
    ...mapState("cases", [
      "pagesValues",
      "selectedForm",
      "courts",
      "caseTypes",
    ]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("departments", ["departments"]),
    ...mapState("auth", ["user"]),
    defendantUsers() {
      if (this.sidesInfo.claimant_id) {
        const user = this.users.find(
          (user) => user.id === this.sidesInfo.claimant_id
        );
        const civilNumber = user?.user_information?.civil_number || null;
        if (civilNumber) {
          this.sidesInfo.civil = civilNumber;

          return this.users.filter((user) => !user.user_information);
        }
        this.sidesInfo.department_id = user?.department_id;
        return this.users.filter((user) => user.user_information);
      }
      return this.users;
    },
    claimantUsers() {
      if (this.sidesInfo.defendant_id) {
        const user = this.users.find(
          (user) => user.id === this.sidesInfo.defendant_id
        );
        const civilNumber = user?.user_information?.civil_number || null;
        if (civilNumber) {
          this.sidesInfo.civil = civilNumber;

          return this.users.filter((user) => !user.user_information);
        }
        this.sidesInfo.department_id = user?.department_id;
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
      "getPagesValues",
      "validateFormData",
      "updatePages",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
    ]),
    stepBack() {
      if (this.e1 > 1) {
        this.e1--;
        this.stepOneErrors = false;
      }
      return;
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
        text: this.$t("general.edit") + " " + this.selectedForm.name,
      });
      this.selectedTitle = this.$t(this.selectedForm.name);
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: this.$t("cases.casesList"),
      });
    },
    clearClaimantSelect() {
      const user = this.users.find(
        (user) => user.id === this.sidesInfo?.claimant_id
      );
      if (user.user_information) {
        this.sidesInfo.civil = null;
      } else {
        this.sidesInfo.department_id = null;
      }
    },
    clearDefendantSelect() {
      const user = this.users.find(
        (user) => user.id === this.sidesInfo?.defendant_id
      );
      if (user.user_information) {
        this.sidesInfo.civil = null;
      } else {
        this.sidesInfo.department_id = null;
      }
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
            if (this.formData?.form_request_information?.sessionDate) {
              this.sessionDate = true;
              this.caseAction.sessionDate =
                this.formData.form_request_information.sessionDate;
            }
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
    stepOneValidation(value) {
      const msg = this.$t("general.required_input");
      return this.stepOneErrors && !value ? [msg] : [];
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
    saveCaseInfo() {
      if (!this.caseName || !this.caseNumber) {
        this.stepOneErrors = true;
        return;
      }
      this.e1 = 2;
    },
    async saveForm() {
      const { id } = this.$route.params;
      const { formType: currentFormId } = this.$route.params;
      this.isSubmitingForm = true;
      if (
        (await this.validateFormData()) ||
        !this.caseName ||
        !this.caseAction
      ) {
        const saveResult = await this.updatePages({
          caseName: this.caseName,
          caseNumber: this.caseNumber,
          formId: id,
        });
        if (saveResult) {
          this.showErrors = false;
          this.e1 = 2;
        } else {
          console.log("Failed To save data");
        }
        this.isSubmitingForm = false;

        // this.$router.push({ path: `/cases/${currentFormId}` });
      } else {
        this.showErrors = true;
        this.stepOneErrors = true;
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
        this.e1 = 3;
        this.stepOneErrors = true;
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
        sessionDate: this.caseAction.sessionDate,
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
        this.stepOneErrors = true;
      }
    },
  },
};
</script>

<style scoped></style>
