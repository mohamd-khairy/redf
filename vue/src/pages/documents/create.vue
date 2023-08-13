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
                <v-date-picker :format="dateFormat" v-model="form.start_date" scrollable>
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
                <v-date-picker :format="dateFormat" v-model="form.end_date" scrollable>
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
          </v-row>

          <div class="d-flex">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="createDoc()"
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
          text: this.$t("documents.createDoc"),
        },
      ],
      loading: false,
      form: {
        name: "",
        status: "",
        priority: "",
        type: "",
        user_id: null,
        start_date: null,
        end_date:null,
        // start_date: new Date(
        //   Date.now() - new Date().getTimezoneOffset() * 60000
        // )
        //   .toISOString()
        //   .substr(0, 10),
        // end_date: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
        //   .toISOString()
        //   .substr(0, 10),
      },
      dateFormat: 'dd-MM-yyyy',

      startDateModal: false,
      endDateModal: false,
      status: ["s1", "s2", "s3"],
      priority: ["p1", "p2"],
      types: ["t1", "t2", "t3"],
      errors: {},
      isDateInvalid: false,
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("documents.documentsList"),
    });
  },

  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("documents", ["createDocument"]),
    validateDates() {
      if (this.startDate && this.endDate) {
        if (this.startDate >= this.endDate) {
          this.isDateInvalid = true;
        } else {
          this.isDateInvalid = false;
        }
      }
    },
    formatDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${day}-${month}-${year}`
      },
    createDoc() {
      this.loading = true;
      this.errors = {};
      this.form.user_id = this.user.id;
      const modForm = {...this.form}
      console.log(modForm);
      modForm.start_date = this.formatDate(this.form.start_date)
      modForm.end_date = this.formatDate(this.form.end_date)
      this.createDocument(modForm)
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
  },
};
</script>