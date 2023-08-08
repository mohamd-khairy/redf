<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('users.usersList') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn color="primary" to="/users/create" v-can="'create-user'">
        {{ $t('users.createUser') }}
      </v-btn>
    </div> -->
    <v-card>
      <!-- users list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedUsers.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.verify') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.disabled') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-divider></v-divider>-->
              <v-list-item @click="deleteAllUsers()">
                <v-list-item-title>{{
                  $t("general.delete")
                }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field
            v-model="searchQuery"
            append-icon="mdi-magnify"
            class="flex-grow-1 mr-md-2"
            solo
            hide-details
            dense
            clearable
            :placeholder="$t('general.search')"
            @keyup.enter="searchUser(searchQuery)"
          ></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2 "
                elevation="0"
                v-bind="attrs"
                v-on="on"
                :to=formTypesUrl
                v-can="'create-user'"
              >
                <v-icon>
                  mdi-plus
                </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("cases.createCase") }}</span>
          </v-tooltip>
          <v-btn
            :loading="isLoading"
            icon
            @click.prevent="open()"
            small
            class="ml-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table
        show-select
        v-model="selectedUsers"
        :headers="headers"
        :items="userItems"
        :options.sync="options"
        class="flex-grow-1"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="totalUsers"
      >
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">
            # <copy-label :text="item.id + ''" />
          </div>
        </template>

        <template v-slot:item.email="{ item }">
          <div class="d-flex align-center py-1">
            <v-avatar size="32" class="elevation-1 grey lighten-3 ml-2">
              <v-img :src="item.avatar" />
            </v-avatar>
            <div class="ml-1 caption font-weight-bold">
              <copy-label :text="item.email" />
            </div>
          </div>
        </template>

        <template v-slot:item.role="{ item }">
          <v-chip
            label
            small
            v-for="(item, index) in item.roles"
            :key="index"
            class="font-weight-bold"
            :color="item.display_name === 'Admin' ? 'primary' : undefined"
          >
            {{ item.display_name }}
          </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          <div>{{ item.created_at | formatDate("lll") }}</div>
        </template>

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn
              color="primary"
              icon
              :to="`/users/edit/${item.id}`"
              v-can="'update-user'"
            >
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn
              color="error"
              icon
              @click.prevent="deleteItem(item.id)"
              v-can="'delete-user'"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </div>
        </template>
        <template v-slot:no-data>
          <div class="text-center my-2 primary--text" color="primary">
            <emptyDataSvg></emptyDataSvg>
            <div class="dt-no_data">
              {{ $t("general.no_data_available") }}
            </div>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  components: {
    CopyLabel,
    emptyDataSvg
  },
  data() {
    return {
      page: 1,
      totalUsers: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.casesManagement"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.cases")
        }
      ],

      searchQuery: "",
      selectedUsers: [],
      userItems: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.email"), value: "email" },
        { text: this.$t("tables.name"), value: "name" },
        { text: this.$t("tables.role"), value: "role" },
        { text: this.$t("tables.created"), value: "created_at" },
        { text: "", sortable: false, align: "right", value: "action" }
      ],
      formTypesUrl:''
    };
  },
  watch: {
    selectedUsers(val) {},
    options: {
      handler() {
        this.open();
      }
    },
    deep: true,
    searchQuery() {
      this.open();
    }
  },
  computed: {
    ...mapState("users", ["users"])
  },
  created() {
    let {id} = this.$route.params;
    this.formTypesUrl = '/cases/form-types/'+id
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("cases.casesList")
    });
  },
  mounted() {
    // this.open()
  },
  methods: {
    ...mapActions("users", ["getUsers", "deleteUser", "deleteAll"]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchUser() {},
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";

      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? ""
      };
      this.getUsers(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.userItems = this.users.data;
            this.totalUsers = this.users.total;
            this.numberOfPages = this.users.last_page;
          } else {
            this.userItems = this.users;
            this.totalUsers = this.users.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    async deleteItem(id) {
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteUser(id)
          .then(response => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },

    async deleteAllUsers() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");
      if (isConfirmed) {
        if (this.selectedUsers.length) {
          this.selectedUsers.forEach(item => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1
        };
        this.isLoading = true;
        this.deleteAll(data)
          .then(response => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
