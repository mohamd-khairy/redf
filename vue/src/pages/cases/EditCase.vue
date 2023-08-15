<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-card v-if="!initialLoading">
      <v-tabs v-model="activeTab">
        <v-tab v-for="(tab, index) in pagesValues" :key="index">{{
          tab.title
        }}</v-tab>
      </v-tabs>
      <v-card-text>
        <v-tabs-items v-model="activeTab">
          <v-tab-item v-for="(tab, tabIndex) in pagesValues" :key="tabIndex">
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
                        v-if="input.preview && input.preview === input.value"
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
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "CreateCase",

  data() {
    return {
      initialLoading: false,
      isSubmitingForm: false,
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
    };
  },
  created() {
    this.init();
  },
  computed: {
    ...mapState("cases", ["pagesValues", "selectedForm"]),
    ...mapState("app", ["navTemplates"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("cases", [
      "getPagesValues",
      "validateFormData",
      "updatePages",
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
        .then((_) => {
          this.setCurrentBread();
        })
        .finally((_) => {
          this.initialLoading = false;
        });
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
    async saveForm() {
      const { id } = this.$route.params;

      this.isSubmitingForm = true;
      if (await this.validateFormData()) {
        await this.updatePages(id);
        this.isSubmitingForm = false;
        this.$router.push({ path: "/cases/1" });
      } else {
        this.showErrors = true;
        this.isSubmitingForm = false;

        console.log("some fields is required");
      }
    },
  },
};
</script>

<style scoped></style>
