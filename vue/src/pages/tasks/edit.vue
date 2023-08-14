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
                v-model="form.type"
                :items="types"
                :label="$t('tasks.types')"
                :error-messages="errors['types']"
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
                v-model="form.document_id"
                :items="documents"
                :label="$t('tasks.document')"
                :error-messages="errors['document_id']"
                item-text="name"
                item-value="id"
                outlined
                dense
              ></v-autocomplete>
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
        name: "",
        type: "",
        document_id: null,
        user_id: null,
        assigner_id: null,
        due_date: "",
      },
      dueDateModal: false,
      types: ["Type 1", "Type 2", "Type 3"],
      errors: {},
      isDateInvalid: false,
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("tasks", ["users","task", "documents"]),
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
    ...mapActions("tasks", ["updateTask","getTask", "getUsers", "getDocuments"]),
    open() {
      const { id } = this.$route.params;
      if (!id) {
        this.$router.push({ name: "documents-list" });
        return;
      }

      this.loading = true;
      this.getTask(id)
      this.getUsers().then(() => {});
      this.getDocuments()
        .then(() => {
          this.loading = false;
          const { name, type, document_id, user_id, assigner_id, due_date } =
            this.task ?? {};
            this.dueDate = new Date(due_date)
        .toISOString()
        .substr(0, 10),
          this.form = { name, type, document_id, user_id, assigner_id };
        })
        .catch(() => {
          this.loading = false;
        });
    },
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${day}-${month}-${year}`;
    },
    updateForm() {
      this.loading = true;
      this.errors = {};
      this.form.due_date = this.formatDate(this.dueDate)
      this.updateTask(this.form)
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "tasks-list" });
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
