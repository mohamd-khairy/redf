<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-card>
      <v-tabs v-model="activeTab">
        <v-tab v-for="(tab, index) in pages" :key="index">{{
          tab.title
        }}</v-tab>
      </v-tabs>
      <v-tabs-items v-model="activeTab">
        <v-tab-item v-for="(tab, index) in pages" :key="index">
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
                      :label="input.label"
                      :required="input.required"
                      dense
                    ></v-text-field>
                  </template>
                  <template v-else-if="input.type === 'textarea'">
                    <v-textarea
                      outlined
                      dense
                      v-model="input.value"
                      :label="input.label"
                      :required="input.required"
                    ></v-textarea>
                  </template>
                  <template v-else-if="input.type === 'file'">
                    <v-file-input
                      :label="input.label"
                      outlined
                      dense
                    ></v-file-input>
                  </template>
                  <template v-else-if="input.type === 'select'">
                    <v-select
                      v-model="input.selectedOption"
                      :items="input.childList"
                      item-text="text"
                      :label="input.label"
                      :required="input.required"
                      outlined
                      dense
                    ></v-select>
                  </template>
                  <template v-else-if="input.type === 'radio'">
                    <v-radio-group
                      v-model="input.selectedOption"
                      :label="input.label"
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
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "CreateCase",

  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("menu.casesManagement"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("menu.cases"),
        },
      ],
      activeTab: null,
    };
  },
  created() {
    this.init();
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("cases.casesList"),
    });
  },
  computed: {
    ...mapState("cases", ["pages", "selectedFormName"]),
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("cases", ["getPages"]),
    init() {
      const { id } = this.$route.params;
      if (!id) {
        this.$router.push({ name: "dashboard-analytics" });
      }
      this.getPages(id).then((_) => {
        this.breadcrumbs.push({
          text: this.$t(this.selectedFormName),
        });
        this.setBreadCrumb({
          breadcrumbs: this.breadcrumbs,
          pageTitle: this.$t("cases.casesList"),
        });

        console.log(this.pages);
      });
    },
    inputWidth(width) {
      return width.split("-")[1];
    },
  },
};
</script>

<style scoped></style>
