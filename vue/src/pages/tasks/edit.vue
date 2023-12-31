<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3"></div>
    <v-card>
      <v-card-text class="p-3 roles">
        <v-form>
          <v-row dense>
            <v-col cols="6">
              <v-text-field
                v-model="form.name"
                :label="$t('tasks.taskName')"
                :error-messages="errors['name']"
                outlined
                dense
              />
            </v-col>

            <v-col cols="6">
              <v-select
                v-model="form.status"
                :items="statuss"
                :label="$t('tasks.status')"
                :error-messages="errors['status']"
                outlined
                dense
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-autocomplete
                v-model="form.assigner_id"
                :items="users"
                :label="$t('tasks.assigned_to')"
                :error-messages="errors['assigner_id']"
                item-text="name"
                item-value="id"
                outlined
                dense
              ></v-autocomplete>
            </v-col>
            <v-col cols="6">
              <v-autocomplete
                v-model="form.form_request_id"
                :items="casesNames"
                item-text="name"
                item-value="id"
                :label="$t('tasks.case')"
                :error-messages="errors['form_request_id']"
                outlined
                dense
              ></v-autocomplete>
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
              <div
                class="mt-0 mb-2 d-flex justify-content-between align-item-center"
                v-if="uploadedFile"
              >
                <h6>{{ uploadedFile.name }}</h6>
                <a :href="uploadedFile.path" target="_blank">
                  <v-icon> mdi-file </v-icon>
                </a>
              </div>
            </v-col>

            <v-col cols="12" sm="6" md="6">
              <v-dialog
                ref="dialog"
                v-model="dueDateModal"
                :return-value.sync="dueDate"
                persistent
                width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="dueDate"
                    :label="$t('tasks.due_date')"
                    prepend-icon="mdi-calendar"
                    readonly
                    outlined
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker v-model="dueDate" scrollable>
                  <v-spacer></v-spacer>
                  <v-btn text color="primary" @click="dueDateModal = false">
                    Cancel
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="$refs.dialog.save(dueDate)"
                  >
                    OK
                  </v-btn>
                </v-date-picker>
              </v-dialog>
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="form.details"
                outlined
                :error-messages="errors['assigner_id']"
                dense
                :label="$t('tasks.details')"
              ></v-textarea>
            </v-col>
          </v-row>

          <div class="d-flex">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="updateForm()"
            >
              {{ $t("general.save") }}
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
      breadcrumbs: [
        {
          text: this.$t("tasks.tasksManagement"),
          disabled: false,
          href: "#",
        },

        {
          text: this.$t("tasks.tasksList"),
          to: "/tasks/list",
          exact: true,
        },
        {
          text: this.$t("tasks.createTask"),
        },
      ],
      loading: false,
      dueDate: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        .toISOString()
        .substr(0, 10),
      form: {
        details: "",
        name: "",
        status: "",
        form_request_id: "",
        user_id: null,
        file: null,
        assigner_id: null,
        due_date: "",
      },
      dueDateModal: false,
      statuss: ["Status 1", "Status 2", "Status 3"],
      errors: {},
      isDateInvalid: false,
      uploadedFile: null,
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("tasks", ["users", "task", "casesNames"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("tasks.tasksList"),
    });
    this.open();
  },

  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("tasks", [
      "updateTask",
      "getTask",
      "getUsers",
      "getCasesNames",
    ]),
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
      this.getTask(id);
      this.getUsers();
      this.getCasesNames()
        .then(() => {
          this.loading = false;
          const {
            details,
            name,
            status,
            file,
            form_request_id,
            assigner_id,
            due_date,
            user_id,
          } = this.task ?? {};
          this.uploadedFile = file;
          (this.dueDate = new Date(due_date).toISOString().substr(0, 10)),
            (this.form = {
              details,
              name,
              status,
              file: null,
              form_request_id,
              user_id,
              assigner_id,
              due_date,
            });
        })
        .catch(() => {
          this.loading = false;
        });
    },
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${year}-${month}-${day}`;
    },
    updateForm() {
      this.loading = true;
      this.errors = {};
      this.form.due_date = this.formatDate(this.dueDate);
      if (!this.form.file) {
        delete this.form.file;
      }
      this.updateTask(this.form)
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "tasks-list" });
        })
        .catch((error) => {
          this.loading = false;
          console.log(error);
          // if (error.response.status == 422) {
          //   const { errors } = error?.response?.data ?? {};
          //   this.errors = errors ?? {};
          // }
        });
    },
  },
};
</script>
