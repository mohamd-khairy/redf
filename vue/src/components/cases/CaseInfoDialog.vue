<template>
  <v-dialog
    v-model="dialogVisible"
    fullscreen
    hide-overlay
    transition="dialog-bottom-transition"
  >
    <v-card>
      <v-toolbar dark color="primary">
        <v-toolbar-title>{{ $t("general.casePreview") }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn icon dark @click="closeDialog">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text v-if="loading" class="dialog-loading-cont">
        <v-row align-content="center" justify="center">
          <v-col class="text-subtitle-1 text-center" cols="12">
            {{ $t("general.getting_data") }}
          </v-col>
          <v-col cols="12">
            <v-progress-linear
              color="primary accent-4"
              indeterminate
              rounded
              height="6"
            ></v-progress-linear>
          </v-col>
        </v-row>
      </v-card-text>
      <v-card-text v-if="!loading && caseName" class="pt-3">
        <div class="form-title mb-3">
          <h2>{{ form.name }}</h2>
        </div>

        <v-row dense class="mb-3">
          <v-col cols="3" class="input-cont">
            <div class="input-label">
              {{ $t("cases.caseName") }}
            </div>
            <div class="input-value">
              {{ caseName }}
            </div>
          </v-col>
          <v-col cols="3" class="input-cont">
            <div class="input-label">
              {{ $t("cases.caseNumber") }}
            </div>
            <div class="input-value">
              {{ caseNumber }}
            </div>
          </v-col>
        </v-row>

        <div v-for="(tab, tabIndex) in pageTabs" :key="tabIndex">
          <div class="form-title-bg mb-1">
            <h5 class="mb-0">
              {{ tab.title }}
            </h5>
          </div>
          <v-row class="mb-3" dense>
            <v-col
              v-for="(input, inputIndex) in tab.items"
              :key="inputIndex"
              :cols="setCols(input.type)"
              :order="setOrder(input.type, tab.items)"
              class="input-cont mb-2"
              :class="inputType(input?.type || '')"
            >
              <template v-if="input.type !== 'file'">
                <div class="input-label">
                  {{ getInputLabel(input) }}
                </div>
                <div class="input-value">
                  {{ input.value }}
                </div>
              </template>
              <template v-else>
                <div class="input-label">
                  {{ getInputLabel(input) }}
                </div>
                <div
                  class="d-flex justify-content-between align-items-start"
                  v-if="input.preview && input.preview === input.value"
                >
                  <h6 class="input-value mb-0">
                    {{ fileInfo(input.preview).name }}
                  </h6>
                  <a
                    :href="input.preview"
                    target="_blank"
                    v-if="
                      fileInfo(input.preview).type === 'png' ||
                      fileInfo(input.preview).type === 'jpg' ||
                      fileInfo(input.preview).type === 'jpeg'
                    "
                  >
                    <img
                      width="80"
                      height="80"
                      :src="input.preview"
                      alt="file preview"
                    />
                  </a>
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
            </v-col>
          </v-row>
        </div>

        <div>
          <div class="form-title mb-1">
            <h4>
              {{ $t("cases.sidesInfo") }}
            </h4>
          </div>
          <v-row dense class="mb-3">
            <v-col cols="3" class="input-cont mb-2">
              <div class="input-label">
                {{ $t(`cases.claimant`) }}
              </div>
              <div class="input-value">
                {{ formRequestSide?.claimant?.name ?? '' }}
              </div>
            </v-col>
            <v-col cols="3" class="input-cont mb-2">
              <div class="input-label">
                {{ $t(`cases.defendant`) }}
              </div>
              <div class="input-value">
                {{ formRequestSide?.defendant?.name ?? '' }}
              </div>
            </v-col>
          </v-row>
        </div>
        <div>
          <div class="form-title mb-1">
            <h4>
              {{ $t("cases.actions") }}
            </h4>
          </div>
          <div v-for="(action, index) in formRequestInformation">
            <div class="form-title-bg mb-1">
              <h5 class="mb-0">
                {{ $t(`cases.action`) + " " + (index + 1) }}
              </h5>
            </div>
            <v-row dense class="mb-3">
              <v-col cols="3" class="input-cont mb-2">
                <div class="input-label">
                  {{ $t(`cases.amount`) }}
                </div>
                <div class="input-value">
                  {{ action?.amount || "---" }}
                </div>
              </v-col>
              <v-col cols="3" class="input-cont mb-2">
                <div class="input-label">
                  {{ $t(`cases.percentageLose`) }}
                </div>
                <div class="input-value">
                  {{ action?.percentage || "---" }}
                </div>
              </v-col>
              <v-col cols="3" class="input-cont mb-2">
                <div class="input-label">
                  {{ $t(`cases.date`) }}
                </div>
                <div class="input-value">
                  {{ action?.date || "---" }}
                </div>
              </v-col>
              <v-col cols="3" class="input-cont mb-2">
                <div class="input-label">
                  {{ $t(`cases.status`) }}
                </div>
                <div class="input-value">
                  {{
                    action.status
                      ? $t(`general.${action.status.toLowerCase()}`)
                      : "---"
                  }}
                </div>
              </v-col>
              <v-col cols="3" class="input-cont mb-2">
                <div class="input-label">
                  {{ $t(`cases.court`) }}
                </div>
                <div class="input-value">
                  {{
                    action.court
                      ? $t(`general.${action.court.toLowerCase()}`)
                      : "---"
                  }}
                </div>
              </v-col>
              <v-col
                cols="3"
                class="input-cont mb-2"
                v-if="action?.sessionDate"
              >
                <div class="input-label">
                  {{ $t(`cases.sessionDate`) }}
                </div>
                <div class="input-value">
                  {{ action?.sessionDate || "---" }}
                </div>
              </v-col>
              <v-col cols="12" class="input-cont textarea-bg mb-2">
                <div class="input-label">
                  {{ $t(`cases.details`) }}
                </div>
                <div class="input-value">
                  {{ action.details }}
                </div>
              </v-col>
              <!-- <v-col
                :cols="key === 'details' ? 12 : 3"
                :order="key === 'details' ? 7 : undefined"
                :class="key === 'details' ? 'textarea-bg' : ''"
                class="input-cont mb-2"
                v-for="(elm, key) in action"
                :key="key"
              >
                <div class="input-label">
                  {{ $t(`cases.${key}`) }}
                </div>
                <div class="input-value">
                  {{ elm }}
                </div>
              </v-col> -->
            </v-row>
          </div>
        </div>
      </v-card-text>
      <v-card-text v-if="!loading && !caseName">
        <div class="text-center mt-7 primary--text" color="primary">
          <emptyDataSvg></emptyDataSvg>
          <div class="dt-no_data">
            {{ $t("general.no_action_yet") }}
          </div>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions } from "vuex";

import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  components: {
    emptyDataSvg,
  },
  props: {
    dialogVisible: Boolean,
    caseId: Number,
  },
  data() {
    return {
      loading: false,
      formActions: [],
      form: {},
      panelExpanded: true,
      caseNumber: null,
      caseName: null,
      formRequestInformation: [],
      formRequestSide: {},
      pageTabs: [],
    };
  },
  watch: {
    caseId: {
      immediate: true,
      handler(newId) {
        this.getCaseTimeline(newId);
      },
    },
  },
  methods: {
    ...mapActions("cases", ["getCasePreview"]),
    closeDialog() {
      this.$emit("closeInfoDialog");
    },
    fileInfo(filePath) {
      const filePathArr = filePath.split(".");
      const fileInfo = filePathArr.pop();
      const fileName = filePath.split("/").pop();
      const info = { name: fileName || "", type: fileInfo || "" };
      return info;
    },
    inputType(type) {
      const classes = {
        textarea: "textarea-bg",
      };
      return classes[type] || "";
    },
    setCols(type) {
      const cols = {
        textarea: "12",
        file: "12",
      };
      return cols[type] || "3";
    },
    setOrder(type, itemsObj) {
      const itemsLength = Object.keys(itemsObj).length;
      const order = {
        textarea: itemsLength,
        file: itemsLength - 1,
      };
      return order[type] || undefined;
    },
    getInputLabel(input) {
      const inputLabel = input.label;
      const isRequired = input.required;

      return inputLabel;
    },
    getTimeColor(typeStr) {
      const type = this.getFormType(typeStr || "");
      const colors = {
        FormAssignRequest: "blue",
        FormRequest: "orange",
        accepted: "green",
      };

      return colors[type] || "primary";
    },
    formatDate(date) {
      if (!date) {
        return;
      }
      return date.split("T")[0];
    },
    getFormType(type) {
      if (!type) return;
      return type.split("\\").pop();
    },
    async getCaseTimeline(id) {
      try {
        this.loading = true;
        const response = await this.$axios.get(`get-form-Requests/${id}`);
        console.log(response?.data?.data);
        const {
          form,
          form_request_number,
          name,
          form_request_informations,
          form_page_item_fill,
          form_request_side,
        } = response?.data?.data;
        this.caseNumber = form_request_number;
        this.caseName = name;
        console.log(response?.data?.data);
        const propertiesToRemove = [
          "updated_at",
          "created_at",
          "deleted_at",
          "form_request_id",
          "id",
        ];

        // propertiesToRemove.forEach((property) => {
        //   if (form_request_information.hasOwnProperty(property)) {
        //     delete form_request_information[property];
        //   }
        // });

        this.formRequestInformation = form_request_informations;
        this.formRequestSide = form_request_side;
        this.form = form;
        let pageTabs = form.pages;
        const inputValues = form_page_item_fill;
        const inputValuesObj = {};
        inputValues.forEach(({ form_page_item_id, value }) => {
          inputValuesObj[form_page_item_id] = value;
        });
        for (const obj of pageTabs) {
          for (const item of obj.items) {
            item.value = inputValuesObj[item.id];
            if (item.type === "file") {
              item.preview = inputValuesObj[item.id];
            }
          }
        }
        this.pageTabs = pageTabs;
        console.log(pageTabs);
        // this.formActions = formRequestActions;
      } catch (error) {
        console.log(error);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style>
.theme--light.v-input.custom-disabled-input {
  color: rgba(0, 0, 0, 0.87) !important;
  pointer-events: auto !important;
}
.c-h6 {
  font-weight: 600;
}
.theme--light.v-input--is-disabled.custom-disabled-input input {
  color: rgba(0, 0, 0, 0.87) !important;
}
.dialog-loading-cont {
  height: calc(100vh - 65px);
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
.textarea-bg {
  background-color: #f7f7f7;
  border-radius: 4px;
}
.form-title {
  border-bottom: 4px solid #0b4d4f;
  margin-bottom: 12px;
  display: inline-block;
}
.form-title-bg {
  display: inline-block;
  border-radius: 6px;
  background: #0b4d4f;
  padding: 6px 26px;
  color: #fff;
}
</style>
<style scoped lang="scss">
.case-info {
  display: flex;
  gap: 30px;
}
.input-cont {
  display: flex;
  flex-direction: column;

  .input-label {
    font-size: 14px;
    margin-bottom: 6px;
  }
  .input-value {
    font-size: 16px;
    font-weight: bold;
  }
}
</style>
