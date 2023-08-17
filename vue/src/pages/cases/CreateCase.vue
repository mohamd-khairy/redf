<template>
  <div class="d-flex flex-column flex-grow-1" style="margin: 50px">
    <v-stepper v-model="e1">
      <v-stepper-header>

        <v-stepper-step
          :complete="e1 > 1"
          step="1"
        >
          {{ $t('cases.caseInfo') }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step
          :complete="e1 > 2"
          step="2"
        >
          {{ $t('cases.sidesInfo') }}
        </v-stepper-step>

        <v-divider></v-divider>

        <v-stepper-step step="3">
          {{ $t('cases.caseActions') }}
        </v-stepper-step>
      </v-stepper-header>

      <v-stepper-items>

        <v-stepper-content step="1">
          <v-card class="mb-12" v-if="!initialLoading">
            <v-tabs v-model="activeTab">
              <v-tab v-for="(tab, index) in pages" :key="index">{{
                  tab.title
                }}</v-tab>
            </v-tabs>
            <v-card-text>
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
            </v-card-text>
<!--            <v-card-actions class="px-5 pb-4">-->
<!--              <v-btn-->
<!--                color="primary"-->
<!--                :disabled="isSubmitingForm"-->
<!--                :loading="isSubmitingForm"-->
<!--                @click="saveForm"-->
<!--              >{{ $t("general.saveChanges") }}</v-btn-->
<!--              >-->
<!--            </v-card-actions>-->
          </v-card>
          <v-btn
            color="primary"
            @click="e1 = 2"
          >
            Continue
          </v-btn>
          <v-btn text>
            Cancel
          </v-btn>
        </v-stepper-content>
        <v-stepper-content step="2">
          <v-card class="mb-12">
            <v-card-title>
              <v-flex class="text-left">
                <v-btn color="primary" large @click.stop="dialog = true">
                  <v-icon>
                    mdi-plus
                  </v-icon>
                  {{ $t('cases.addUser') }}
                </v-btn>
              </v-flex>
            </v-card-title>
            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-row>
                    <v-col cols="12">
                      <v-select
                        :items="claimantUsers"
                        :label="$t('cases.claimant')"
                        :item-text="item => item.name"
                        :item-value="item => item.id"
                        hide-details
                        dense
                        outlined
                        v-model="sidesInfo.claimant_id"
                        :rules="[rules.required]"
                        :error-messages="errors['claimant_id']"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="defendantUsers"
                        :label="$t('cases.defendant')"
                        :item-text="item => item.name"
                        :item-value="item => item.id"
                        hide-details
                        dense
                        outlined
                        v-model="sidesInfo.defendant_id"
                        :rules="[rules.required]"
                        :error-messages="errors['defendant_id']"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="departments"
                        :label="$t('tables.department')"
                        :item-text="item => item.name"
                        :item-value="item => item.id"
                        hide-details
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
                        :rules="[rules.required]"
                        :label="$t('cases.civil')"
                        :error-messages="errors['civil']"
                        dense
                        outlined
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </div>
              </div>
            </v-card-text>
          </v-card>

          <v-btn
            color="primary"
            @click="e1 = 3"
          >
            Continue
          </v-btn>

          <v-btn text>
            Cancel
          </v-btn>
        </v-stepper-content>

        <v-stepper-content step="3">
          <v-card class="mb-12">
            <v-card-title>

            </v-card-title>
            <v-card-text>
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-row>
                    <v-col cols="4">
                      <v-text-field
                        type="number"
                        v-model="caseAction.amount"
                        :label="$t('cases.amount')"
                        dense
                        outlined
                      >
                        <template v-slot:append>
                          <v-icon>
                            mdi-cash
                          </v-icon>
                        </template>
                      </v-text-field>
                    </v-col>
                    <v-col cols="4">
                      <v-text-field
                        type="number"
                        v-model="caseAction.percentage"
                        :label="$t('cases.percentageLose')"
                        dense
                        outlined
                      >
                        <template v-slot:append>
                          <v-icon>
                            mdi-percent
                          </v-icon>
                        </template>
                      </v-text-field>
                    </v-col>
                    <v-col cols="4">
                      <v-select
                        :items="status"
                        :label="$t('tables.status')"
                        :item-text="item => item.key"
                        :item-value="item => item.value"
                        hide-details
                        dense
                        outlined
                        v-model="caseAction.status"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-textarea
                        :label="$t('cases.action')"
                        value=""
                        v-model="caseAction.action"
                        dense
                        outlined
                      ></v-textarea>
                    </v-col>
                  </v-row>
                  <v-row v-for="(date,k) in caseAction.dates" :key="k">
                    <v-col cols="10">
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
                        v-show="k || ( !k && caseAction.dates.length > 1)"
                      >
                        <v-icon color="green">
                          mdi-minus
                        </v-icon>
                      </v-btn>
                    </v-col>
                    <v-col cols="1">
                      <v-btn
                        @click="addDate(k)"
                        v-show="k == caseAction.dates.length-1"
                      >
                        <v-icon color="red">
                          mdi-plus
                        </v-icon>
                      </v-btn>
                    </v-col>
                  </v-row>
                </div>
              </div>
            </v-card-text>
          </v-card>
          <v-btn
            color="primary"
          >
            Continue
          </v-btn>

          <v-btn text>
            Cancel
          </v-btn>
        </v-stepper-content>
      </v-stepper-items>
    </v-stepper>

    <add-user-dialog v-model="dialog"></add-user-dialog>

  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import AddUserDialog from "@/pages/cases/AddUserDialog";

export default {
  name: "CreateCase",
  components: {AddUserDialog},
  data() {
    return {
      e1: 1,
      initialLoading: false,
      isLoading: false,
      isSubmitingForm: false,
      users:[],
      departments:[],
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
      sidesInfo:{
        claimant_id:'',
        defendant_id:'',
        civil:'',
        department_id:''
      },
      caseAction:{
        amount:'',
        status:'',
        percentage:'',
        action:'',
        dates:[{
          caseDate:""
        }]
      },
      dialog: false,
      rules: {
        required: (value) => (value && Boolean(value)) || this.$t("general.fieldRequired")
      },
      errors: {},
      status:[
        {key:'error', value:0},
        {key:'confirmed',value:1},
        {key:'pending', value:2}
      ],
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("cases.casesList"),
    });
    this.init();
    this.fetchUsers()
    this.fetchDepartments()

    this.$root.$on("userCreated", () => {
      this.fetchUsers();
    });
  },

  computed: {
    ...mapState("cases", ["pages", "selectedForm"]),
    ...mapState("auth", ["user"]),
    ...mapState("app", ["navTemplates"]),

    defendantUsers(){
      return this.sidesInfo.claimant_id ? this.users.filter(obj => {
        return obj.id !== this.sidesInfo.claimant_id
      }) : this.users
    },
    claimantUsers(){
      return this.sidesInfo.defendant_id ? this.users.filter(obj => {
        return obj.id !== this.sidesInfo.defendant_id
      }) : this.users
    },
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("users", ["getUserType"]),
    ...mapActions("departments", ["getDepartments"]),
    ...mapActions("cases", ["getPages", "validateFormData", "savePages"]),
    addDate(index) {
      this.caseAction.dates.push({ caseDate: ""});
    },
    removeDate(index) {
      this.caseAction.dates.splice(index, 1);
    },
    removeFromDefendant(id){
      this.defendantUsers = this.users
      const newArr = this.defendantUsers.filter(object => {
        return object.id !== id;
      });
      this.defendantUsers = newArr
    },
    removeFromClaimant(id){
      this.claimantUsers = this.users
      const newArr = this.claimantUsers.filter(object => {
        return object.id !== id;
      });
      this.claimantUsers = newArr
    },
    fetchUsers(){
      this.isLoading = true;
      this.getUserType()
        .then((response) => {
          this.isLoading = false;
          this.users = response.data.data.users
          // this.claimantUsers = response.data.data.users
          // this.defendantUsers = response.data.data.users
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchDepartments(){
      this.isLoading = true;
      let data = {
        pageSize:-1
      }
      this.getDepartments(data)
        .then((response) => {
          this.isLoading = false;
          this.departments = response.data.data.departments
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
    async saveForm() {
      const { id } = this.$route.params;
      const { formType: currentFormId } = this.$route.params;
      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        await this.savePages(id);
        this.isSubmitingForm = false;
        this.$router.push({ path: `/cases/${currentFormId}` });
      } else {
        this.showErrors = true;
        this.isSubmitingForm = false;

        console.log("some fields is required");
      }
    },
    addUser(){

    }
  },
};
</script>

<style scoped></style>
