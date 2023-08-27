<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3"></div>

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <v-row dense>
            <v-col cols="12">
              <v-text-field
                v-model="form.name"
                :label="$t('branches.BranchName')"
                :error-messages="errors['name']"
                :rules="[rules.required]"
                outlined
                required
                dense
              />
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="form.description"
                :label="$t('branches.BranchDesc')"
                :error-messages="errors['status']"
                outlined
                dense
              />
            </v-col>
          </v-row>
          <div class="d-flex">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="createNewBranch()"
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
import { makeToast } from "@/helpers";
export default {
  components: {},
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("branches.branches"),
          disabled: false,
          href: "#",
        },

        {
          text: this.$t("branches.branchesList"),
          to: "/branches/list",
          exact: true,
        },
        {
          text: this.$t("branches.createBranch"),
        },
      ],
      rules: {
        required: (value) =>
          (value && Boolean(value)) || this.$t("general.fieldRequired"),
      },
      loading: false,
      form: {
        name: "",
        description: "",
      },
      dateFormat: "dd-MM-yyyy",
      errors: {},
      isDateInvalid: false,
    };
  },
  computed: {},
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("documents.documentsList"),
    });
  },

  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("branches", ["createBranch"]),

    createNewBranch() {
      this.loading = true;
      this.errors = {};
      if (!this.form.name) {
        this.errors.name = this.$t("branches.branchNameReq");
        this.loading = false;
        return;
      }
      this.createBranch(this.form)
        .then(() => {
          this.loading = false;
          makeToast("success", this.$t("branches.branchCreated"));
          this.$router.push({ name: "branches-list" });
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
