<template>
  <div>
    <v-stepper v-model="e1">
      <v-stepper-header>
        <v-stepper-step :complete="e1 > 1" step="1" :editable="formRequestId">
          {{ $t("general.info") + " " + selectedTitle }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step :complete="e1 > 2" step="2" :editable="formRequestId">
          {{ $t("cases.sidesInfo") }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step step="3" :editable="formRequestId">
          {{ $t("cases.caseActions") }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>
        <v-stepper-content step="1">
          <div class="mt-2" v-if="!initialLoading">
            <div class="d-flex flex-column flex-sm-row">
              <div class="flex-grow-1 pt-2 pa-sm-2">
                <v-row dense>
                  <v-col cols="12">
                    <v-text-field outlined type="number" class="mb-2" v-model="caseNumber" @keydown="handleInput"
                      :label="$t('cases.caseNumber')" :required="true" :rules="[requiredRule]"
                      :error-messages="stepOneValidation(caseNumber)" dense></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field class="mb-2" v-model="caseName" :label="$t('cases.caseName')" outlined :required="true"
                      :error-messages="stepOneValidation(caseName)" dense :rules="[requiredRule]"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-file-input outlined dense show-size :v-model="caseFile" :label="$t('cases.caseFile')"
                      @change="(file) => handleCaseFileUpload(file)" click:clear="handleRemoveFile"
                      :error-messages="errors['file']">
                    </v-file-input>
                  </v-col>
                  <v-col cols="12">
                    <v-dialog ref="caseDateDialog" v-model="caseDateDialog" :return-value.sync="caseDate" persistent
                      width="290px">
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="caseDate" :label="$t('cases.caseDate')" append-icon="mdi-calendar" readonly
                          v-bind="attrs" v-on="on" dense required="true" :rules="[rules.required]"
                          :error-messages="stepOneValidation(caseDate)" outlined></v-text-field>
                      </template>
                      <v-date-picker v-model="caseDate" scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="caseDateDialog = false">
                          Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.caseDateDialog.save(caseDate)">
                          OK
                        </v-btn>
                      </v-date-picker>
                    </v-dialog>
                  </v-col>
                  <v-col cols="12">
                    <v-select :items="organizations" :label="$t('cases.classification')" item-text="name" item-value="id"
                      dense outlined v-model="organization_id" required="true" :rules="[rules.required]"
                      :error-messages="stepOneValidation(organization_id)">
                    </v-select>
                  </v-col>
                  <v-col cols="12">
                    <v-select :items="caseModels" :label="$t('cases.caseModels')" item-text="name" item-value="value"
                      dense outlined v-model="caseModel" required="true" :rules="[rules.required]"
                      :error-messages="stepOneValidation(caseModel)">
                    </v-select>
                  </v-col>
                  <v-col cols="12">
                    <v-select :items="specializations" :label="$t('cases.specialization')" item-text="name"
                      item-value="id" dense outlined v-model="specialization_id" required="true" :rules="[rules.required]"
                      :error-messages="stepOneValidation(specialization_id)">
                    </v-select>
                  </v-col>
                  <v-col cols="12">
                    <v-select :items="branches || []" :label="$t('branches.branch')" item-text="name" item-value="id"
                      dense outlined v-model="branch_id" required="true" :rules="[rules.required]"
                      :error-messages="stepOneValidation(branch_id)">
                    </v-select>
                  </v-col>
                </v-row>
              </div>
            </div>
            <v-tabs v-model="activeTab" v-if="pages && pages[0]?.items?.length > 0">
              <v-tab v-for="(tab, index) in pages" :key="index">{{
                tab.title
              }}</v-tab>
            </v-tabs>
            <v-tabs-items v-model="activeTab">
              <v-tab-item v-for="(tab, tabIndex) in pages" :key="tabIndex">
                <v-form>
                  <v-container>
                    <v-row dense>
                      <v-col v-for="(input, inputIndex) in tab.items" :key="inputIndex" :cols="inputWidth(input.width)">
                        <template v-if="input.type === 'text'">
                          <v-text-field outlined v-model="input.value" :label="getInputLabel(input)"
                            :rules="input.required ? [requiredRule] : []" :required="input.required"
                            :error-messages="errorMessage(input)" dense></v-text-field>
                        </template>
                        <template v-else-if="input.type === 'textarea'">
                          <v-textarea outlined dense v-model="input.value" :label="getInputLabel(input)"
                            :required="input.required" :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)"></v-textarea>
                        </template>
                        <template v-else-if="input.type === 'file'">
                          <v-file-input outlined dense counter show-size :label="getInputLabel(input)"
                            @change="(file) => handleFileUpload(file, input)" :required="input.required"
                            :rules="input.required ? [requiredRule] : []" :error-messages="errorMessage(input)">
                          </v-file-input>
                        </template>
                        <template v-else-if="input.type === 'select'">
                          <v-select v-model="input.value" :items="input.childList" item-text="text"
                            :label="getInputLabel(input)" :required="input.required"
                            :rules="input.required ? [requiredRule] : []" :error-messages="errorMessage(input)" outlined
                            dense></v-select>
                        </template>
                        <template v-else-if="input.type === 'radio'">
                          <v-radio-group v-model="input.selectedOption" :label="getInputLabel(input)"
                            :required="input.required" :rules="input.required ? [requiredRule] : []"
                            :error-messages="errorMessage(input)">
                            <v-radio v-for="(option, optionIndex) in input.childList" :key="optionIndex"
                              :label="option.text" :value="option.text"></v-radio>
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
                  <v-select :items="claimantUsers" :label="$t('cases.claimant')" :item-text="(item) => item.name"
                    :item-value="(item) => item.id" dense outlined v-model="sidesInfo.claimant_id"
                    :rules="[rules.required]" :error-messages="stepOneValidation(sidesInfo.claimant_id)" clearable
                    @click:clear="clearClaimantSelect" @change="changeDefendantUsers">
                  </v-select>
                </v-col>
                <v-col cols="12" v-if="caseModel === 'entry'">
                  <v-text-field type="number" v-model="sidesInfo.claimant_civil" :label="$t('cases.civil')" disabled dense
                    outlined></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select :items="defendantUsers" :label="$t('cases.defendant')" :item-text="(item) => item.name"
                    :item-value="(item) => item.id" dense outlined v-model="sidesInfo.defendant_id"
                    :rules="[rules.required]" :error-messages="stepOneValidation(sidesInfo.defendant_id)" clearable
                    @click:clear="clearDefendantSelect" @change="changeClaimantUsers">
                  </v-select>
                </v-col>
                <v-col cols="12" v-if="caseModel === 'entry'">
                  <v-text-field type="number" v-model="sidesInfo.defendant_civil" :label="$t('cases.civil')" disabled
                    dense outlined></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select :items="departments" :label="$t('tables.department')" :item-text="(item) => item.name"
                    :item-value="(item) => item.id" dense outlined
                    v-model="sidesInfo.department_id">
                  </v-select>
                </v-col>
                <v-col cols="12" v-if="caseModel !== 'entry'">
                  <v-text-field type="number" v-model="sidesInfo.civil" :label="$t('cases.civil')" disabled dense
                    outlined></v-text-field>
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

        <v-stepper-content step="3">
          <div class="d-flex flex-column flex-sm-row">
            <div class="flex-grow-1 pt-2 pa-sm-2">
              <v-row dense class="mb-2">
                <v-radio-group v-model="radioAction" row>
                  <v-radio class="radio-check" value="session" :label="$t('cases.add_session')"></v-radio>
                  <v-radio class="radio-check" value="court" :label="$t('cases.add_court')"></v-radio>
                  <v-radio value="other" :label="$t('cases.another')"></v-radio>
                </v-radio-group>
              </v-row>

              <v-row dense v-if="radioAction === 'session'">
                <v-col cols="12" sm="12">
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
                      <v-btn text color="primary" @click="
                        $refs.sessionDialog.save(caseAction.sessionDate)
                        ">
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
                <v-col cols="6">
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
                <v-col cols="6">
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
                      <v-btn text color="primary" @click="
                        $refs.receiptDialog.save(caseAction.receiptDate)
                        ">
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-dialog>
                </v-col>
                <v-col cols="12" v-if="caseAction.status">
                  <v-textarea :label="caseActionDetailsLabel" value="" v-model="caseAction.details" dense
                    outlined></v-textarea>
                </v-col>
              </v-row>
              <v-row dense v-if="radioAction === 'other'">
                <v-col cols="12">
                  <v-text-field type="number" @keydown="handleInput" v-model="caseAction.amount"
                    :label="$t('cases.amount')" dense outlined>
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
      radioAction: "session",
      e1: 1,
      selectedTitle: "",
      caseActionDetailsLabel: "",
      caseDateDialog: false,
      receiptDialog: false,
      dateDialog: false,
      sessionDialog: false,
      sessionDate: false,
      caseNumber: "",
      caseName: "",
      caseDate: "",
      caseModel: "",
      branch_id: "",
      caseFile: null,
      specialization_id: "",
      organization_id: "",
      caseModels: [
        {
          name: this.$t("cases.from_redf"),
          value: "from_redf",
        },
        {
          name: this.$t("cases.against_redf"),
          value: "against_redf",
        },
        {
          name: this.$t("cases.entry"),
          value: "entry",
        },
      ],
      initialLoading: false,
      isLoading: false,
      isSubmitingForm: false,
      users: [],
      formRequestId: null,

      breadcrumbs: [
        {
          text: this.$t("menu.cases_child"),
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
        claimant_civil: "",
        defendant_civil: "",
      },
      caseAction: {
        amount: "",
        form_request_id: "",
        percentage: "",
        details: "",
        status: "",
        court: "",
        sessionDate: null,
        judgment_date: null,
        judgment_for: null,
        receiptDate: null,
        date: null,
        sessionPlace: "",
        // date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        //   .toISOString()
        //   .substr(0, 10),
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
      defendantUsers: [],
      claimantUsers: [],
    };
  },

  created() {
    this.init();
    this.fetchUsers();
    this.fetchDepartments();
    this.getCourts();
    this.fetchBranches();

    this.$root.$on("userCreated", () => {
      this.fetchUsers();
    });
  },
  watch: {
    "caseAction.status"(newVal) {
      const selecetdStatus = this.caseTypes.find(
        (type) => type.value === newVal
      );
      this.caseActionDetailsLabel = selecetdStatus.title;
    },
    caseModel() {
      this.filterUsers();
    },
    e1(val) {
      if (val === 3) {
        this.retrieveClaimant({ form_request_id: this.formRequestId });
      }
    },
  },
  computed: {
    ...mapState("cases", [
      "pages",
      "selectedForm",
      "courts",
      "caseTypes",
      "specializations",
      "organizations",
      "claimant",
      "sessionPlaces",
    ]),
    ...mapState("auth", ["user"]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("departments", ["departments"]),
    ...mapState("branches", ["branches"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("users", ["getUserType"]),
    ...mapActions("departments", ["getDepartments"]),
    ...mapActions("branches", ["getBranches"]),
    ...mapActions("cases", [
      "getPages",
      "validateFormData",
      "savePages",
      "userDepartment",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
      "updatePages",
      "retrieveClaimant",
    ]),
    filterUsers() {
      if (this.caseModel === "from_redf") {
        this.defendantUsers = this.users.filter((user) => user.type === "user");
        this.claimantUsers = this.users.filter((user) =>
          user.roles.find((role) => role.name === "system")
        );
      } else if (this.caseModel === "against_redf") {
        this.defendantUsers = this.users.filter((user) =>
          user.roles.find((role) => role.name === "system")
        );
        this.claimantUsers = this.users.filter((user) => user.type === "user");
      } else {
        this.defendantUsers = this.users.filter((user) => user.type === "user");
        this.claimantUsers = this.users.filter((user) => user.type === "user");
      }
    },
    changeDefendantUsers() {
      if (this.sidesInfo.claimant_id) {
        const claimantUser = this.users.find(
          (user) => user.id === this.sidesInfo.claimant_id
        );
        if (this.caseModel === "from_redf") {
          this.sidesInfo.department_id = claimantUser?.department_id;
        } else if (this.caseModel === "against_redf") {
          this.sidesInfo.civil =
            claimantUser?.user_information?.civil_number || null;
        } else if (this.caseModel === "entry") {
          this.defendantUsers = this.users.filter(
            (user) => user.id !== claimantUser.id && user.type === "user"
          );
          this.sidesInfo.claimant_civil =
            claimantUser?.user_information?.civil_number || null;
        }
      }
    },
    changeClaimantUsers() {
      if (this.sidesInfo.defendant_id) {
        const defendantUser = this.users.find(
          (user) => user.id === this.sidesInfo.defendant_id
        );
        if (this.caseModel === "from_redf") {
          this.sidesInfo.civil =
            defendantUser?.user_information?.civil_number || null;
        } else if (this.caseModel === "against_redf") {
          this.sidesInfo.department_id = defendantUser?.department_id;
        } else if (this.caseModel === "entry") {
          this.claimantUsers = this.users.filter(
            (user) => user.id !== defendantUser.id && user.type === "user"
          );
          this.sidesInfo.defendant_civil =
            defendantUser?.user_information?.civil_number || null;
        }
      }
    },
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
          this.filterUsers();
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
    fetchBranches() {
      this.isLoading = true;
      let data = {
        pageSize: -1,
      };
      this.getBranches(data)
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
        this.breadcrumbs.unshift({
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
    handleCaseFileUpload(file) {
      if (file) {
        const fileName = file.name.split(".")[0];
        const fileExtension = file.name.split(".")[1];
        // input["file_name"] = fileName + "." + fileExtension;
        this.caseFile = file;
      }
    },
    handleRemoveFile() {
      this.caseFile = null;
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
      if (!this.caseName || !this.caseNumber || !this.caseDate) {
        this.stepOneErrors = true;
        return;
      }
      this.stepOneErrors = false;
      this.e1 = 2;
    },
    async saveForm() {
      this.isSubmitingForm = true;
      if (
        (await this.validateFormData()) &&
        this.caseName &&
        this.caseNumber &&
        this.caseDate &&
        this.branch_id &&
        this.specialization_id &&
        this.organization_id &&
        this.caseModel
      ) {
        let result = null;
        if (!this.formRequestId) {
          result = await this.savePages({
            caseName: this.caseName,
            caseNumber: this.caseNumber,
            caseDate: this.caseDate,
            branch_id: this.branch_id,
            type: "case",
            case_type: this.caseModel,
            specialization_id: this.specialization_id,
            organization_id: this.organization_id,
            file: this.caseFile,
          });
        } else {
          result = await this.updatePages({
            caseName: this.caseName,
            caseNumber: this.caseNumber,
            caseDate: this.caseDate,
            branch_id: this.branch_id,
            formId: this.formRequestId,
            type: "case",
            case_type: this.caseModel,
            specialization_id: this.specialization_id,
            organization_id: this.organization_id,
            file: this.caseFile,
          });
        }

        if (result) {
          this.isSubmitingForm = false;
          this.formRequestId =
            this.formRequestId || result.data?.data?.formRequest?.id;
          this.showErrors = false;
          this.e1 = 2;
          // makeToast("success", response.data.message);
        } else {
          makeToast("error", "Failed to save data");
        }
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
        department_id: this.sidesInfo.department_id,
      };

      // if (await this.validateFormData()) {
      const result = await this.saveRequestSide(data);
      if (result) {
        this.isLoading = false;
        this.e1 = 3;
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
        sessionDate: this.caseAction.sessionDate,
        session_place: this.caseAction.sessionPlace,
        date_of_receipt: this.caseAction.receiptDate,
        user_id: this.caseAction.judgment_for,
        type: this.radioAction,
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

<style scoped>
.radio-check {
  margin-left: 15%;
}
</style>
