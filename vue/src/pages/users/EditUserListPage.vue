<template>
  <div class="flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">
          {{ $t("users.editUser") }} {{ user.name && `- ${user.name}` }}
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>

    </div> -->

    <div
      v-if="user.role === 'ADMIN'"
      class="d-flex align-center font-weight-bold primary--text my-2"
    >
      <v-icon small color="primary">mdi-security</v-icon>
      <span class="ma-1">{{ $t("texts.administrator") }}</span>
    </div>

    <div class="mb-4">
      <div class="d-flex">
        <span class="font-weight-bold">{{ $t("tables.email") }}</span>
        <span class="mx-1">
          <copy-label :text="user.email" />
        </span>
      </div>
      <div class="d-flex">
        <span class="font-weight-bold">{{ $t("tables.id") }}</span>
        <span class="mx-1">
          <copy-label :text="user.id + ''" />
        </span>
      </div>
    </div>

    <v-tabs v-model="tab" :show-arrows="false" background-color="transparent">
      <v-tab to="#tabs-account">{{ $t("users.account") }}</v-tab>
      <!-- <v-tab to="#tabs-information">{{ $t("users.information") }}</v-tab> -->
    </v-tabs>

    <v-tabs-items v-model="tab">
      <v-tab-item value="tabs-account">
        <account-tab
          ref="tabs-account"
          @updateUser="updateUser"
          :loading="loading"
          :errors="errors"
        ></account-tab>
      </v-tab-item>

      <!-- <v-tab-item value="tabs-information">
        <information-tab ref="tabs-information" :user="user"></information-tab>
      </v-tab-item> -->
    </v-tabs-items>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import CopyLabel from "../../components/common/CopyLabel";
import AccountTab from "./EditUser/AccountTab";
import InformationTab from "./EditUser/InformationTab";
import { makeToast } from "@/helpers";

export default {
  components: {
    CopyLabel,
    AccountTab,
    InformationTab
  },
  data() {
    return {
      tab: null,
      loading: false,
      errors: {},
      breadcrumbs: [
        {
          text: this.$t("menu.usersManagement"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("users.usersList"),
          to: "/users/list",
          exact: true
        },
        {
          text: this.$t("users.editUser")
        }
      ]
    };
  },
  computed: {
    user: {
      get() {
        return this.$store.state.users.user;
      },
      set(val) {
        this.$store.commit("users/SET_USER", val);
      }
    }
  },
  created() {
    const { id } = this.$route.params;
    const pageTitle = this.$t("users.editUser") + (this.user.name ? " - " + this.user.name : "");
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle
    });
    this.loading = true;
    this.getUser(id)
      .then(() => {
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    ...mapActions("users", ["getUser", "editUser"]),
    ...mapActions("app", ["setBreadCrumb"]),
    refreshUser() {
      const { id } = this.$route.params;
      this.loading = true;
      this.getUser(id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    updateUser(data) {
      data.set("_method", "PUT");
      this.loading = true;
      this.errors = {};
      this.editUser(data)
        .then(response => {
          this.loading = false;
          this.errors = {};
          makeToast("success", response.data.message);
          this.$router.push({ name: "users-list" });
        })
        .catch(error => {
          this.loading = false;
          if (error.response.status == 422) {
            const { errors } = error?.response?.data;
            this.errors = errors ?? {};
          }
        });
    }
  }
};
</script>
