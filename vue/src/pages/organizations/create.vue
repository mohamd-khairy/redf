<template>
  <div class="flex-grow-1">
    <div class="d-flex align-center py-3">
   
    </div>

    <v-card :loading="loading">
      <v-card-text class="p-3 roles">
        <v-form>
          <v-row dense>
            <v-col cols="12" >
              <v-text-field
                v-model="form.name"
                :label="$t('organizations.orgName')"
                :error-messages="errors['name']"
                outlined
                dense
              />
            </v-col>
            <v-col cols="12" md="12">
              <v-textarea
                v-model="form.description"
                :error-messages="errors['display_name']"
                :label="$t('organizations.orgDesc')"
                outlined
                dense
              ></v-textarea>
            </v-col>
          </v-row>
        
          <div class="d-flex">
            <v-btn
              :loading="loading"
              :disabled="loading"
              color="primary"
              @click="createOrg()"
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
          text: this.$t("organizations.organizationsManagement"),
          disabled: false,
          href: "#"
        },
       
        {
          text: this.$t("organizations.organizationsList"),
          to: "/organizations/list",
          exact: true
        },
        {
          text: this.$t("organizations.createOrg")
        }
      ],
      loading: false,
      form: {
        name: "",
        description: "",
        
      },
      errors: {}
    };
  },
  computed: {
    
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("organizations.organizationsList")
    });
    
  },
 
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("organizations", ["createOrganization"]),
    createOrg() {
      this.loading = true;
      this.errors = {};
      this.createOrganization(this.form)
        .then(() => {
          this.loading = false;
          this.$router.push({ name: "organizations-list" });
        })
        .catch(error => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data ?? {};
            this.errors = errors ?? {};
          }
        });
    }
  }
};
</script>
