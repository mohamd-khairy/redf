<template>
  <div class="flex-grow-1 my-2">
    <v-tabs background-color="primary" center-active dark>
      <v-tab>
        {{ $t("templates.basic_data") }}
      </v-tab>
      <v-tab>
        {{ $t("templates.template_edit") }}
      </v-tab>
      <v-tab>
        {{ $t("templates.stages") }}
      </v-tab>

      <v-tab-item>
        <v-card>
          <v-card-title>{{ $t("templates.updateTemplate") }}</v-card-title>
          <v-card-text>
            <div class="d-flex flex-column flex-sm-row">
              <div class="flex-grow-1 pt-2 pa-sm-2">
                <v-row>
                  <v-col cols="6">
                    <v-text-field
                      v-model="template.name"
                      :rules="[rules.required]"
                      :label="$t('tables.name')"
                      :error-messages="errors['name']"
                      color="primary"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="6">
                    <v-text-field
                      v-model="template.description"
                      :rules="[rules.required]"
                      :label="$t('tables.description')"
                      :error-messages="errors['description']"
                      color="primary"
                    ></v-text-field>
                  </v-col>
                </v-row>

                <div class="mt-2">
                  <v-btn
                    :loading="loading"
                    :disabled="loading"
                    @click="updateTemplateII"
                    color="primary"
                  >
                    {{ $t("general.save") }}
                  </v-btn>
                </div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-tab-item>
      <v-tab-item>
        <DynamicFormBuilder :id="1"></DynamicFormBuilder>
      </v-tab-item>
      <v-tab-item>
        <draggable
          :list="stages"
          @start="onDragStart"
          @end="onDragEnd"
          :animation="200"
          ghost-class="ghost-card"
          :force-fallback="true"
        >
          <div
            v-for="stage in stages"
            :key="stage.id"
            class="mt-2 cursor-move stage-edit"
          >
            <v-checkbox
              v-model="selected"
              multiple
              :value="stage.id"
              hide-details
              color="#fff"
              class="checkboxClass mt-0"
              :class="getClassActive(stage.id)"
              draggable="true"
            >
              <template v-slot:label class="stage-cont">
                <div class="labelClass" :class="getLabelClass(stage.id)">
                  {{ stage.name }}
                </div>
                <div>
                  <v-chip
                    v-for="action in stage.actions"
                    class="me-1 chip-custom"
                  >
                    {{ action }}
                  </v-chip>
                </div>
              </template>
            </v-checkbox>
          </div>
        </draggable>
        <div class="mt-10">
          <v-btn
            :loading="loading"
            :disabled="loading"
            @click="updateStages"
            color="primary"
          >
            {{ $t("general.save") }}
          </v-btn>
        </div>
      </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";
import DynamicFormBuilder from "./DynamicFormBuilder.vue";
import axios from "axios";
import draggable from "vuedraggable";

export default {
  components: {
    DynamicFormBuilder,
    draggable,
  },
  data() {
    return {
      template: {
        name: "",
        description: "",
        template_id: "",
      },
      errors: {},
      loading: false,
      dragging: false,
      breadcrumbs: [
        {
          text: this.$t("menu.templates"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("templates.templatesList"),
          to: "/templates",
          exact: true,
        },
        {
          text: `${this.$t("templates.updateTemplate")}`,
        },
      ],
      rules: {
        required: (value) =>
          (value && Boolean(value)) || this.$t("general.fieldRequired"),
      },
      selected: [],
      orderedSelected: [],
    };
  },
  computed: {
    ...mapState("stages", ["stages"]),
  },
  created() {
    const { id: formId } = this.$route.params;

    this.getStages(formId);
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("templates.updateTemplate"),
    });
    axios.get(`get-form/${this.$route.params.id}`).then((data) => {
      this.template = data.data.data;
      this.selected = this.template.stages.map((item) => {
        return item.id;
      });
    });
  },
  methods: {
    ...mapActions("templates", ["updateForm"]),
    ...mapActions("stages", ["getStages", "updateTemplateStages"]),
    ...mapActions("app", ["setBreadCrumb"]),
    onDragStart() {
      this.dragging = true;
    },
    onDragEnd() {
      this.dragging = false;
      this.orderedSelected = this.stages.map(function (item, index) {
        return { stage_id: item.id, order: index + 1 };
      });
    },
    getClassActive(id) {
      if (this.selected.includes(id)) {
        return "checkboxClass1";
      } else {
        return "checkboxClass2";
      }
    },
    getLabelClass(id) {
      if (this.selected.includes(id)) {
        return "labelClass1";
      }
    },
    buildForm(data) {
      let keys = Object.keys(data);
      let form = new FormData();
      form.append("_method", "PUT");
      for (let index = 0; index < keys.length; index++) {
        const key = keys[index];
        if (data[key]) {
          form.set(key, data[key]);
        }
      }
      return form;
    },
    updateTemplateII() {
      this.loading = true;
      this.errors = {};
      let form = this.buildForm(this.template);
      this.updateForm(form)
        .then((response) => {
          this.loading = false;
          makeToast("success", response.data.message);
          this.$router.push({ name: "TemplatesList" });
        })
        .catch((error) => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    },
    updateStages() {
      this.loading = true;
      let { id } = this.$route.params;
      let array = [];
      if (this.orderedSelected.length) {
        array = this.orderedSelected
          .filter((element) => this.selected.includes(element.stage_id))
          .map((item, index) => {
            return { stage_id: item.stage_id, order: index + 1 };
          });
      } else {
        array = this.selected.map((item, index) => {
          return { stage_id: item, order: index + 1 };
        });
      }

      let data = {
        form_id: id,
        stages: array,
      };
      console.log(array);
      this.updateTemplateStages(data)
        .then((response) => {
          this.loading = false;
          makeToast("success", response.data.message);
          this.$router.push({ name: "TemplatesList" });
        })
        .catch((error) => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    },
  },
};
</script>
<style>
.checkboxClass .v-label {
  display: flex !important;
  flex-direction: row-reverse !important;
}
.checkboxClass .chip-custom {
  background: #d1dfdf !important;
  font-size: 14px !important;
  font-weight: 700 !important;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  border-radius: 8px !important;
  color: #014c4f !important;
}
.checkboxClass1 .chip-custom {
  background-color: #fff !important;
}
</style>
<style lang="scss" scoped>
.stage-edit {
  border-radius: 8px;
  overflow: hidden;
}
.checkboxClass {
  //width:280px;
  //height:60px;
  direction: ltr;
  padding: 18px 20px;
}
.checkboxClass1 {
  background-color: #014c4f9c;
}
.checkboxClass2 {
  background-color: #ffffff;
}
.labelClass {
  font-weight: bold;
  margin-inline-start: auto;
}
.labelClass1 {
  color: #fff;
}
</style>
