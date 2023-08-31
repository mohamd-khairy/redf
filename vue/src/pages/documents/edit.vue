<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3"></div>

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <v-row dense>
            <v-col cols="6">
              <v-text-field
                v-model="form.name"
                :label="$t('documents.docName')"
                :error-messages="errors['name']"
                outlined
                dense
              />
            </v-col>
            <v-col cols="6">
              <v-select
                v-model="form.status"
                :items="status"
                :label="$t('documents.status')"
                :error-messages="errors['status']"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                v-model="form.type"
                :items="types"
                :label="$t('documents.types')"
                :error-messages="errors['types']"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                v-model="form.priority"
                :items="priority"
                :label="$t('documents.priority')"
                :error-messages="errors['priority']"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="12" sm="6" md="6">
              <v-dialog
                ref="dialogStart"
                v-model="startDateModal"
                :return-value.sync="form.start_date"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="form.start_date"
                    @input="validateDates"
                    :label="$t('general.start_date')"
                    prepend-icon="mdi-calendar"
                    readonly
                    outlined
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="form.start_date" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="startDateModal = false">
                    {{ $t("general.cancel") }}
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialogStart.save(form.start_date)"
                  >
                    {{ $t("general.ok") }}
                  </v-btn>
                </v-date-picker>
              </v-dialog>
            </v-col>
            <v-col cols="12" sm="6" md="6">
              <v-dialog
                ref="dialog"
                v-model="endDateModal"
                :return-value.sync="form.end_date"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="form.end_date"
                    @input="validateDates"
                    :label="$t('general.end_date')"
                    prepend-icon="mdi-calendar"
                    readonly
                    outlined
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="form.end_date" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="endDateModal = false">
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog.save(form.end_date)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-dialog>
            </v-col>
            <v-col cols="6">
              <v-file-input
                outlined
                dense
                :v-model="form.file"
                :label="$t('tasks.document')"
                @change="(file) => handleFileUpload(file)"
                click:clear="handleRemoveFile"
                :error-messages="errors['file']"
              >
              </v-file-input>
              <!-- <div
                class="mt-0 mb-2 d-flex justify-content-between align-item-center"
                v-if="this.full_path"
              >
                <h6>{{ this.form.name }}</h6>
                <a :href="this.full_path" target="_blank">
                  <v-icon> mdi-file </v-icon>
                </a>
              </div> -->
            </v-col>
            <v-col cols="6">
              <div class="d-flex align-items-center">
                <label class="ms-2" for="">{{ $t("general.belongsTo") }}</label>
                <v-spacer></v-spacer>
                <v-checkbox
                  class="me-3 mt-0"
                  v-model="belongsTo"
                  :label="$t('general.case')"
                  value="case"
                ></v-checkbox>
                <v-checkbox
                  class="mt-0"
                  v-model="belongsTo"
                  :label="$t('general.consultation')"
                  value="consultation"
                ></v-checkbox>
              </div>
            </v-col>
            <v-col cols="6">
              <div
                class="mt-0 mb-2 d-flex justify-content-between align-item-center"
                v-if="this.full_path"
              >
                <h6>{{ this.form.name }}</h6>
                <a :href="this.full_path" target="_blank">
                  <v-icon> mdi-file </v-icon>
                </a>
              </div></v-col
            >
            <v-col cols="6" v-if="belongsTo">
              <v-autocomplete
                v-if="belongsTo === 'case'"
                v-model="form.form_request_id"
                :items="casesNames"
                item-text="name"
                item-value="id"
                :label="$t('tasks.case')"
                :error-messages="errors['form_request_id']"
                outlined
                dense
              ></v-autocomplete>
              <v-autocomplete
                v-else
                v-model="form.consultation_id"
                :items="consultationNames"
                item-text="name"
                item-value="id"
                :label="$t('general.consultation')"
                :error-messages="errors['consultation_id']"
                outlined
                dense
              ></v-autocomplete>
            </v-col>
          </v-row>

          <div class="d-flex">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="updateDoc()"
            >
              {{ $t("general.update") }}
            </v-btn>
          </div>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  components: {},
  data() {
    return {
      belongsTo: null,
      breadcrumbs: [
        {
          text: this.$t("documents.documentsManagement"),
          disabled: false,
          href: "#",
        },

        {
          text: this.$t("documents.documentsList"),
          to: "/documents/list",
          exact: true,
        },
        {
          text: this.$t("documents.editDoc"),
        },
      ],
      loading: false,
      form: {
        name: "",
        status: "",
        priority: "",
        type: "",
        form_request_id: "",
        consultation_id: "",
        user_id: null,
        file: null,
        path: null,
        start_date: null,
        end_date: null,
      },
      full_path: null,
      startDateModal: false,
      endDateModal: false,
      status: ["active", "notactive"],
      priority: ["high", "medium", "low"],
      types: ["case", "consultation", "task"],
      errors: {},
      isDateInvalid: false,
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("documents", ["document"]),
    ...mapState("tasks", ["casesNames", "consultationNames"]),
  },
  watch: {
    belongsTo(val) {
      if (!val) {
        this.form.form_request_id = "";
        this.form.consultation_id = "";
      }
    },
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("documents.documentsList"),
    });
    this.open();
  },

  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("documents", ["updateDocument", "getDocument"]),
    ...mapActions("tasks", ["getCasesNames", "getConsultationNames"]),
    handleFileUpload(file, input) {
      if (file) {
        const fileName = file.name.split(".")[0];
        const fileExtension = file.name.split(".")[1];
        this.form.file = file;
      }
    },
    handleRemoveFile() {
      this.form.file = null;
    },
    open() {
      const { id } = this.$route.params;
      if (!id) {
        this.$router.push({ name: "documents-list" });
        return;
      }

      this.loading = true;
      this.getCasesNames();
      this.getConsultationNames();
      this.getDocument(id)
        .then(() => {
          this.loading = false;
          const { name, status, type, priority } = this.document ?? {};
          this.form = { name, status, type, priority };
          this.form.form_request_id = this.document.fileable_id;
          this.form.path = this.document.path.split("storage/")[1];
          this.full_path = this.document.path;
          this.form.start_date = this.formatDate(
            this.document.start_date.split(" ")[0]
          );
          this.form.end_date = this.formatDate(
            this.document.end_date.split(" ")[0]
          );
        })
        .catch(() => {
          this.loading = false;
        });
    },
    updateDoc() {
      this.loading = true;
      this.errors = {};
      this.updateDocument(this.form)
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "documents-list" });
        })
        .catch((error) => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    },
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${year}-${month}-${day}`;
    },
  },
};
</script>
