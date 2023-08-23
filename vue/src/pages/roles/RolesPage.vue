<template>
  <div class="d-flex flex-column flex-grow-1">
    <div class="d-flex align-center py-1">
      <div>
        <!-- <div class="display-1">{{ $t("users.rolesList") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs> -->
      </div>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        :to="{ name: 'roles-create' }"
        v-can="'create-role'"
      >
        {{ $t("users.createRole") }}
      </v-btn>
      <!--      <v-btn icon @click="open()">-->
      <!--        <v-icon>mdi-refresh</v-icon>-->
      <!--      </v-btn>-->
    </div>
    <!-- roles list -->

    <v-row dense class="">
      <div class="col-md-4 mb-2" v-for="item in roles">
        <v-card class="mx-auto">
          <v-card-title class="d-flex justify-space-between">
            <div class="title font-weight-bold text--primary">
              {{ item.display_name }}
            </div>
            <div>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    small
                    color="warning"
                    class="mx-1"
                    elevation="0"
                    :to="{ name: 'roles-edit', params: { id: item.id } }"
                    v-bind="attrs"
                    v-on="on"
                    v-can="'create-type'"
                  >
                    <v-icon> mdi-pencil </v-icon>
                  </v-btn>
                </template>
                <span>{{ $t("users.editRole") }}</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    small
                    color="error"
                    elevation="0"
                    @click.prevent="deleteRole(item)"
                    v-can="'delete-role'"
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon> mdi-close </v-icon>
                  </v-btn>
                </template>
                <span> {{ $t("users.deleteRole") }}</span>
              </v-tooltip>

              <!-- <v-btn
                  color="dark"
                  @click.prevent="deleteRole(item)"
                  v-can="'delete-role'"
                >
                  {{ $t("users.deleteRole") }}
                </v-btn> -->
            </div>
          </v-card-title>
          <v-card-text class="px-3 pt-3">
            <!-- <p>{{ $t("texts.totalUsersWithThisRole") }}: 5</p> -->
            <div class="d-flex flex-column overflow-y">
              <div
                v-for="item in item.permissions"
                class="d-flex align-center mb-1 text--primary"
              >
                <span class="bullet bg-primary mx-1"></span>
                {{ item.replace("-", " ") | uppercase }}
              </div>
            </div>
          </v-card-text>
          <v-card-actions class="px-3 pb-3">
            <!--            <v-btn-->
            <!--              color="dark"-->
            <!--              :to="{ name: 'roles-view', params: { id: item.id } }"-->
            <!--            >-->
            <!--              {{ $t("users.viewRole") }}-->
            <!--            </v-btn>-->
          </v-card-actions>
        </v-card>
      </div>
    </v-row>
  </div>
</template>

<script>
import { ask } from "@/helpers";
import { mapActions, mapState } from "vuex";

export default {
  components: {},
  data() {
    return {
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.usersManagement"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("users.rolesList"),
        },
      ],

      // roles: []
    };
  },
  computed: {
    ...mapState("roles", ["roles"]),
  },
  created() {
    this.open();
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("users.rolesList"),
    });
  },

  methods: {
    ...mapActions("roles", ["getRoles", "removeRole"]),
    ...mapActions("app", ["setBreadCrumb"]),
    open() {
      this.isLoading = true;
      this.getRoles()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    async deleteRole({ name, id }) {
      const message = `Are you sure to delete role ${name} ?`;
      const { isConfirmed } = await ask(message);
      if (isConfirmed) {
        this.loading = true;
        this.removeRole(id)
          .then(() => {
            this.loading = false;
            this.open();
          })
          .catch(() => {
            this.loading = false;
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.overflow-y {
  height: 200px;
  max-height: 200px;
  overflow: auto;
}
</style>
