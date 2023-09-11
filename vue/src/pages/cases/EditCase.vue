<template>
  <div v-if="formData">
    <v-stepper v-model="e1" class="mb-4">
      <v-stepper-header>
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
              <div class="d-flex flex-column flex-sm-row">
                <div class="flex-grow-1 pt-2 pa-sm-2">
                  <v-row dense>
                    <v-col cols="12">
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
                    </v-col>
                    <v-col cols="12">
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
                    </v-col>
                    <v-col cols="12">
                      <v-dialog
                        ref="caseDateDialog"
                        v-model="caseDateDialog"
                        :return-value.sync="caseDate"
                        persistent
                        width="290px"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="caseDate"
                            :label="$t('cases.caseDate')"
                            append-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                            dense
                            required="true"
                            :rules="[rules.required]"
                            :error-messages="stepOneValidation(caseDate)"
                            outlined
                          ></v-text-field>
                        </template>
                        <v-date-picker v-model="caseDate" scrollable>
                          <v-spacer></v-spacer>
                          <v-btn text color="primary" @click="caseDateDialog = false">
                            Cancel
                          </v-btn>
                          <v-btn
                            text
                            color="primary"
                            @click="$refs.caseDateDialog.save(caseDate)"
                          >
                            OK
                          </v-btn>
                        </v-date-picker>
                      </v-dialog>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="organizations"
                        :label="$t('cases.classification')"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        v-model="organization_id"
                        required="true"
                        :rules="[rules.required]"
                        :error-messages="stepOneValidation(organization_id)"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="caseModels"
                        :label="$t('cases.caseModels')"
                        item-text="name"
                        item-value="value"
                        dense
                        outlined
                        v-model="caseModel"
                        required="true"
                        :rules="[rules.required]"
                        :error-messages="stepOneValidation(caseModel)"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="specializations"
                        :label="$t('cases.specialization')"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        v-model="specialization_id"
                        required="true"
                        :rules="[rules.required]"
                        :error-messages="stepOneValidation(specialization_id)"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        :items="branches || []"
                        :label="$t('branches.branch')"
                        item-text="name"
                        item-value="id"
                        dense
                        outlined
                        v-model="branch_id"
                        required="true"
                        :rules="[rules.required]"
                        :error-messages="stepOneValidation(branch_id)"
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                </div>
              </div>

            </div>
            <v-tabs v-model="activeTab" v-if="pagesValues && pagesValues[0]?.items?.length > 0">
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
                      @change="changeDefendantUsers"
                    >
                    </v-select>
                  </v-col>
                  <v-col cols="12" v-if="caseModel === 'entry'">
                    <v-text-field
                      type="number"
                      v-model="sidesInfo.claimant_civil"
                      :label="$t('cases.civil')"
                      disabled
                      dense
                      outlined
                    ></v-text-field>
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
                      @change="changeClaimantUsers"
                    >
                    </v-select>
                  </v-col>
                  <v-col cols="12" v-if="caseModel === 'entry'">
                    <v-text-field
                      type="number"
                      v-model="sidesInfo.defendant_civil"
                      :label="$t('cases.civil')"
                      disabled
                      dense
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-select
                      :items="departments"
                      :label="$t('tables.department')"
                      :item-text="(item) => item.name"
                      :item-value="(item) => item.id"
                      hide-details
                      :disabled="caseModel !== 'entry'"
                      dense
                      outlined
                      v-model="sidesInfo.department_id"
                    >
                    </v-select>
                  </v-col>
                  <v-col cols="12" v-if="caseModel !== 'entry'">
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
<!--              <v-row dense v-if="lastAction" class="mb-2">-->
<!--                <v-col cols="12">-->
<!--                  <v-expansion-panels multiple>-->
<!--                    <v-expansion-panel>-->
<!--                      <v-expansion-panel-header>-->
<!--                        <h5>{{ $t("general.last_action") }}</h5>-->
<!--                      </v-expansion-panel-header>-->
<!--                      <v-expansion-panel-content>-->
<!--                        <v-row class="mb-1" dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("cases.amount") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->
<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-text-field-->
<!--                              dense-->
<!--                              class="custom-disabled-input"-->
<!--                              :value="lastAction?.amount || ''"-->
<!--                              solo-->
<!--                              label="Solo"-->
<!--                              disabled-->
<!--                              hide-details-->
<!--                            ></v-text-field>-->
<!--                          </v-col>-->
<!--                        </v-row>-->
<!--                        <v-row class="mb-1" dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("cases.percentageLose") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->
<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-text-field-->
<!--                              dense-->
<!--                              class="custom-disabled-input"-->
<!--                              :value="lastAction?.percentage || ''"-->
<!--                              solo-->
<!--                              label="Solo"-->
<!--                              disabled-->
<!--                              hide-details-->
<!--                            ></v-text-field>-->
<!--                          </v-col>-->
<!--                        </v-row>-->
<!--                        <v-row class="mb-1" dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("tables.court") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->
<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-text-field-->
<!--                              class="custom-disabled-input"-->
<!--                              :value="lastAction?.court || ''"-->
<!--                              solo-->
<!--                              label="Solo"-->
<!--                              disabled-->
<!--                              hide-details-->
<!--                              dense-->
<!--                            ></v-text-field>-->
<!--                          </v-col>-->
<!--                        </v-row>-->
<!--                        <v-row class="mb-1" dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("tables.date") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->
<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-text-field-->
<!--                              class="custom-disabled-input"-->
<!--                              :value="lastAction?.date || ''"-->
<!--                              solo-->
<!--                              label="Solo"-->
<!--                              disabled-->
<!--                              hide-details-->
<!--                              dense-->
<!--                            ></v-text-field>-->
<!--                          </v-col>-->
<!--                        </v-row>-->

<!--                        <v-row class="mb-1" dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("cases.action") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->

<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-textarea-->
<!--                              class="custom-disabled-input"-->
<!--                              :value="lastAction?.details || ''"-->
<!--                              solo-->
<!--                              disabled-->
<!--                              hide-details-->
<!--                            ></v-textarea>-->
<!--                          </v-col>-->
<!--                        </v-row>-->

<!--                        <v-row dense>-->
<!--                          <v-col cols="12" sm="3">-->
<!--                            <h6 class="mt-1 mb-0 c-h6">-->
<!--                              {{ $t("tables.status") }}-->
<!--                            </h6>-->
<!--                          </v-col>-->
<!--                          <v-col cols="12" sm="9">-->
<!--                            <v-chip-->
<!--                              :color="-->
<!--                                getStatusColor(-->
<!--                                  lastAction?.status?.toLowerCase()-->
<!--                                )-->
<!--                              "-->
<!--                              label-->
<!--                              text-color="white"-->
<!--                            >-->
<!--                              {{-->
<!--                                $t(-->
<!--                                  `general.${lastAction?.status?.toLowerCase()}`-->
<!--                                )-->
<!--                              }}-->
<!--                            </v-chip>-->
<!--                          </v-col>-->
<!--                        </v-row>-->
<!--                      </v-expansion-panel-content>-->
<!--                    </v-expansion-panel>-->
<!--                  </v-expansion-panels>-->
<!--                </v-col>-->
<!--              </v-row>-->

              <v-row dense class="mb-2">
                <v-radio-group v-model="radioAction" row>
                  <v-radio
                    class="radio-check"
                    value="session"
                    :label="$t('cases.add_session')"
                  ></v-radio>
                  <v-radio
                    class="radio-check"
                    value="court"
                    :label="$t('cases.add_court')"
                  ></v-radio>
                  <v-radio
                    value="other"
                    :label="$t('cases.another')"
                  ></v-radio>
                </v-radio-group>
              </v-row>

              <v-row dense v-if="radioAction === 'session'">
                <v-col cols="12" sm="12">
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
                        append-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="caseAction.sessionDate" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="sessionDialog = false">
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
                <v-col cols="12" md="12">
                  <v-select
                    :items="sessionPlaces || []"
                    :label="$t('cases.casePlace')"
                    dense
                    outlined
                    v-model="caseAction.sessionPlace"
                  >
                  </v-select>
                </v-col>
              </v-row>
              <v-row dense v-if="radioAction === 'court'">
                <v-col cols="6">
                  <v-select
                    clearable
                    :items="caseTypes"
                    @click:clear="caseAction.status = null"
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
                <v-col cols="12" md="6">
                  <v-select
                    :items="claimant"
                    :label="$t('cases.judgment_for')"
                    dense
                    outlined
                    item-text="name"
                    item-value="id"
                    v-model="caseAction.judgment_for"
                  >
                  </v-select>
                </v-col>
                <v-col cols="6">
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
                        :label="$t('cases.judgmentDate')"
                        append-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="caseAction.date" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="dateDialog = false">
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
                <v-col cols="6">
                  <v-dialog
                    ref="receiptDialog"
                    v-model="receiptDialog"
                    :return-value.sync="caseAction.receiptDate"
                    persistent
                    width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="caseAction.receiptDate"
                        :label="$t('cases.receiptDate')"
                        append-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        dense
                        outlined
                      ></v-text-field>
                    </template>
                    <v-date-picker v-model="caseAction.receiptDate" scrollable>
                      <v-spacer></v-spacer>
                      <v-btn text color="primary" @click="receiptDialog = false">
                        Cancel
                      </v-btn>
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.receiptDialog.save(caseAction.receiptDate)"
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-dialog>
                </v-col>
                <v-col cols="12" v-if="caseAction.status">
                  <v-textarea
                    :label="caseActionDetailsLabel"
                    value=""
                    v-model="caseAction.details"
                    dense
                    outlined
                  ></v-textarea>
                </v-col>
              </v-row>
              <v-row dense v-if="radioAction === 'other'">
                <v-col cols="12">
                  <v-text-field
                    type="number"
                    @keydown="handleInput"
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
                <v-col cols="12">
                  <v-text-field
                    type="number"
                    @keydown="handleInput"
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
      radioAction: 'session',
      e1: 1,
      selectedTitle: "",
      caseActionDetailsLabel: "",
      caseDateDialog: false,
      receiptDialog: false,
      dateDialog: false,
      sessionDialog: false,
      sessionDate: false,
      formData: null,
      caseNumber: "",
      caseName: "",
      caseDate: "",
      caseModel: "",
      branch_id: "",
      specialization_id: "",
      organization_id: "",
      caseModels:[
        {
          name:this.$t("cases.from_redf"),
          value:"from_redf"
        },
        {
          name:this.$t("cases.against_redf"),
          value:"against_redf"
        },
        {
          name:this.$t("cases.entry"),
          value:"entry"
        }
      ],
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
      sidesInfo: {
        claimant_id: "",
        defendant_id: "",
        civil: "",
        department_id: "",
        claimant_civil:"",
        defendant_civil:""
      },
      caseAction: {
        amount: "",
        form_request_id: "",
        sessionPlace: "",
        percentage: "",
        details: "",
        sessionDate: null,
        court: "",
        judgment_date: null,
        judgment_for: null,
        receiptDate: null,
        date: null,
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
      lastAction: null,
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
    'caseModel'(){
      this.filterUsers()
    }
  },
  computed: {
    ...mapState("cases", [
      "pagesValues",
      "selectedForm",
      "courts",
      "caseTypes",
      'specializations',
      'organizations',
      "claimant",
      'sessionPlaces'
    ]),
    ...mapState("app", ["navTemplates"]),
    ...mapState("departments", ["departments"]),
    ...mapState("auth", ["user"]),
    ...mapState("branches", ["branches"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("users", ["getUserType"]),
    ...mapActions("departments", ["getDepartments"]),
    ...mapActions("branches", ["getBranches"]),
    ...mapActions("cases", [
      "getPagesValues",
      "validateFormData",
      "updatePages",
      "saveRequestSide",
      "saveFormInformation",
      "getCourts",
      "retrieveClaimant",
    ]),
    filterUsers()
    {
      if(this.caseModel === 'from_redf') {
        this.defendantUsers = this.users.filter((user) => user.type === 'user')
        this.claimantUsers = this.users.filter(
          (user) => user.roles.find(
            (role) => role.name === 'system'
          )
        )
      }
      else if(this.caseModel === 'against_redf')
      {
        this.defendantUsers = this.users.filter(
          (user) => user.roles.find(
            (role) => role.name === 'system'
          )
        )
        this.claimantUsers = this.users.filter((user) => user.type === 'user')
      }
      else{
        this.defendantUsers = this.users.filter((user) => user.type === 'user')
        this.claimantUsers = this.users.filter((user) => user.type === 'user')
      }
    },
    changeDefendantUsers() {
      if (this.sidesInfo.claimant_id) {
        const claimantUser = this.users.find(
          (user) => user.id === this.sidesInfo.claimant_id
        );
        if(this.caseModel === 'from_redf'){
          this.sidesInfo.department_id = claimantUser?.department_id;
        }
        else if(this.caseModel === 'against_redf'){
          this.sidesInfo.civil = claimantUser?.user_information?.civil_number || null;
        }
        else if(this.caseModel === 'entry') {
            this.defendantUsers = this.users.filter((user) => user.id !== claimantUser.id && user.type === 'user');
            this.sidesInfo.claimant_civil = claimantUser?.user_information?.civil_number || null;
        }
      }
    },
    changeClaimantUsers() {
      if (this.sidesInfo.defendant_id) {
        const defendantUser = this.users.find(
          (user) => user.id === this.sidesInfo.defendant_id
        );
        if(this.caseModel === 'from_redf'){
          this.sidesInfo.civil = defendantUser?.user_information?.civil_number || null;
        }
        else if(this.caseModel === 'against_redf'){
          this.sidesInfo.department_id = defendantUser?.department_id;
        }
        else if(this.caseModel === 'entry') {
          this.claimantUsers = this.users.filter((user) => user.id !== defendantUser.id && user.type === 'user');
          this.sidesInfo.defendant_civil = defendantUser?.user_information?.civil_number || null;
        }
      }
    },
    stepBack() {
      if (this.e1 > 1) {
        this.e1--;
        this.stepOneErrors = false;
      }
      return;
    },
    handleInput(event) {
      if (event.key.toLowerCase() === "e") {
        event.preventDefault();
      }
    },
    fetchUsers() {
      this.isLoading = true;
      this.getUserType()
        .then((response) => {
          this.isLoading = false;
          this.users = response.data.data.users;
          this.filterUsers()
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
          this.caseDate = data.case_date
          this.caseModel = data.case_type
          this.branch_id = data.branche_id
          this.specialization_id = data.specialization_id
          this.organization_id = data.organization_id
          this.formRequestId = this.formData.id;
          if (this.formData.form_request_side) {
            this.sidesInfo.claimant_id = this.formData?.form_request_side?.claimant_id;
            this.sidesInfo.defendant_id = this.formData?.form_request_side?.defendant_id;
            this.sidesInfo.department_id = this.formData?.form_request_side?.department_id;
            // this.sidesInfo.civil = this.formData?.form_request_side?.civil;

            if(this.caseModel==='from_redf'){
              // this.sidesInfo.department_id = this.formData?.form_request_side?.claimant?.department_id
              this.sidesInfo.civil = this.formData?.form_request_side?.defendant?.user_information?.civil_number;
            }else if(this.caseModel==='against_redf'){
              // this.sidesInfo.department_id = this.formData?.form_request_side?.defendant?.department_id
              this.sidesInfo.civil = this.formData?.form_request_side?.claimant?.user_information?.civil_number;
            }else if(this.caseModel==='entry'){
              this.sidesInfo.claimant_civil = this.formData?.form_request_side?.claimant?.user_information?.civil_number;
              this.sidesInfo.defendant_civil = this.formData?.form_request_side?.defendant?.user_information?.civil_number;
            }
          }

          //  temp solution
          // if (
          //   this.formData.form_request_informations &&
          //   this.formData.form_request_informations.length
          // ) {
          //   this.formData.form_request_information =
          //     this.formData.form_request_informations[
          //       this.formData.form_request_informations.length - 1
          //     ];
          // }
          // if (this.formData.form_request_information) {
          //   this.caseAction.amount =
          //     this.formData?.form_request_information?.amount;
          //   this.caseAction.court =
          //     this.formData?.form_request_information?.court;
          //   this.caseAction.date =
          //     this.formData?.form_request_information?.date;
          //   this.caseAction.percentage =
          //     this.formData?.form_request_information?.percentage;
          //   this.caseAction.status =
          //     this.formData?.form_request_information?.status || null;
          //   this.caseAction.details =
          //     this.formData?.form_request_information?.details;
          //   if (this.formData?.form_request_information?.sessionDate) {
          //     this.sessionDate = true;
          //     this.caseAction.sessionDate =
          //       this.formData.form_request_information.sessionDate.split(
          //         "T"
          //       )[0];
          //   }
          //   this.caseAction.branch_id = this.formData?.form_request_information?.session_place
          // }
        })
        .finally((_) => {
          this.initialLoading = false;
          this.retrieveClaimant({ form_request_id: this.formRequestId });
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
        (await this.validateFormData()) &&
        this.caseName &&
        this.caseNumber &&
        this.caseDate &&
        this.branch_id &&
        this.specialization_id &&
        this.organization_id &&
        this.caseModel
      ) {
        const saveResult = await this.updatePages({
          caseName: this.caseName,
          caseNumber: this.caseNumber,
          formId: id,
          caseDate: this.caseDate,
          branch_id: this.branch_id,
          type: 'case',
          case_type: this.caseModel,
          specialization_id: this.specialization_id,
          organization_id: this.organization_id,
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
        department_id: this.sidesInfo.department_id,
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
        session_place: this.caseAction.sessionPlace,
        date_of_receipt: this.caseAction.receiptDate,
        user_id: this.caseAction.judgment_for,
        type:this.radioAction
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

<style scoped>
.radio-check {
  margin-left: 15%;
}
</style>
